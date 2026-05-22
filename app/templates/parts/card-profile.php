<?php
/**
 * Profile Card
 *
 * Shared card for the panel/gutter profile catalog. Used by the
 * /profiles landing grid and the header mega-menu. Cross-section
 * imagery sits on bg-blue-50 with object-contain inside a 16:9 well
 * because these are technical drawings, not photography; they must
 * not be cropped. Aspect matches card-post and card-product for a
 * consistent card art height across mixed grids.
 *
 * Args:
 *   profile (WP_Post|int): profile post or its ID
 *   context (string): 'grid' (default) or 'mega'
 *     - grid: shows machine-tag subtitle row
 *     - mega: title only, denser
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

// Up to two machine tag names, with a "+N" suffix if more exist.
$subtitle = '';
if ($context === 'grid') {
    $tags = get_the_terms($profile->ID, 'post_tag');
    if (is_array($tags) && !empty($tags)) {
        $names = array_map(static fn(\WP_Term $t): string => $t->name, array_slice($tags, 0, 2));
        $extra = count($tags) - count($names);
        $subtitle = implode(', ', $names);
        if ($extra > 0) {
            $subtitle .= sprintf(
                /* translators: %d: number of additional machine tags. */
                _n(' +%d more', ' +%d more', $extra, 'standard'),
                $extra
            );
        }
    }
}
?>

<a href="<?php echo esc_url($url); ?>"
   class="profile-card group grid gap-3 no-underline">

    <div class="profile-card__image bg-blue-50 aspect-[16/9] overflow-hidden flex items-center justify-center border border-blue-200 group-hover:border-blue-500 transition-colors duration-200">
        <?php if ($thumb) : ?>
            <img src="<?php echo esc_url($thumb); ?>"
                 alt="<?php echo esc_attr($title); ?>"
                 class="w-full h-full object-contain p-4 transition-transform duration-200 group-hover:scale-105"
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
