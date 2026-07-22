<?php
/**
 * Default Machine — Available Profiles
 *
 * Reads the 'profiles' ACF field (relationship/post-object array) and
 * renders the canonical card-profile component inside a carousel. Silent
 * return when no profiles are attached.
 *
 * @package Standard
 * @var array{product: \WC_Product} $args
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$product = $args['product'] ?? null;
if (!$product instanceof \WC_Product) {
    return;
}

$profiles = function_exists('get_field') ? get_field('profiles', $product->get_id()) : null;

if (empty($profiles) || !is_array($profiles)) {
    return;
}

$profile_ids = [];
foreach ($profiles as $profile) {
    $profile_id = is_object($profile) ? ($profile->ID ?? 0) : (int) $profile;
    if ($profile_id) {
        $profile_ids[] = $profile_id;
    }
}

if (empty($profile_ids)) {
    return;
}

$profile_groups = \Standard\ProfileGroups\partition_machine_profiles($profile_ids);
$profile_ids    = $profile_groups['profiles'];
$rib_rollers    = $profile_groups['rib_rollers'];

$profile_count  = count($profile_ids);
$carousel_id    = 'default-profiles-' . $product->get_id();
$grid_id        = $carousel_id . '-grid';
$title_id       = 'default-profiles-title';
$show_all_label = sprintf(
    /* translators: %d is the number of profiles available for a machine. */
    _n('See All %d Profile', 'See All %d Profiles', $profile_count, 'standard'),
    $profile_count
);
$collapse_label = __('Collapse Profiles', 'standard');
?>

<section id="machine-profiles" class="section bg-blue-50" aria-labelledby="<?php echo esc_attr($title_id); ?>">
    <div class="container section-content"
         data-profile-expand
         data-profile-expand-show-label="<?php echo esc_attr($show_all_label); ?>"
         data-profile-expand-collapse-label="<?php echo esc_attr($collapse_label); ?>">

        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-10">
            <div>
                <p class="section-eyebrow mb-2"><?php esc_html_e('Available Profiles', 'standard'); ?></p>
                <h2 id="<?php echo esc_attr($title_id); ?>" class="section-title">
                    <?php esc_html_e('What it forms', 'standard'); ?>
                </h2>
            </div>
            <div data-profile-expand-compact class="flex gap-2 shrink-0 self-end md:self-auto">
                <button type="button"
                        data-carousel-prev="<?php echo esc_attr($carousel_id); ?>"
                        class="carousel__nav"
                        aria-label="<?php esc_attr_e('Previous profiles', 'standard'); ?>">
                    <?php icon('arrow-left', ['class' => 'w-4 h-4 text-blue-700']); ?>
                </button>
                <button type="button"
                        data-carousel-next="<?php echo esc_attr($carousel_id); ?>"
                        class="carousel__nav"
                        aria-label="<?php esc_attr_e('Next profiles', 'standard'); ?>">
                    <?php icon('arrow-right', ['class' => 'w-4 h-4 text-blue-700']); ?>
                </button>
            </div>
        </div>

        <?php get_template_part('templates/parts/profile-expandable-list', null, [
            'profiles'     => $profile_ids,
            'carousel_id' => $carousel_id,
            'grid_id'     => $grid_id,
            'show_label'  => $show_all_label,
        ]); ?>

        <?php get_template_part('templates/parts/machine-rib-rollers', null, [
            'rib_rollers' => $rib_rollers,
        ]); ?>

    </div>
</section>
