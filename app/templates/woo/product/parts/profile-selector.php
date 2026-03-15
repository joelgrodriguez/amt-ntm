<?php
/**
 * Machine Product — Profile Carousel
 *
 * Queries profiles by tag, then delegates to the shared carousel part.
 *
 * @package Standard
 * @var array{product: \WC_Product, machine: array} $args
 */

declare(strict_types=1);

$machine   = $args['machine'] ?? [];
$tag_slugs = $machine['profiles']['tag_slugs'] ?? [];

if (empty($tag_slugs)) {
    return;
}

$profiles = get_posts([
    'post_type'      => 'profile',
    'posts_per_page' => -1,
    'orderby'        => 'menu_order title',
    'order'          => 'ASC',
    'tax_query'      => [
        [
            'taxonomy' => 'post_tag',
            'field'    => 'slug',
            'terms'    => $tag_slugs,
        ],
    ],
]);

if (empty($profiles)) {
    return;
}

// Build standardized card data for the carousel
$cards = [];
foreach ($profiles as $profile) {
    $categories = get_the_terms($profile->ID, 'category');
    $cat_name   = (!empty($categories) && !is_wp_error($categories)) ? $categories[0]->name : '';
    $image_html = has_post_thumbnail($profile)
        ? get_the_post_thumbnail($profile, 'medium', ['class' => 'w-full h-full object-contain p-3 group-hover:scale-105 transition-transform'])
        : '';

    $cards[] = [
        'url'        => get_permalink($profile),
        'image_html' => $image_html,
        'title'      => get_the_title($profile),
        'subtitle'   => $cat_name,
    ];
}
?>

<section class="section bg-slate-50 pattern-dot-grid gradient-fade-bottom-sm" aria-labelledby="profiles-title">
    <div class="container section-content">
        <?php get_template_part('templates/woo/product/parts/carousel', null, [
            'carousel_id' => 'profiles-carousel',
            'eyebrow'     => __('Panel Profiles', 'standard'),
            'title'       => __('Your Panels, Your Way', 'standard'),
            'title_id'    => 'profiles-title',
            'prev_label'  => __('Previous profiles', 'standard'),
            'next_label'  => __('Next profiles', 'standard'),
            'cards'       => $cards,
        ]); ?>
    </div>
</section>
