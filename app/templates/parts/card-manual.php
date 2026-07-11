<?php
/**
 * Manual Card
 *
 * Canonical manual card. Mirrors card-profile.php exactly so the /manuals
 * grid reads as a sibling of /profiles. Only delta: a small mono "PDF"
 * chip pinned to the image well's top-left corner so the card identifies
 * as a downloadable manual at a glance.
 *
 * Args:
 *   manual (WP_Post|int): manual post or its ID
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

$manual_arg = $args['manual'] ?? null;
$context    = $args['context'] ?? 'grid';

if ($manual_arg instanceof \WP_Post) {
    $manual = $manual_arg;
} elseif (is_int($manual_arg) && $manual_arg > 0) {
    $manual = get_post($manual_arg);
} else {
    return;
}

if (!$manual instanceof \WP_Post) {
    return;
}

$url   = isset($args['url']) && is_string($args['url']) && $args['url'] !== ''
    ? $args['url']
    : get_permalink($manual);
$title = get_the_title($manual);
$thumb = get_the_post_thumbnail_url($manual, 'product-card');

// Up to two machine tag names, with a "+N" suffix if more exist.
$subtitle = '';
if ($context === 'grid') {
    $tags = get_the_terms($manual->ID, 'post_tag');
    if (is_array($tags) && !empty($tags)) {
        $names = array_map(static fn(\WP_Term $t): string => $t->name, array_slice($tags, 0, 2));
        $extra = count($tags) - count($names);
        $subtitle = implode(', ', $names);
        if ($extra > 0) {
            $subtitle .= sprintf(' +%d', $extra);
        }
    }
}
?>

<a href="<?php echo esc_url($url); ?>"
   class="manual-card group grid grid-rows-[auto_1fr] no-underline bg-white border border-blue-200 hover:border-blue-500 transition-colors duration-200">

    <div class="manual-card__image relative aspect-[16/9] overflow-hidden border-b border-blue-200 group-hover:border-blue-500 transition-colors duration-200">
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

        <span class="absolute top-2 left-2 inline-flex items-center gap-1 bg-white border border-blue-200 px-2 py-1 font-mono font-medium uppercase tracking-widest text-blue-700"
              style="font-size: 10px; line-height: 1;"
              aria-hidden="true">
            <?php icon('file-text', ['class' => 'w-3 h-3']); ?>
            <?php esc_html_e('PDF', 'standard'); ?>
        </span>
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
