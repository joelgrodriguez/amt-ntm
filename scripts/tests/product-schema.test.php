<?php

declare(strict_types=1);

namespace {
    define('ABSPATH', __DIR__);

    /** @var array<string, array<int, array<int, callable|string|array>>> */
    $ntm_actions = [];
    /** @var array<string, array<int, array<int, callable|string|array>>> */
    $ntm_filters = [];
    /** @var array<int, array<string, string>> */
    $ntm_post_meta = [];
    /** @var WC_Product|null */
    $ntm_product = null;
    /** @var array<string, array> */
    $ntm_machine_data = [];

    function ntm_callback_id(callable|string|array $callback): string
    {
        if (is_string($callback)) {
            return $callback;
        }

        if (is_array($callback)) {
            $owner = is_object($callback[0]) ? spl_object_hash($callback[0]) : (string) $callback[0];

            return $owner . '::' . (string) $callback[1];
        }

        return spl_object_hash($callback);
    }

    function add_action(string $hook, callable|string|array $callback, int $priority = 10): bool
    {
        $GLOBALS['ntm_actions'][$hook][$priority][ntm_callback_id($callback)] = $callback;

        return true;
    }

    function do_action(string $hook): void
    {
        $priorities = $GLOBALS['ntm_actions'][$hook] ?? [];
        ksort($priorities);

        foreach ($priorities as $callbacks) {
            foreach ($callbacks as $callback) {
                $callback();
            }
        }
    }

    function add_filter(string $hook, callable|string|array $callback, int $priority = 10): bool
    {
        $GLOBALS['ntm_filters'][$hook][$priority][ntm_callback_id($callback)] = $callback;

        return true;
    }

    function apply_filters(string $hook, mixed $value, mixed ...$args): mixed
    {
        $priorities = $GLOBALS['ntm_filters'][$hook] ?? [];
        ksort($priorities);

        foreach ($priorities as $callbacks) {
            foreach ($callbacks as $callback) {
                $value = $callback($value, ...$args);
            }
        }

        return $value;
    }

    function is_singular(string $post_type = ''): bool
    {
        return $post_type === 'product';
    }

    function is_front_page(): bool
    {
        return false;
    }

    function get_queried_object_id(): int
    {
        return 42;
    }

    function get_post_meta(int $post_id, string $key, bool $single = false): string
    {
        return $GLOBALS['ntm_post_meta'][$post_id][$key] ?? '';
    }

    function delete_post_meta(int $post_id, string $key): bool
    {
        if (!isset($GLOBALS['ntm_post_meta'][$post_id][$key])) {
            return false;
        }

        unset($GLOBALS['ntm_post_meta'][$post_id][$key]);

        return true;
    }

    function wc_get_product(int $product_id): ?WC_Product
    {
        return $product_id === 42 ? $GLOBALS['ntm_product'] : null;
    }

    function get_permalink(int $product_id): string
    {
        return 'https://example.com/product/' . $product_id . '/';
    }

    function wp_get_attachment_url(int $attachment_id): string|false
    {
        return $attachment_id > 0 ? 'https://example.com/media/' . $attachment_id . '.jpg' : false;
    }

    function home_url(string $path = ''): string
    {
        return 'https://example.com' . $path;
    }

    function wp_strip_all_tags(string $text): string
    {
        return strip_tags($text);
    }

    function wp_json_encode(mixed $value, int $flags = 0): string|false
    {
        return json_encode($value, $flags);
    }

    function wp_get_post_terms(int $post_id, string $taxonomy, array $args = []): array
    {
        return $taxonomy === 'product_cat' && ($args['fields'] ?? null) === 'names'
            ? ['Accessories & Upgrades']
            : [];
    }

    function is_wp_error(mixed $value): bool
    {
        return false;
    }

    function get_woocommerce_currency(): string
    {
        return 'USD';
    }

    function __(string $text, string $domain = 'default'): string
    {
        return $text;
    }

    function esc_html_e(string $text, string $domain = 'default'): void
    {
        echo htmlspecialchars($text, ENT_QUOTES);
    }

    function esc_html(string $text): string
    {
        return htmlspecialchars($text, ENT_QUOTES);
    }

    function wp_kses_post(string $text): string
    {
        return $text;
    }

    function icon(string $name, array $args = []): void
    {
        echo '<span data-icon="' . htmlspecialchars($name, ENT_QUOTES) . '"></span>';
    }

    final class WC_Product
    {
        public function __construct(
            private int $id,
            private string $name,
            private string $slug,
            private string $shortDescription,
            private int $imageId = 0,
            private string $price = '',
            private string $sku = '',
            private string $stockStatus = 'instock',
            private string $description = ''
        ) {
        }

        public function get_id(): int
        {
            return $this->id;
        }

        public function get_name(): string
        {
            return $this->name;
        }

        public function get_slug(): string
        {
            return $this->slug;
        }

        public function get_short_description(): string
        {
            return $this->shortDescription;
        }

        public function get_description(): string
        {
            return $this->description;
        }

        public function get_image_id(): int
        {
            return $this->imageId;
        }

        public function get_price(): string
        {
            return $this->price;
        }

        public function get_sku(): string
        {
            return $this->sku;
        }

        public function get_stock_status(): string
        {
            return $this->stockStatus;
        }

    }

    final class BSF_AIOSRS_Pro_Markup
    {
        private static ?self $instance = null;

        public static function get_instance(): self
        {
            return self::$instance ??= new self();
        }

        public function schema_markup(): void
        {
            if (apply_filters('wp_schema_pro_schema_enabled', true, 42, 'product')) {
                echo '<script type="application/ld+json">[]</script>';
            }

            if (apply_filters('wp_schema_pro_schema_enabled', true, 42, 'video-object')) {
                echo '<script type="application/ld+json">'
                    . '{"@context":"https://schema.org","@type":"VideoObject","name":"Machine overview"}'
                    . '</script>';
            }
        }
    }

    function ntm_assert_same(mixed $expected, mixed $actual, string $message): void
    {
        if ($expected !== $actual) {
            throw new \RuntimeException(
                $message . ' Expected ' . var_export($expected, true) . ', got ' . var_export($actual, true) . '.'
            );
        }
    }

    /**
     * Return only root Product nodes. Nested Brand and PropertyValue nodes do
     * not count as competing Product ownership.
     *
     * @return array<int, array>
     */
    function ntm_schema_nodes(string $html, string $type): array
    {
        preg_match_all(
            '~<script\b[^>]*type=["\']application/ld\+json["\'][^>]*>(.*?)</script>~is',
            $html,
            $matches
        );

        $nodes = [];
        foreach ($matches[1] ?? [] as $json) {
            $decoded = json_decode(trim($json), true);
            if (!is_array($decoded)) {
                continue;
            }

            $roots = isset($decoded['@graph']) && is_array($decoded['@graph'])
                ? $decoded['@graph']
                : [$decoded];

            foreach ($roots as $root) {
                if (is_array($root) && ($root['@type'] ?? null) === $type) {
                    $nodes[] = $root;
                }
            }
        }

        return $nodes;
    }

    /**
     * @return array<int, array>
     */
    function ntm_product_nodes(string $html): array
    {
        return ntm_schema_nodes($html, 'Product');
    }
}

namespace Standard\MachineProductData {
    function get_machine_product_data(string $slug): ?array
    {
        return $GLOBALS['ntm_machine_data'][$slug] ?? null;
    }
}

namespace {
    require __DIR__ . '/../../app/inc/machine-schema.php';
    require __DIR__ . '/../../app/inc/product-schema.php';

    $GLOBALS['ntm_product'] = new WC_Product(
        42,
        'MACH II 5" Gutter Machine',
        'mach-ii-5-gutter-machine',
        'Portable 5" K-style seamless gutter machine.',
        900
    );
    $GLOBALS['ntm_machine_data']['mach-ii-5-gutter-machine'] = [
        'hero' => [
            'subtitle' => 'Portable 5" K-style seamless gutter machine.',
        ],
        'stats' => [],
        'specs' => [],
        'schema' => [
            'low_price' => '9800',
            'availability' => 'InStock',
            'brand' => 'New Tech Machinery',
            'category' => 'Seamless Gutter Machines',
        ],
    ];

    $schema_pro = BSF_AIOSRS_Pro_Markup::get_instance();
    add_action('wp_head', [$schema_pro, 'schema_markup']);
    $GLOBALS['ntm_post_meta'][42][\Standard\ProductSchema\SCHEMA_PRO_CACHE_KEY] =
        '<script type="application/ld+json">[]</script>';

    \Standard\ProductSchema\prepare_schema_pro_product_markup();

    ntm_assert_same(
        false,
        \Standard\ProductSchema\schema_pro_cache_requires_refresh(
            '<script type="application/ld+json">'
            . '{"@context":"https://schema.org","@type":"Review","itemReviewed":{"@type":"Product"}}'
            . '</script>'
        ),
        'A nested Product reference in unrelated schema should not invalidate the cache.'
    );

    ob_start();
    do_action('wp_head');
    do_action('wp_footer');
    $rendered = (string) ob_get_clean();

    ntm_assert_same(
        false,
        str_contains($rendered, '>[]</script>'),
        'A rendered product page should not contain Schema Pro empty-array markup.'
    );
    ntm_assert_same(
        1,
        count(ntm_schema_nodes($rendered, 'VideoObject')),
        'Disabling Schema Pro Product output should preserve unrelated per-page schema.'
    );

    $products = ntm_product_nodes($rendered);
    ntm_assert_same(1, count($products), 'A rendered machine page should contain exactly one root Product node.');
    ntm_assert_same(
        '9800',
        $products[0]['offers']['lowPrice'] ?? null,
        'Machine Product schema should use the curated starting price.'
    );
    ntm_assert_same(
        'https://example.com/product/42/#product',
        $products[0]['@id'] ?? null,
        'Machine Product schema should expose the canonical product entity identifier.'
    );
    ntm_assert_same(
        'MACH II 5" Gutter Machine',
        $products[0]['model'] ?? null,
        'Machine Product schema should expose the visible machine name when no SKU exists.'
    );
    ntm_assert_same(
        false,
        isset($products[0]['offers']['availability']),
        'Machine schema should not publish an unverified availability claim.'
    );

    $GLOBALS['ntm_product'] = new WC_Product(
        42,
        'MACH II Gutter Machine Cart',
        'mach-ii-gutter-machine-cart',
        'Portable cart for MACH II gutter machines.',
        901,
        '2400',
        'MG-CART'
    );

    ob_start();
    do_action('wp_footer');
    $rendered = (string) ob_get_clean();

    $products = ntm_product_nodes($rendered);
    ntm_assert_same(1, count($products), 'A priced accessory page should contain exactly one root Product node.');
    ntm_assert_same(
        '2400',
        $products[0]['offers']['price'] ?? null,
        'A priced accessory Product should expose its active WooCommerce price.'
    );

    $GLOBALS['ntm_product'] = new WC_Product(
        42,
        'MACH II Machine Cover',
        'machine-cover-cvr-gm5-gm6-or-gm56',
        'Protective cover for MACH II gutter machines.',
        902,
        '',
        'CVR-GM5'
    );

    ob_start();
    do_action('wp_footer');
    $rendered = (string) ob_get_clean();

    $products = ntm_product_nodes($rendered);
    ntm_assert_same(1, count($products), 'An unpriced accessory page should still contain one Product entity.');
    ntm_assert_same(
        false,
        isset($products[0]['offers']),
        'An unpriced accessory must not publish a made-up Offer.'
    );
    ntm_assert_same(
        'CVR-GM5',
        $products[0]['sku'] ?? null,
        'An unpriced accessory should retain its real WooCommerce SKU.'
    );

    ntm_assert_same(
        ['review', 'breadcrumblist', 'order'],
        apply_filters('woocommerce_structured_data_type_for_page', [
            'product',
            'review',
            'breadcrumblist',
            'order',
        ]),
        'WooCommerce should suppress only its competing Product node.'
    );

    $faq_machine = [
        'faq' => [
            [
                'question' => 'Which profiles does this machine form?',
                'answer' => 'It forms three K-style gutter profiles.',
            ],
        ],
    ];
    $args = ['machine' => $faq_machine];

    ob_start();
    require __DIR__ . '/../../app/templates/woo/product/parts/faq.php';
    $rendered_faq = (string) ob_get_clean();

    ntm_assert_same(
        true,
        str_contains($rendered_faq, 'Which profiles does this machine form?'),
        'Machine FAQ schema should be backed by a visible question.'
    );
    ntm_assert_same(
        1,
        preg_match('~<h3[^>]*>\\s*Which profiles does this machine form\\?\\s*</h3>~', $rendered_faq),
        'Each visible machine FAQ question should be a heading.'
    );
    ntm_assert_same(
        1,
        count(ntm_schema_nodes($rendered_faq, 'FAQPage')),
        'The visible machine FAQ component should emit exactly one FAQPage node.'
    );

    echo "Product schema tests passed.\n";
}
