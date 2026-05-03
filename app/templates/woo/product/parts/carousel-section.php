<?php
/**
 * Machine Product — Generic Carousel Section
 *
 * One template for any carousel section on a machine product page.
 * Card queries are built in inc/woo/carousel.php; this file only
 * handles section chrome and rendering.
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

$section_class = $args['section_class'] ?? '';
$carousel_id   = $args['carousel_id'] ?? 'carousel';
$eyebrow       = $args['eyebrow'] ?? '';
$title         = $args['title'] ?? '';
$title_id      = $args['title_id'] ?? 'carousel-title';
$prev_label    = $args['prev_label'] ?? __('Previous', 'standard');
$next_label    = $args['next_label'] ?? __('Next', 'standard');

$cards = \Standard\Woo\Carousel\get_cards($args ?? []);

if (empty($cards)) {
    return;
}
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
