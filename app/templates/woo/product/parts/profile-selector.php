<?php
/**
 * Machine Product — Profile Carousel
 *
 * Renders panel profile cards in a horizontal carousel for flagship machines.
 *
 * @package Standard
 * @var array{product: \WC_Product, machine: array} $args
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$machine   = $args['machine'] ?? [];
$tag_slugs = $machine['profiles']['tag_slugs'] ?? [];

if (empty($tag_slugs)) {
    return;
}

$profiles = get_posts([
    'post_type'           => 'profile',
    'post_status'         => 'publish',
    'posts_per_page'      => 24,
    'orderby'             => 'menu_order title',
    'order'               => 'ASC',
    'ignore_sticky_posts' => true,
    'no_found_rows'       => true,
    'tax_query'           => [
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

$carousel_id = 'profiles-carousel';
$title_id    = 'profiles-title';
?>

<section id="machine-profiles" class="section" aria-labelledby="<?php echo esc_attr($title_id); ?>">
    <div class="container section-content">

        <div class="flex items-end justify-between gap-4 mb-10">
            <div class="section-header-left mb-0">
                <p class="section-eyebrow"><?php esc_html_e('Panel Profiles', 'standard'); ?></p>
                <div class="section-divider"></div>
                <h2 id="<?php echo esc_attr($title_id); ?>" class="section-title">
                    <?php esc_html_e('Your Panels, Your Way', 'standard'); ?>
                </h2>
            </div>
            <div class="flex gap-2 shrink-0">
                <button type="button"
                        data-carousel-prev="<?php echo esc_attr($carousel_id); ?>"
                        class="carousel__nav"
                        aria-label="<?php esc_attr_e('Previous profiles', 'standard'); ?>">
                    <span class="text-blue-600">&larr;</span>
                </button>
                <button type="button"
                        data-carousel-next="<?php echo esc_attr($carousel_id); ?>"
                        class="carousel__nav"
                        aria-label="<?php esc_attr_e('Next profiles', 'standard'); ?>">
                    <span class="text-blue-600">&rarr;</span>
                </button>
            </div>
        </div>

        <ul id="<?php echo esc_attr($carousel_id); ?>" class="carousel__track list-none p-0 m-0">
            <?php foreach ($profiles as $profile) : ?>
                <li class="contents">
                    <?php get_template_part('templates/parts/card-profile', null, [
                        'profile' => $profile,
                        'context' => 'carousel',
                    ]); ?>
                </li>
            <?php endforeach; ?>
        </ul>

    </div>
</section>
