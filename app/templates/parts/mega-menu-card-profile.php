<?php
/**
 * Mega Menu — Profile card.
 *
 * Args:
 *   profile (WP_Post): the profile post object
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$profile = $args['profile'] ?? null;
if (!$profile instanceof \WP_Post) {
    return;
}

$url   = get_permalink($profile);
$thumb = get_the_post_thumbnail_url($profile, 'product-card');
$title = get_the_title($profile);
?>

<a href="<?php echo esc_url($url); ?>" class="mega-profile-card flex flex-col no-underline group">
    <div class="w-full aspect-square bg-blue-50 overflow-hidden mb-2">
        <?php if ($thumb) : ?>
            <img
                src="<?php echo esc_url($thumb); ?>"
                alt="<?php echo esc_attr($title); ?>"
                class="w-full h-full object-cover transition-transform duration-200 group-hover:scale-105"
                loading="lazy"
                decoding="async"
            >
        <?php else : ?>
            <div class="w-full h-full flex items-center justify-center text-blue-200">
                <?php icon('user', ['class' => 'w-8 h-8']); ?>
            </div>
        <?php endif; ?>
    </div>
    <span class="text-sm font-medium text-blue-700 leading-snug group-hover:text-blue-500 transition-colors">
        <?php echo esc_html($title); ?>
    </span>
</a>
