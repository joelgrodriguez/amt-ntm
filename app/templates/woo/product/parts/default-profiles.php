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

$carousel_id = 'default-profiles-' . $product->get_id();
$title_id    = 'default-profiles-title';
?>

<section id="machine-profiles" class="section bg-blue-50" aria-labelledby="<?php echo esc_attr($title_id); ?>">
    <div class="container section-content">

        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-10">
            <div>
                <p class="section-eyebrow mb-2"><?php esc_html_e('Available Profiles', 'standard'); ?></p>
                <h2 id="<?php echo esc_attr($title_id); ?>" class="section-title">
                    <?php esc_html_e('What it forms', 'standard'); ?>
                </h2>
            </div>
            <div class="flex gap-2 shrink-0 self-end md:self-auto">
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

        <ul id="<?php echo esc_attr($carousel_id); ?>" class="carousel__track list-none p-0 m-0">
            <?php foreach ($profile_ids as $profile_id) : ?>
                <li class="contents">
                    <?php get_template_part('templates/parts/card-profile', null, [
                        'profile' => $profile_id,
                        'context' => 'carousel',
                    ]); ?>
                </li>
            <?php endforeach; ?>
        </ul>

    </div>
</section>
