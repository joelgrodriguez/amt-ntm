<?php
/**
 * Profile Card
 *
 * Canonical profile card. Used everywhere a profile renders: /profiles
 * landing, mega-menu Profiles tab, and the Woo machine profile carousels
 * (Available Profiles + Panel Profiles). Image fills a 16:9 well edge-to-
 * edge — profile renders are already 16:9 so no letterboxing needed.
 *
 * Args:
 *   profile  (WP_Post|int): profile post or its ID
 *   context  (string): 'grid' (default), 'mega', or 'carousel'
 *     - grid:     shows machine-tag subtitle row
 *     - mega:     title only, denser
 *     - carousel: title only, sized for .carousel__track (snap + responsive widths)
 *
 * @package Standard
 * @var array $args
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$profile_arg = $args['profile'] ?? null;
$context     = $args['context'] ?? 'grid';

if ($profile_arg instanceof \WP_Post) {
    $profile = $profile_arg;
} elseif (is_int($profile_arg) && $profile_arg > 0) {
    $profile = get_post($profile_arg);
} else {
    return;
}

if (!$profile instanceof \WP_Post) {
    return;
}

$url   = get_permalink($profile);
$title = get_the_title($profile);
$thumb = get_the_post_thumbnail_url($profile, 'product-card');
$subtitle = '';
if ($context === 'grid') {
    $tags = get_the_terms($profile->ID, 'post_tag');
    if (is_array($tags) && !empty($tags)) {
        $names = array_map(static fn(\WP_Term $t): string => $t->name, array_slice($tags, 0, 2));
        $extra = count($tags) - count($names);
        $subtitle = implode(', ', $names);
        if ($extra > 0) {
            $subtitle .= sprintf(' +%d', $extra);
        }
    }
}
$root_classes = 'profile-card group grid grid-rows-[auto_1fr] no-underline bg-white border border-blue-200 hover:border-blue-500 transition-colors duration-200';
if ($context === 'carousel') {
    $root_classes .= ' carousel__card';
}
?>

<a href="<?php echo esc_url($url); ?>"
   class="<?php echo esc_attr($root_classes); ?>">

    <div class="profile-card__image aspect-[16/9] overflow-hidden border-b border-blue-200 group-hover:border-blue-500 transition-colors duration-200">
        <?php if ($thumb) : ?>
            <img src="<?php echo esc_url($thumb); ?>"
                 alt="<?php echo esc_attr($title); ?>"
                 class="w-full h-full block object-cover transition-transform duration-200 group-hover:scale-105"
                 loading="lazy"
                 decoding="async">
        <?php else : ?>
            <span class="flex items-center justify-center w-full h-full font-mono text-blue-400 text-sm px-4 text-center">
                <?php echo esc_html($title); ?>
            </span>
        <?php endif; ?>
    </div>

    <div class="grid gap-1 content-start p-4 lg:p-5">
        <h3 class="font-sans font-semibold text-base lg:text-lg leading-snug tracking-tight text-blue-900 group-hover:text-blue-500 transition-colors duration-200">
            <?php echo esc_html($title); ?>
        </h3>
        <?php if ($subtitle) : ?>
            <p class="font-mono uppercase tracking-widest text-caption text-blue-400 leading-tight">
                <?php echo esc_html($subtitle); ?>
            </p>
        <?php endif; ?>
    </div>
</a>
