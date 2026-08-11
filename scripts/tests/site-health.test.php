<?php
/**
 * Public site-health regression tests.
 *
 * Run with: php scripts/tests/site-health.test.php
 */

declare(strict_types=1);

namespace {
    define('ABSPATH', '/tmp/ntm-wordpress/');

    /** @var array<string, array<int, array{callback: callable, priority: int}>> */
    $ntm_actions = [];
    /** @var array<string, array<int, array{callback: callable, priority: int}>> */
    $ntm_filters = [];
    /** @var array<int, array{hook: string, callback: callable, priority: int}> */
    $ntm_removed_actions = [];
    /** @var array<string, mixed> */
    $ntm_options = [
        'wpseo_titles' => ['company_name' => 'NewTech Machinery'],
    ];
    /** @var array<string, mixed> */
    $ntm_updated_options = [];
    $ntm_current_slug = '';

    function add_action(string $hook, callable $callback, int $priority = 10): void
    {
        global $ntm_actions;
        $ntm_actions[$hook][] = compact('callback', 'priority');
    }

    function add_filter(string $hook, callable $callback, int $priority = 10): void
    {
        global $ntm_filters;
        $ntm_filters[$hook][] = compact('callback', 'priority');
    }

    function remove_action(string $hook, callable $callback, int $priority = 10): bool
    {
        global $ntm_removed_actions;
        $ntm_removed_actions[] = compact('hook', 'callback', 'priority');
        return true;
    }

    function home_url(string $path = ''): string
    {
        return 'https://newtechmachinery.com' . $path;
    }

    function get_option(string $name, mixed $default = false): mixed
    {
        global $ntm_options;
        return $ntm_options[$name] ?? $default;
    }

    function update_option(string $name, mixed $value): bool
    {
        global $ntm_options, $ntm_updated_options;
        $ntm_options[$name]         = $value;
        $ntm_updated_options[$name] = $value;
        return true;
    }

    function delete_option(string $name): bool
    {
        global $ntm_options;
        unset($ntm_options[$name]);
        return true;
    }

    function is_singular(string $post_type = ''): bool
    {
        global $ntm_current_slug;
        return $post_type === 'product' && $ntm_current_slug !== '';
    }

    function is_page(string $slug = ''): bool
    {
        global $ntm_current_slug;
        return $ntm_current_slug === $slug;
    }

    function get_queried_object_id(): int
    {
        return 42;
    }

    function get_post_field(string $field, int $post_id): string
    {
        global $ntm_current_slug;
        return $field === 'post_name' && $post_id === 42 ? $ntm_current_slug : '';
    }

    function __(string $text, string $domain = ''): string
    {
        return $text;
    }

    function content_url(string $path = ''): string
    {
        return 'https://newtechmachinery.com/wp-content' . $path;
    }

    function esc_html(string $text): string
    {
        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }

    function esc_html_e(string $text, string $domain = ''): void
    {
        echo esc_html($text);
    }

    function esc_attr(string $text): string
    {
        return esc_html($text);
    }

    function esc_attr_e(string $text, string $domain = ''): void
    {
        echo esc_attr($text);
    }

    function esc_url(string $url): string
    {
        return $url;
    }

    function icon(string $name, array $attributes = []): void
    {
    }

    function get_field(string $key, int $post_id = 0): mixed
    {
        return $key === 'specs'
            ? '<table><tr><th>MACH II 5</th><th>MACH II 6</th><th>5/6 Combo</th></tr></table>'
            : null;
    }

    function wp_kses_post(string $html): string
    {
        return $html;
    }

    class WC_Product
    {
    }

    final class NtmProduct extends WC_Product
    {
        public function get_id(): int
        {
            return 42;
        }

        public function get_slug(): string
        {
            return 'mach-ii-5-gutter-machine';
        }

        public function get_image_id(): int
        {
            return 0;
        }

        public function get_name(): string
        {
            return 'MACH II™ 5" Gutter Machine';
        }
    }

    function ntm_assert(bool $condition, string $message): void
    {
        if (!$condition) {
            fwrite(STDERR, "FAIL: {$message}\n");
            exit(1);
        }
    }
}

namespace PixelYourSite {
    final class NtmPixelYourSite
    {
        public function controllSessionStart(): void
        {
        }
    }

    function PYS(): NtmPixelYourSite
    {
        static $instance;
        return $instance ??= new NtmPixelYourSite();
    }
}

namespace Standard\Url {
    function internal(string $path): string
    {
        return 'https://newtechmachinery.com' . $path;
    }
}

namespace Standard\MachineProductData {
    function get_machine_product_data(string $slug): ?array
    {
        $prices = [
            'mach-ii-5-gutter-machine'         => '9800',
            'mach-ii-6-gutter-machine'         => '10500',
            'mach-ii-5-6-combo-gutter-machine' => '12300',
            'bg7-box-gutter-machine'           => '71600',
        ];

        return isset($prices[$slug])
            ? ['schema' => ['low_price' => $prices[$slug]]]
            : null;
    }
}

namespace {
    require __DIR__ . '/../../app/inc/llms-txt.php';

    $llms = \Standard\LlmsTxt\build_curated_llms_txt('https://newtechmachinery.com');

    ntm_assert(str_contains($llms, '# New Tech Machinery (NTM)'), 'llms.txt should use the canonical company name.');
    ntm_assert(str_contains($llms, 'Starting at $9,800 USD'), 'llms.txt should publish the current MACH II 5 price.');
    ntm_assert(str_contains($llms, 'Starting at $10,500 USD'), 'llms.txt should publish the current MACH II 6 price.');
    ntm_assert(str_contains($llms, 'Starting at $12,300 USD'), 'llms.txt should publish the current MACH II Combo price.');
    ntm_assert(str_contains($llms, 'Starting at $71,600 USD'), 'llms.txt should include BG7 and its current price.');
    ntm_assert(
        str_contains($llms, 'SSQ II MultiPro roof panel machine') && str_contains($llms, 'Available for purchase through September 30, 2026.'),
        'llms.txt should identify the SSQ II final-sale deadline.'
    );
    ntm_assert(
        str_contains($llms, 'The [SSQ3 MultiPro]') && str_contains($llms, 'is its current successor.'),
        'llms.txt should identify the SSQ3 as the current successor.'
    );
    ntm_assert(
        str_contains($llms, '/machines/gutter-machines/mach-ii-6-gutter-machine/'),
        'llms.txt should link to the canonical MACH II 6 URL.'
    );
    ntm_assert(!str_contains($llms, 'gutter-machine-copy'), 'llms.txt should not expose the obsolete copied URL.');
    ntm_assert(
        str_contains($llms, 'https://newtechmachinery.com/machines/machii/'),
        'llms.txt should use the canonical MACH II family URL.'
    );
    ntm_assert(
        str_contains($llms, 'https://newtechmachinery.com/machines/leasing-financing/'),
        'llms.txt should use the canonical financing URL.'
    );
    ntm_assert(!str_contains($llms, '\\<'), 'llms.txt should not contain escaped raw HTML.');
    ntm_assert(!str_contains($llms, 'NewTech Machinery'), 'llms.txt should not use the collapsed company name.');

    require __DIR__ . '/../../app/inc/site-health.php';

    $ntm_current_slug = 'mach-ii-5-gutter-machine';
    ntm_assert(
        \Standard\SiteHealth\filter_wpseo_title('Old title') === 'MACH II 5" Seamless Gutter Machine | NTM',
        'The MACH II 5 page should publish the model-specific SEO title.'
    );
    ntm_assert(
        !str_contains(strtolower(\Standard\SiteHealth\filter_wpseo_description('Old combo description')), 'combo'),
        'The MACH II 5 meta description should not call it a combo machine.'
    );

    $ntm_current_slug = 'mach-ii-6-gutter-machine';
    ntm_assert(
        \Standard\SiteHealth\filter_wpseo_title('Old title') === 'MACH II 6" Seamless Gutter Machine | NTM',
        'The MACH II 6 page should publish the model-specific SEO title.'
    );
    ntm_assert(
        !str_contains(strtolower(\Standard\SiteHealth\filter_wpseo_description('Old combo description')), 'combo'),
        'The MACH II 6 meta description should not call it a combo machine.'
    );

    $ntm_current_slug = 'bg7-box-gutter-machine';
    ntm_assert(
        \Standard\SiteHealth\filter_wpseo_title('Old title') === 'BG7 7" Commercial Box Gutter Machine | NTM',
        'The BG7 page should publish the descriptive SEO title.'
    );

    ntm_assert(
        \Standard\SiteHealth\filter_yoast_company_name('NewTech Machinery') === 'New Tech Machinery',
        'Yoast organization schema should use the canonical company name.'
    );

    \Standard\SiteHealth\synchronize_yoast_company_name();
    ntm_assert(
        ($ntm_updated_options['wpseo_titles']['company_name'] ?? '') === 'New Tech Machinery',
        'The persisted Yoast organization name should be corrected.'
    );

    \Standard\SiteHealth\disable_pixelyoursite_php_session();
    $removed_session_callback = array_values(array_filter(
        $ntm_removed_actions,
        static fn(array $removed): bool => $removed['hook'] === 'wp'
            && $removed['priority'] === 10
            && is_array($removed['callback'])
            && $removed['callback'][1] === 'controllSessionStart'
    ));
    ntm_assert(count($removed_session_callback) === 1, 'PixelYourSite should not start PHP sessions for public visitors.');

    $machine = require __DIR__ . '/../../app/data/machines/mach-ii-5-gutter.php';
    $args    = [
        'product' => new NtmProduct(),
        'machine' => $machine,
    ];
    ob_start();
    require __DIR__ . '/../../app/templates/woo/product/parts/comparison.php';
    $comparison_html = (string) ob_get_clean();
    ntm_assert(
        str_contains($comparison_html, 'Compare the MACH II Family'),
        'The MACH II comparison should label itself as a family comparison.'
    );
    ntm_assert(
        str_contains($comparison_html, 'specifications above apply only to the MACH II 5'),
        'The MACH II 5 page should explicitly scope its preceding specifications to the current model.'
    );

    $args = ['product' => new NtmProduct()];
    ob_start();
    require __DIR__ . '/../../app/templates/woo/product/parts/default-specs.php';
    $default_specs_html = (string) ob_get_clean();
    ntm_assert(
        str_contains($default_specs_html, 'MACH II Family Specifications Comparison'),
        'The legacy MACH II specification table should be labeled as a family comparison.'
    );
    ntm_assert(
        str_contains($default_specs_html, 'current model is the MACH II 5'),
        'The legacy MACH II specification table should identify the current model.'
    );

    echo "Site health tests passed.\n";
}
