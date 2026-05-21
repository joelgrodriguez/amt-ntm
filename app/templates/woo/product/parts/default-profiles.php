<?php
/**
 * Default Machine — Available Profiles
 *
 * Reads the 'profiles' ACF field (relationship/post-object array) and
 * renders an image-led grid via product-card-link. Silent return when
 * no profiles are attached.
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

$cards = [];
foreach ($profiles as $profile) {
    $profile_id = is_object($profile) ? ($profile->ID ?? 0) : (int) $profile;
    if (!$profile_id) {
        continue;
    }

    $cards[] = [
        'url'      => get_permalink($profile_id),
        'image_id' => (int) get_post_thumbnail_id($profile_id),
        'title'    => get_the_title($profile_id),
        'subtitle' => null,
    ];
}

if (empty($cards)) {
    return;
}
?>

<section id="machine-profiles" class="section bg-blue-50" aria-labelledby="default-profiles-title">
    <div class="container section-content">

        <div class="section-header-left mb-12">
            <p class="section-eyebrow"><?php esc_html_e('Available Profiles', 'standard'); ?></p>
            <div class="section-divider"></div>
            <h2 id="default-profiles-title" class="section-title"><?php esc_html_e('What it forms', 'standard'); ?></h2>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            <?php foreach ($cards as $card) : ?>
                <?php get_template_part('templates/woo/product/parts/product-card-link', null, compact('card')); ?>
            <?php endforeach; ?>
        </div>

    </div>
</section>
