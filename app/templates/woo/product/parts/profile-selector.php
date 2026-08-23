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

$profile_groups = \Standard\ProfileGroups\partition_machine_profiles($profiles);
$profiles       = $profile_groups['profiles'];
$rib_rollers    = $profile_groups['rib_rollers'];

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
?>

<section id="machine-profiles" class="section" aria-labelledby="<?php echo esc_attr($title_id); ?>">
    <div class="container section-content" data-expandable-list>

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
        </div>

        <?php get_template_part('templates/parts/profile-expandable-list', null, [
            'profiles'     => $profiles,
            'carousel_id' => $carousel_id,
            'grid_id'     => $grid_id,
            'show_label'  => $show_all_label,
        ]); ?>

        <?php get_template_part('templates/parts/machine-rib-rollers', null, [
            'rib_rollers' => $rib_rollers,
        ]); ?>

    </div>
</section>
