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
    'posts_per_page'      => -1,
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

$profile_copy = $machine['profiles'] ?? [];
$eyebrow      = $profile_copy['eyebrow'] ?? __('Panel Profiles', 'standard');
$title        = $profile_copy['title'] ?? __('Your Panels, Your Way', 'standard');
$subtitle     = $profile_copy['subtitle'] ?? __('Every profile this machine forms, in the gauges and widths your jobs call for. Swipe through to see the full range.', 'standard');

$profile_count  = count($profiles);
$carousel_id    = 'profiles-carousel';
$grid_id        = 'profiles-grid';
$title_id       = 'profiles-title';
$show_all_label = sprintf(
    /* translators: %d is the number of profiles available for a machine. */
    _n('See All %d Profile', 'See All %d Profiles', $profile_count, 'standard'),
    $profile_count
);
$collapse_label = __('Collapse Profiles', 'standard');
?>

<section id="machine-profiles" class="section" aria-labelledby="<?php echo esc_attr($title_id); ?>">
    <div class="container section-content"
         data-profile-expand
         data-profile-expand-show-label="<?php echo esc_attr($show_all_label); ?>"
         data-profile-expand-collapse-label="<?php echo esc_attr($collapse_label); ?>">

        <div class="flex items-end justify-between gap-4 mb-10">
            <div class="section-header-left mb-0">
                <p class="section-eyebrow"><?php echo esc_html($eyebrow); ?></p>
                <div class="section-divider"></div>
                <h2 id="<?php echo esc_attr($title_id); ?>" class="section-title">
                    <?php echo esc_html($title); ?>
                </h2>
                <?php /* TODO(copy): confirm wording with team — Evita asked for an explainer under this headline. */ ?>
                <p class="section-subtitle max-w-xl">
                    <?php echo esc_html($subtitle); ?>
                </p>
            </div>
            <div data-profile-expand-compact class="flex gap-2 shrink-0">
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

        <?php get_template_part('templates/parts/profile-expandable-list', null, [
            'profiles'     => $profiles,
            'carousel_id' => $carousel_id,
            'grid_id'     => $grid_id,
            'show_label'  => $show_all_label,
        ]); ?>

    </div>
</section>
