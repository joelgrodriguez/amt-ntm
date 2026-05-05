<?php
/**
 * Mega Menu — Learning Center post card.
 *
 * Args:
 *   post (WP_Post): the post object
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$post = $args['post'] ?? null;
if (!$post instanceof \WP_Post) {
    return;
}

$type_label = \Standard\LearningCenter\get_type_label($post->post_type);
$type_icon  = \Standard\LearningCenter\get_type_icon($post->post_type);
$url        = get_permalink($post);
$thumb      = get_the_post_thumbnail_url($post, 'card-thumbnail');
$title      = get_the_title($post);
$date       = get_the_date('M j, Y', $post);
?>

<a href="<?php echo esc_url($url); ?>" class="mega-post-card flex gap-3 no-underline group">
    <?php if ($thumb) : ?>
        <div class="flex-none w-24 h-16 bg-blue-50 overflow-hidden">
            <img
                src="<?php echo esc_url($thumb); ?>"
                alt=""
                class="w-full h-full object-cover transition-transform duration-200 group-hover:scale-105"
                loading="lazy"
                decoding="async"
            >
        </div>
    <?php else : ?>
        <div class="flex-none w-24 h-16 bg-blue-100 flex items-center justify-center text-blue-400">
            <?php icon($type_icon, ['class' => 'w-5 h-5']); ?>
        </div>
    <?php endif; ?>
    <div class="flex flex-col justify-center gap-1 min-w-0">
        <span class="inline-flex items-center gap-1 font-mono text-[11px] font-medium uppercase tracking-widest text-blue-400">
            <?php icon($type_icon, ['class' => 'w-3 h-3']); ?>
            <?php echo esc_html($type_label); ?>
        </span>
        <span class="text-sm font-medium text-blue-700 leading-snug line-clamp-2 group-hover:text-blue-500 transition-colors">
            <?php echo esc_html($title); ?>
        </span>
        <span class="text-xs text-blue-400"><?php echo esc_html($date); ?></span>
    </div>
</a>
