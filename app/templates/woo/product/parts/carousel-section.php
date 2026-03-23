<?php
/**
 * Machine Product — Generic Carousel Section
 *
 * One template for any carousel section on a machine product page.
 * Queries either WooCommerce products or regular post types, maps
 * results to the standard card shape, and renders via carousel.php.
 *
 * Expected $args:
 *   'query_type'    => 'product' | 'post'
 *
 *   For query_type = 'product':
 *     'product_tag' => string   Tag slug to filter products
 *     'limit'       => int      Max products (default 12)
 *
 *   For query_type = 'post':
 *     'post_type'   => string   Post type slug
 *     'tag_slugs'   => string[] Tag slugs for tax_query
 *     'taxonomy'    => string   Taxonomy for filtering (default 'post_tag')
 *     'subtitle_tax'=> string   Taxonomy to pull subtitle from (default 'category')
 *
 *   Section display:
 *     'section_class' => string   Extra classes on <section> (default '')
 *     'carousel_id'   => string   Unique carousel ID
 *     'eyebrow'       => string   Section eyebrow text
 *     'title'         => string   Section heading
 *     'title_id'      => string   ID for aria-labelledby
 *     'prev_label'    => string   Aria label for prev button
 *     'next_label'    => string   Aria label for next button
 *
 * @package Standard
 * @var array $args
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$query_type    = $args['query_type'] ?? '';
$section_class = $args['section_class'] ?? '';
$carousel_id   = $args['carousel_id'] ?? 'carousel';
$eyebrow       = $args['eyebrow'] ?? '';
$title         = $args['title'] ?? '';
$title_id      = $args['title_id'] ?? 'carousel-title';
$prev_label    = $args['prev_label'] ?? __('Previous', 'standard');
$next_label    = $args['next_label'] ?? __('Next', 'standard');

// ── Query & map to cards ──────────────────────────────────────────────

$cards = [];

if ($query_type === 'product') {
    $product_tag = $args['product_tag'] ?? '';
    if (empty($product_tag)) {
        return;
    }

    $products = wc_get_products([
        'tag'    => [$product_tag],
        'limit'  => $args['limit'] ?? 12,
        'status' => 'publish',
    ]);

    if (empty($products)) {
        return;
    }

    foreach ($products as $product) {
        /** @var \WC_Product $product */
        $image_url = $product->get_image_id()
            ? wp_get_attachment_image_url($product->get_image_id(), 'product-card')
            : '';

        $cards[] = [
            'url'       => $product->get_permalink(),
            'image_url' => $image_url,
            'title'     => $product->get_name(),
            'subtitle'  => $product->get_price_html() ?: null,
        ];
    }
} elseif ($query_type === 'post') {
    $post_type    = $args['post_type'] ?? 'post';
    $tag_slugs    = $args['tag_slugs'] ?? [];
    $taxonomy     = $args['taxonomy'] ?? 'post_tag';
    $subtitle_tax = $args['subtitle_tax'] ?? 'category';

    if (empty($tag_slugs)) {
        return;
    }

    $posts = get_posts([
        'post_type'      => $post_type,
        'posts_per_page' => -1,
        'orderby'        => 'menu_order title',
        'order'          => 'ASC',
        'tax_query'      => [
            [
                'taxonomy' => $taxonomy,
                'field'    => 'slug',
                'terms'    => $tag_slugs,
            ],
        ],
    ]);

    if (empty($posts)) {
        return;
    }

    foreach ($posts as $post) {
        $terms    = get_the_terms($post->ID, $subtitle_tax);
        $subtitle = (!empty($terms) && !is_wp_error($terms)) ? $terms[0]->name : '';
        $image_url = has_post_thumbnail($post)
            ? get_the_post_thumbnail_url($post, 'product-card')
            : '';

        $cards[] = [
            'url'       => get_permalink($post),
            'image_url' => $image_url,
            'title'     => get_the_title($post),
            'subtitle'  => $subtitle ?: null,
        ];
    }
} else {
    return;
}

if (empty($cards)) {
    return;
}

// ── Render ─────────────────────────────────────────────────────────────
?>

<section class="section <?php echo esc_attr($section_class); ?>" aria-labelledby="<?php echo esc_attr($title_id); ?>">
    <div class="container section-content">
        <?php get_template_part('templates/woo/product/parts/carousel', null, [
            'carousel_id' => $carousel_id,
            'eyebrow'     => $eyebrow,
            'title'       => $title,
            'title_id'    => $title_id,
            'prev_label'  => $prev_label,
            'next_label'  => $next_label,
            'cards'       => $cards,
        ]); ?>
    </div>
</section>
