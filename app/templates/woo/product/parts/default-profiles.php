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

<?php
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
            <div class="flex gap-2 shrink-0">
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

        <div id="<?php echo esc_attr($carousel_id); ?>" class="carousel__track">
            <?php foreach ($cards as $card) : ?>
                <a href="<?php echo esc_url($card['url']); ?>" class="carousel__card group">
                    <div class="carousel__card-image">
                        <?php if (!empty($card['image_id'])) : ?>
                            <?php echo wp_get_attachment_image((int) $card['image_id'], 'product-card', false, [
                                'class' => 'w-full h-full object-contain p-3 transition-transform',
                                'alt'   => $card['title'],
                            ]); ?>
                        <?php else : ?>
                            <span class="text-blue-400 text-sm font-mono"><?php echo esc_html($card['title']); ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="grid gap-1">
                        <h3 class="text-sm font-medium text-blue-900 group-hover:text-blue-500 transition-colors leading-tight">
                            <?php echo esc_html($card['title']); ?>
                        </h3>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>

    </div>
</section>
