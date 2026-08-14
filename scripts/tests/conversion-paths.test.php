<?php
/**
 * Regression checks for public sales and configurator paths.
 *
 * Run with: php scripts/tests/conversion-paths.test.php
 */

declare(strict_types=1);

namespace {
    define('ABSPATH', __DIR__);

    /** @var array<string, callable|string> */
    $ntm_actions = [];

    function add_action(string $hook, callable|string $callback, int $priority = 10): bool
    {
        $GLOBALS['ntm_actions'][$hook] = $callback;
        return true;
    }

    function __(string $text, string $domain = 'default'): string
    {
        return $text;
    }

    function wp_http_validate_url(string $url): bool
    {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }

    function home_url(string $path = ''): string
    {
        return 'https://newtechmachinery.com' . $path;
    }

    function content_url(string $path = ''): string
    {
        return 'https://newtechmachinery.com/wp-content' . $path;
    }

    function wc_get_products(array $args = []): array
    {
        return array_map(
            static fn(string $slug): NtmTestProduct => new NtmTestProduct($slug),
            array_values(\Standard\MachineProductData\get_canonical_product_slugs())
        );
    }

    function wp_get_attachment_url(int $attachment_id): string
    {
        return '';
    }

    function sanitize_key(string $key): string
    {
        return strtolower((string) preg_replace('/[^a-z0-9_\-]/', '', $key));
    }

    function wp_parse_url(string $url, int $component = -1): mixed
    {
        return parse_url($url, $component);
    }

    function untrailingslashit(string $value): string
    {
        return rtrim($value, '/\\');
    }

    function ntm_assert(bool $condition, string $message): void
    {
        if (!$condition) {
            throw new \RuntimeException($message);
        }
    }

    final class NtmTestProduct
    {
        public function __construct(private readonly string $slug)
        {
        }

        public function get_slug(): string
        {
            return $this->slug;
        }

        public function get_permalink(): string
        {
            return home_url('/machines/test/' . $this->slug . '/');
        }

        public function get_price(): string
        {
            return '';
        }

        public function get_image_id(): int
        {
            return 0;
        }
    }
}

namespace Standard\Url {
    function internal(string $path): string
    {
        return \home_url($path);
    }

    function canonical(string $url): string
    {
        return $url;
    }
}

namespace Standard\Woo\Cache {
    function get_products(array $args = []): array
    {
        return \wc_get_products($args);
    }
}

namespace {
    require __DIR__ . '/../../app/inc/machine-product-data.php';
    require __DIR__ . '/../../app/inc/machines-data.php';
    require __DIR__ . '/../../app/inc/legacy-build-finance.php';

    $ssr_candidates = \Standard\MachineProductData\get_machine_product_slug_candidates('ssr-multipro-jr');
    ntm_assert(
        ($ssr_candidates[1] ?? '') === 'ssr-multipro-jr-roof-panel-machine',
        'SSR must prefer its published WooCommerce slug over older aliases.'
    );
    ntm_assert(
        \Standard\MachinesData\get_product_url('ssr-multipro-jr')
            === 'https://newtechmachinery.com/machines/test/ssr-multipro-jr-roof-panel-machine/',
        'SSR must resolve to its canonical published product permalink.'
    );

    foreach (\Standard\MachinesData\get_machine_categories() as $category) {
        foreach ($category['machines'] as $machine) {
            $url = (string) ($machine['url'] ?? '');
            ntm_assert(
                $url !== '' && $url !== '#',
                (string) ($machine['slug'] ?? 'Unknown machine') . ' must have an active product URL.'
            );
        }
    }

    $all_machines    = \Standard\MachinesData\get_machine_categories(true);
    $active_machines = \Standard\MachinesData\get_machine_categories();
    ntm_assert(
        count($all_machines['roof-wall']['machines']) > count($active_machines['roof-wall']['machines']),
        'Dormant machines must stay excluded from the active lineup.'
    );

    ntm_assert(
        \Standard\MachinesData\get_configurator_url('mach-ii-combo-gutter')
            === 'https://newtechmachinery.com/configurator/machii/',
        'The featured MACH II CTA must resolve to the canonical configurator.'
    );
    ntm_assert(
        \Standard\MachinesData\get_configurator_url('mach-ii-5-6-combo-gutter-machine')
            === 'https://newtechmachinery.com/configurator/machii/',
        'A known WooCommerce alias must resolve to the canonical configurator.'
    );
    ntm_assert(
        \Standard\LegacyBuildFinance\get_redirect_path('ssr-multipro-jr-roof-panel-machine')
            === '/configurator/ssr/',
        'Known legacy machine requests must redirect to their machine configurator.'
    );
    ntm_assert(
        \Standard\LegacyBuildFinance\get_redirect_path('unknown-machine')
            === '/configurator/',
        'Unknown legacy machine requests must fall back to the configurator index.'
    );

    $active_theme_files = [
        __DIR__ . '/../../app/inc/machines-data.php',
        __DIR__ . '/../../app/templates/pages/machines/lineup-flagship.php',
        __DIR__ . '/../../app/templates/pages/machii/variant-matrix.php',
        __DIR__ . '/../../app/templates/parts/front-page/flagships.php',
    ];
    foreach ($active_theme_files as $file) {
        ntm_assert(
            !str_contains((string) file_get_contents($file), '/build-finance/'),
            basename($file) . ' must not emit the retired build-finance route.'
        );
    }

    echo "Conversion path tests passed.\n";
}
