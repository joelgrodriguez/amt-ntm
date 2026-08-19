<?php
/**
 * Behavioral checks for the front-end third-party loader configuration.
 */

declare(strict_types=1);

namespace {
    define('ABSPATH', __DIR__);

    $ntm_is_page = false;
    $ntm_page_uri = '';

    function add_action(string $hook, callable|string $callback, int $priority = 10): bool
    {
        return true;
    }

    function add_filter(string $hook, callable|string $callback, int $priority = 10, int $accepted_args = 1): bool
    {
        return true;
    }

    function is_admin(): bool
    {
        return false;
    }

    function is_page(array|string|int $page = ''): bool
    {
        return $GLOBALS['ntm_is_page'];
    }

    function get_queried_object_id(): int
    {
        return $GLOBALS['ntm_is_page'] ? 42 : 0;
    }

    function get_page_uri(int $post_id): string
    {
        return $post_id === 42 ? $GLOBALS['ntm_page_uri'] : '';
    }

    function get_option(string $name, mixed $default = false): mixed
    {
        return $default;
    }

    function sanitize_key(string $key): string
    {
        return strtolower(preg_replace('/[^a-z0-9_\-]/', '', $key) ?? '');
    }

    function wp_json_encode(mixed $value, int $flags = 0): string|false
    {
        return json_encode($value, $flags);
    }

    /** @return array{hubspotPortalId: string, clarityProjectId: string} */
    function ntm_get_third_party_config(bool $is_page, string $page_uri): array
    {
        $GLOBALS['ntm_is_page'] = $is_page;
        $GLOBALS['ntm_page_uri'] = $page_uri;

        ob_start();
        \Standard\Performance\print_third_party_config();
        $output = (string) ob_get_clean();

        if (!preg_match('/window\.ntmThirdPartyConfig = (\{.*\});<\/script>/', $output, $matches)) {
            throw new RuntimeException('Third-party configuration script was not rendered.');
        }

        $config = json_decode($matches[1], true, 512, JSON_THROW_ON_ERROR);

        return $config;
    }

    function ntm_assert_same(mixed $expected, mixed $actual, string $message): void
    {
        if ($expected !== $actual) {
            throw new RuntimeException(
                $message . ' Expected ' . var_export($expected, true) . ', got ' . var_export($actual, true) . '.'
            );
        }
    }
}

namespace {
    require __DIR__ . '/../../app/inc/page-templates.php';
    require __DIR__ . '/../../app/inc/performance.php';

    ntm_assert_same(
        '',
        ntm_get_third_party_config(true, 'configurator')['hubspotPortalId'],
        'The configurator root must not load HubSpot chat.'
    );
    ntm_assert_same(
        '',
        ntm_get_third_party_config(true, 'configurator/roof-panel-machines')['hubspotPortalId'],
        'Configurator child pages must not load HubSpot chat.'
    );
    ntm_assert_same(
        '',
        ntm_get_third_party_config(true, 'machines')['hubspotPortalId'],
        'Ordinary pages must keep HubSpot chat dormant.'
    );
    ntm_assert_same(
        '',
        ntm_get_third_party_config(false, '')['hubspotPortalId'],
        'Non-page requests must keep HubSpot chat dormant.'
    );

    echo "Third-party configuration tests passed.\n";
}
