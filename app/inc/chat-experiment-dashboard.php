<?php
/**
 * Admin dashboard for the self-hosted chat A/B experiment.
 *
 * Tools -> Chat A/B Test: start/stop the experiment, adjust the split, and
 * review per-goal conversion rates with two-proportion z-test significance.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard\ChatExperiment\Dashboard;

use function Standard\ChatExperiment\get_daily_rows;
use function Standard\ChatExperiment\get_state;
use function Standard\ChatExperiment\get_totals;
use function Standard\ChatExperiment\significance;
use function Standard\ChatExperiment\update_state;

if (!defined('ABSPATH')) {
    exit;
}

const PAGE_SLUG = 'ntm-chat-experiment';
const NONCE_ACTION = 'ntm_chat_experiment_controls';

const GOAL_LABELS = [
    'conversation_started' => 'Conversations started',
    'question_resolved' => 'Questions resolved',
    'sales_handoff' => 'Handoffs to sales',
    'lead_generated' => 'Leads generated',
];

function register_page(): void
{
    add_management_page(
        'Chat A/B Test',
        'Chat A/B Test',
        'manage_options',
        PAGE_SLUG,
        __NAMESPACE__ . '\\render_page'
    );
}
add_action('admin_menu', __NAMESPACE__ . '\\register_page');

function handle_actions(): void
{
    if (
        !isset($_POST['ntm_chat_experiment_action'])
        || !current_user_can('manage_options')
        || !check_admin_referer(NONCE_ACTION)
    ) {
        return;
    }

    $action = sanitize_key((string) $_POST['ntm_chat_experiment_action']);

    if ($action === 'start') {
        $split = isset($_POST['corbel_split']) ? (int) $_POST['corbel_split'] : 20;
        update_state([
            'status' => 'running',
            'corbel_split' => max(1, min(99, $split)),
            'started_at' => current_time('mysql'),
            'stopped_at' => '',
        ]);
    } elseif ($action === 'stop') {
        update_state([
            'status' => 'stopped',
            'stopped_at' => current_time('mysql'),
        ]);
    }
}
add_action('admin_init', __NAMESPACE__ . '\\handle_actions');

function render_page(): void
{
    $state = get_state();
    $running = $state['status'] === 'running';
    $totals = get_totals();
    $corbel_n = $totals['corbel']['exposure'];
    $hubspot_n = $totals['hubspot']['exposure'];
    ?>
    <div class="wrap">
        <h1>Chat A/B Test: Corbel vs HubSpot</h1>

        <h2>Status:
            <?php if ($running) : ?>
                <span style="color:#00a32a">Running</span>
                (since <?php echo esc_html($state['started_at']); ?>,
                Corbel <?php echo esc_html((string) $state['corbel_split']); ?>% /
                HubSpot <?php echo esc_html((string) (100 - $state['corbel_split'])); ?>%)
            <?php else : ?>
                <span style="color:#d63638">Stopped</span>
                <?php if ($state['stopped_at'] !== '') : ?>
                    (since <?php echo esc_html($state['stopped_at']); ?>)
                <?php endif; ?>
            <?php endif; ?>
        </h2>

        <form method="post" style="margin:1em 0 2em">
            <?php wp_nonce_field(NONCE_ACTION); ?>
            <?php if ($running) : ?>
                <input type="hidden" name="ntm_chat_experiment_action" value="stop">
                <?php submit_button('Stop experiment', 'delete', 'submit', false); ?>
            <?php else : ?>
                <input type="hidden" name="ntm_chat_experiment_action" value="start">
                <label>
                    Corbel traffic share (%):
                    <input type="number" name="corbel_split" min="1" max="99"
                        value="<?php echo esc_attr((string) $state['corbel_split']); ?>">
                </label>
                <?php submit_button('Start experiment', 'primary', 'submit', false); ?>
                <p class="description">
                    Starting resets the results window to today. Visitors keep
                    their assignment via a 90-day cookie.
                </p>
            <?php endif; ?>
        </form>

        <h2>Results<?php echo $state['started_at'] !== ''
            ? ' (since ' . esc_html(substr($state['started_at'], 0, 10)) . ')'
            : ''; ?></h2>
        <table class="widefat striped" style="max-width:1000px">
            <thead>
                <tr>
                    <th>Metric</th>
                    <th>Corbel (n=<?php echo esc_html(number_format_i18n($corbel_n)); ?>)</th>
                    <th>HubSpot (n=<?php echo esc_html(number_format_i18n($hubspot_n)); ?>)</th>
                    <th>Corbel rate</th>
                    <th>HubSpot rate</th>
                    <th>Lift</th>
                    <th>p-value</th>
                    <th>Verdict</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach (GOAL_LABELS as $metric => $label) :
                    $corbel_hits = $totals['corbel'][$metric];
                    $hubspot_hits = $totals['hubspot'][$metric];
                    $stats = significance($corbel_hits, $corbel_n, $hubspot_hits, $hubspot_n);
                    ?>
                    <tr>
                        <td><strong><?php echo esc_html($label); ?></strong></td>
                        <td><?php echo esc_html(number_format_i18n($corbel_hits)); ?></td>
                        <td><?php echo esc_html(number_format_i18n($hubspot_hits)); ?></td>
                        <?php if ($stats !== null) : ?>
                            <td><?php echo esc_html(number_format($stats['corbel_rate'] * 100, 2)); ?>%</td>
                            <td><?php echo esc_html(number_format($stats['hubspot_rate'] * 100, 2)); ?>%</td>
                            <td><?php echo esc_html(($stats['lift'] >= 0 ? '+' : '') . number_format($stats['lift'] * 100, 1)); ?>%</td>
                            <td><?php echo esc_html(number_format($stats['p'], 4)); ?></td>
                            <td>
                                <?php if ($corbel_hits + $hubspot_hits < 50) : ?>
                                    Too early
                                <?php elseif ($stats['significant']) : ?>
                                    <strong style="color:#00a32a">
                                        <?php echo $stats['corbel_rate'] > $stats['hubspot_rate']
                                            ? 'Corbel wins'
                                            : 'HubSpot wins'; ?>
                                    </strong>
                                <?php else : ?>
                                    No clear winner yet
                                <?php endif; ?>
                            </td>
                        <?php else : ?>
                            <td colspan="5">No data yet</td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <p class="description" style="max-width:800px">
            Rates are per exposed visitor. "Questions resolved" and "handoffs"
            depend on vendor events; where a vendor cannot report them in the
            browser, supplement these rows with their weekly reports.
            Significance uses a two-proportion z-test at 95% confidence. Wait
            for full weeks and at least ~100 conversions per side before
            calling a winner.
        </p>

        <h2>Daily breakdown (last 60 days)</h2>
        <?php $daily = get_daily_rows(); ?>
        <?php if ($daily === []) : ?>
            <p>No data recorded yet.</p>
        <?php else : ?>
            <table class="widefat striped" style="max-width:700px">
                <thead>
                    <tr><th>Date</th><th>Provider</th><th>Metric</th><th>Hits</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($daily as $row) : ?>
                        <tr>
                            <td><?php echo esc_html($row['stat_date']); ?></td>
                            <td><?php echo esc_html($row['provider']); ?></td>
                            <td><?php echo esc_html($row['metric']); ?></td>
                            <td><?php echo esc_html(number_format_i18n($row['hits'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <h2>Cross-checks</h2>
        <p class="description" style="max-width:800px">
            The same events flow to GA4 as <code>chat_experiment_exposure</code>
            and <code>chat_experiment_goal</code> (with <code>chat_provider</code>),
            and every session is tagged in Microsoft Clarity with
            <code>chat_variant</code> for filtered replay review.
        </p>
    </div>
    <?php
}
