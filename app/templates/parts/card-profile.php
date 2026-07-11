<?php
/**
 * Profile Card
 *
 * Canonical profile card. Used everywhere a profile renders: /profiles
 * landing, mega-menu Profiles tab, and the Woo machine profile carousels
 * (Available Profiles + Panel Profiles). Mirrors card-manual.php in shape
 * (bordered image well, gap to a sans title + mono machine-tag subtitle
 * sitting outside the well) so the two catalogs read as siblings. One
 * delta: 16:9 image well instead of square, since profile renders are
 * already 16:9 and don't need letterboxing.
 *
 * Args:
 *   profile  (WP_Post|int): profile post or its ID
 *   context  (string): 'grid' (default), 'mega', or 'carousel'
 *     - grid:     standard catalog tile
 *     - mega:     denser, used in the header mega-menu Profiles tab
 *     - carousel: sized for .carousel__track (snap + responsive widths)
 *
 * All three contexts show the same title + machine-tag subtitle so the
 * card reads identically wherever it appears.
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

$url   = isset($args['url']) && is_string($args['url']) && $args['url'] !== ''
    ? $args['url']
    : get_permalink($profile);
$title = get_the_title($profile);
$thumb = get_the_post_thumbnail_url($profile, 'product-card');

// Up to two machine tag names, with a "+N" suffix if more exist.
$subtitle = '';
$tags = get_the_terms($profile->ID, 'post_tag');
if (is_array($tags) && !empty($tags)) {
    $names = array_map(static fn(\WP_Term $t): string => $t->name, array_slice($tags, 0, 2));
    $extra = count($tags) - count($names);
    $subtitle = implode(', ', $names);
    if ($extra > 0) {
        $subtitle .= sprintf(' +%d', $extra);
    }
}

// Carousel context piggybacks .carousel__card on the anchor itself so the
// card carries snap + responsive width directly inside .carousel__track.
$root_classes = 'profile-card group grid gap-3 no-underline';
if ($context === 'carousel') {
    $root_classes .= ' carousel__card';
}
?>

<a href="<?php echo esc_url($url); ?>"
   class="<?php echo esc_attr($root_classes); ?>">

    <div class="profile-card__image relative bg-blue-50 aspect-[16/9] overflow-hidden flex items-center justify-center border border-blue-200 group-hover:border-blue-500 transition-colors duration-200">
        <?php if ($thumb) : ?>
            <img src="<?php echo esc_url($thumb); ?>"
                 alt="<?php echo esc_attr($title); ?>"
                 class="w-full h-full object-cover transition-transform duration-200 group-hover:scale-105"
                 loading="lazy"
                 decoding="async">
        <?php else : ?>
            <span class="font-mono text-blue-400 text-sm px-4 text-center">
                <?php echo esc_html($title); ?>
            </span>
        <?php endif; ?>
    </div>

    <div class="grid gap-1">
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
