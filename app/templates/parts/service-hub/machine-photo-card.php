<?php
/**
 * Service Hub (alt) — compact horizontal machine card.
 *
 * Renders one machine as a directory row: a small product thumbnail on the
 * left, name + descriptor on the right, linking to the machine mini-page at
 * /service-hub/<slug>/. This is the support hub, not the showroom: the photo
 * is a recognition cue, not a hero, so it stays small and the card stays
 * scannable. Light band (blue-50), so card is white with a blue-200 hairline
 * and dark text. Falls back to a typographic thumbnail when the machine has
 * no image, so a 404'd asset never shows a broken image. Zero radius, no
 * shadow (DESIGN.md).
 *
 * Args:
 *   machine (array): a machines-data entry (slug, name, short_name,
 *                    descriptor, image, badge)
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$machine = $args['machine'] ?? [];
$slug    = (string) ($machine['slug'] ?? '');
if ($slug === '') {
    return;
}

// Photo tiles can afford the full name; the compact text card prefers short_name.
$name  = (string) ($machine['name'] ?? $machine['short_name'] ?? $slug);
$desc  = (string) ($machine['descriptor'] ?? '');
$image = (string) ($machine['image'] ?? '');
$badge = (string) ($machine['badge'] ?? '');
$url   = \Standard\Url\internal('/service-hub/' . $slug . '/');
?>
<a href="<?php echo esc_url($url); ?>"
   class="group reveal-scale flex items-center gap-4 bg-white border border-blue-200 p-4 transition-colors duration-200 hover:border-blue-500 no-underline">

    <div class="relative shrink-0 size-20 overflow-hidden bg-blue-50 border border-blue-200">
        <?php if ($image !== '') : ?>
            <img
                src="<?php echo esc_url($image); ?>"
                alt="<?php echo esc_attr($name); ?>"
                loading="lazy"
                decoding="async"
                class="absolute inset-0 h-full w-full object-contain p-2"
            >
        <?php else : ?>
            <span class="absolute inset-0 flex items-center justify-center p-2 text-center font-mono font-medium text-blue-600" style="font-size: var(--text-caption);">
                <?php echo esc_html((string) ($machine['short_name'] ?? $name)); ?>
            </span>
        <?php endif; ?>

        <?php if ($badge !== '') : ?>
            <span class="absolute left-0 top-0 bg-red px-1.5 py-0.5 font-mono font-medium uppercase tracking-wider text-white" style="font-size: 9px;">
                <?php echo esc_html($badge); ?>
            </span>
        <?php endif; ?>
    </div>

    <div class="flex flex-col gap-0.5 min-w-0">
        <span class="font-medium text-heading-sm text-blue-900 transition-colors duration-200 group-hover:text-blue-500">
            <?php echo esc_html($name); ?>
        </span>
        <?php if ($desc !== '') : ?>
            <span class="font-sans text-blue-600" style="font-size: var(--text-body); line-height: var(--leading-body);">
                <?php echo esc_html($desc); ?>
            </span>
        <?php endif; ?>
    </div>

    <?php icon('arrow-right', ['class' => 'shrink-0 ml-auto w-4 h-4 text-blue-400 transition-colors duration-200 group-hover:text-blue-500', 'aria-hidden' => 'true']); ?>
</a>
