<?php
/**
 * Corbel vs HubSpot chat A/B experiment.
 *
 * Self-hosted split testing: the theme assigns visitors to a chat provider
 * client-side (sticky cookie), records exposures and chat goals into a daily
 * aggregate table via REST, and reports the same events to GA4 (dataLayer)
 * and Microsoft Clarity. Results are shown on the Tools -> Chat A/B Test
 * dashboard (see chat-experiment-dashboard.php).
 *
 * If Nelio A/B Testing is ever installed, its variant snippets take
 * precedence automatically: ChatExperiment.js prefers a Nelio-provided
 * `window.ntmChatProvider` over self-assignment, and goals are mirrored to
 * `nab.convert()` when an experiment ID is registered.
 *
 * Goal indices: 0 conversation_started, 1 question_resolved,
 * 2 sales_handoff, 3 lead_generated.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard\ChatExperiment;

if (!defined('ABSPATH')) {
    exit;
}

const OPTION_NAME = 'ntm_chat_experiment';
const TABLE = 'ntm_chat_experiment_stats';
const DB_VERSION = 1;

const PROVIDERS = ['corbel', 'hubspot'];
const METRICS = [
    'exposure',
    'conversation_started',
    'question_resolved',
    'sales_handoff',
    'lead_generated',
];

/**
 * Experiment state stored in one option.
 *
 * @return array{status: string, corbel_split: int, started_at: string, stopped_at: string}
 */
function get_state(): array
{
    $defaults = [
        'status' => 'stopped', // stopped | running
        'corbel_split' => 20,  // percent of visitors assigned to Corbel
        'started_at' => '',
        'stopped_at' => '',
    ];
    $state = get_option(OPTION_NAME, []);

    return is_array($state) ? array_merge($defaults, $state) : $defaults;
}

/**
 * @param array<string, mixed> $changes
 */
function update_state(array $changes): void
{
    update_option(OPTION_NAME, array_merge(get_state(), $changes), false);
}

function is_running(): bool
{
    $running = get_state()['status'] === 'running';

    return (bool) apply_filters('ntm_chat_experiment_enabled', $running);
}

/**
 * Optional Nelio experiment ID; when set, goals are mirrored to Nelio.
 */
function get_experiment_id(): int
{
    return max(0, (int) apply_filters('ntm_chat_experiment_id', 0));
}

/**
 * HubSpot portal ID for the chat/tracker loader used by the hubspot variant.
 * Reuses the Standard Site Integrations setting so the portal is configured
 * in one place.
 */
function get_hubspot_portal_id(): string
{
    $settings = get_option('standard_site_integrations', []);
    $portal_id = is_array($settings)
        ? (string) ($settings['hubspot_portal_id'] ?? '')
        : '';

    if (!preg_match('/^[0-9]{1,20}$/', $portal_id)) {
        $portal_id = '';
    }

    return (string) apply_filters('ntm_chat_experiment_hubspot_portal_id', $portal_id);
}

/**
 * Client configuration merged into `window.ntmThirdPartyConfig`.
 *
 * @return array{enabled: bool, corbelSplit: int, experimentId: int, hubspotPortalId: string, trackUrl: string}
 */
function get_client_config(): array
{
    return [
        'enabled' => is_running(),
        'corbelSplit' => get_state()['corbel_split'],
        'experimentId' => get_experiment_id(),
        'hubspotPortalId' => get_hubspot_portal_id(),
        'trackUrl' => rest_url('ntm/v1/chat-experiment/track'),
    ];
}

/**
 * Exact custom splits need finer buckets than Nelio's default 24
 * `nabAlternative` slots (only relevant if Nelio is ever installed).
 */
function raise_nelio_bucket_precision(): int
{
    return 100;
}
add_filter('nab_max_combinations', __NAMESPACE__ . '\\raise_nelio_bucket_precision');

/**
 * Daily aggregate stats table. Counters only; no visitor-level data or PII.
 */
function table_name(): string
{
    global $wpdb;

    return $wpdb->prefix . TABLE;
}

function maybe_create_table(): void
{
    if ((int) get_option(OPTION_NAME . '_db_version', 0) >= DB_VERSION) {
        return;
    }

    global $wpdb;
    $table = table_name();
    $charset = $wpdb->get_charset_collate();

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta(
        "CREATE TABLE {$table} (
            stat_date date NOT NULL,
            provider varchar(20) NOT NULL,
            metric varchar(40) NOT NULL,
            hits bigint(20) unsigned NOT NULL DEFAULT 0,
            PRIMARY KEY  (stat_date, provider, metric)
        ) {$charset};"
    );

    update_option(OPTION_NAME . '_db_version', DB_VERSION, false);
}
add_action('admin_init', __NAMESPACE__ . '\\maybe_create_table');
add_action('rest_api_init', __NAMESPACE__ . '\\maybe_create_table');

function increment_metric(string $provider, string $metric): void
{
    global $wpdb;

    $wpdb->query(
        $wpdb->prepare(
            'INSERT INTO ' . table_name()
                . ' (stat_date, provider, metric, hits) VALUES (%s, %s, %s, 1)'
                . ' ON DUPLICATE KEY UPDATE hits = hits + 1',
            current_time('Y-m-d'),
            $provider,
            $metric
        )
    );
}

/**
 * Totals per provider/metric, optionally limited to the current run window.
 *
 * @return array<string, array<string, int>> provider => metric => hits
 */
function get_totals(bool $current_run_only = true): array
{
    global $wpdb;

    $sql = 'SELECT provider, metric, SUM(hits) AS hits FROM ' . table_name();
    $state = get_state();

    if ($current_run_only && $state['started_at'] !== '') {
        $sql .= $wpdb->prepare(
            ' WHERE stat_date >= %s',
            substr($state['started_at'], 0, 10)
        );
    }

    $sql .= ' GROUP BY provider, metric';
    $rows = $wpdb->get_results($sql, ARRAY_A) ?: [];

    $totals = [];
    foreach (PROVIDERS as $provider) {
        foreach (METRICS as $metric) {
            $totals[$provider][$metric] = 0;
        }
    }
    foreach ($rows as $row) {
        $provider = (string) $row['provider'];
        $metric = (string) $row['metric'];
        if (isset($totals[$provider][$metric])) {
            $totals[$provider][$metric] = (int) $row['hits'];
        }
    }

    return $totals;
}

/**
 * Daily breakdown for the dashboard trend table.
 *
 * @return array<int, array{stat_date: string, provider: string, metric: string, hits: int}>
 */
function get_daily_rows(int $days = 60): array
{
    global $wpdb;

    $rows = $wpdb->get_results(
        $wpdb->prepare(
            'SELECT stat_date, provider, metric, hits FROM ' . table_name()
                . ' WHERE stat_date >= DATE_SUB(%s, INTERVAL %d DAY)'
                . ' ORDER BY stat_date DESC, provider, metric',
            current_time('Y-m-d'),
            $days
        ),
        ARRAY_A
    ) ?: [];

    return array_map(
        static fn(array $row): array => [
            'stat_date' => (string) $row['stat_date'],
            'provider' => (string) $row['provider'],
            'metric' => (string) $row['metric'],
            'hits' => (int) $row['hits'],
        ],
        $rows
    );
}

/**
 * Two-proportion z-test.
 *
 * @return array{corbel_rate: float, hubspot_rate: float, lift: float, z: float, p: float, significant: bool}|null
 */
function significance(int $corbel_hits, int $corbel_n, int $hubspot_hits, int $hubspot_n): ?array
{
    if ($corbel_n < 1 || $hubspot_n < 1) {
        return null;
    }

    $p1 = $corbel_hits / $corbel_n;
    $p2 = $hubspot_hits / $hubspot_n;
    $pooled = ($corbel_hits + $hubspot_hits) / ($corbel_n + $hubspot_n);
    $se = sqrt($pooled * (1 - $pooled) * (1 / $corbel_n + 1 / $hubspot_n));

    if ($se <= 0.0) {
        return null;
    }

    $z = ($p1 - $p2) / $se;
    // Two-tailed p-value via the complementary error function.
    $p_value = erfc(abs($z) / M_SQRT2);

    return [
        'corbel_rate' => $p1,
        'hubspot_rate' => $p2,
        'lift' => $p2 > 0 ? ($p1 - $p2) / $p2 : 0.0,
        'z' => $z,
        'p' => $p_value,
        'significant' => $p_value < 0.05,
    ];
}

/**
 * Complementary error function (Abramowitz & Stegun 7.1.26 approximation).
 */
function erfc(float $x): float
{
    $t = 1 / (1 + 0.3275911 * abs($x));
    $y = $t * (0.254829592 + $t * (-0.284496736 + $t * (1.421413741
        + $t * (-1.453152027 + $t * 1.061405429)))) * exp(-$x * $x);

    return $x >= 0 ? $y : 2 - $y;
}

/**
 * Public tracking endpoint. Aggregate counters only — no cookies read, no
 * IP/UA stored — so it is safe to expose unauthenticated.
 */
function register_rest_routes(): void
{
    register_rest_route('ntm/v1', '/chat-experiment/track', [
        'methods' => 'POST',
        'permission_callback' => '__return_true',
        'args' => [
            'provider' => [
                'required' => true,
                'type' => 'string',
                'enum' => PROVIDERS,
            ],
            'metric' => [
                'required' => true,
                'type' => 'string',
                'enum' => METRICS,
            ],
        ],
        'callback' => __NAMESPACE__ . '\\handle_track_request',
    ]);
}
add_action('rest_api_init', __NAMESPACE__ . '\\register_rest_routes');

function handle_track_request(\WP_REST_Request $request): \WP_REST_Response
{
    if (!is_running()) {
        return new \WP_REST_Response(['tracked' => false], 202);
    }

    increment_metric(
        (string) $request->get_param('provider'),
        (string) $request->get_param('metric')
    );

    return new \WP_REST_Response(['tracked' => true], 201);
}
