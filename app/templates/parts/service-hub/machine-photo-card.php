<?php
/**
 * Service Hub (alt) — photo-led machine tile.
 *
 * Renders one machine as a visual gallery tile: full product photo on a
 * uniform dark field, name + descriptor below, linking to the machine
 * mini-page at /service-hub/<slug>/. Built for the drenched (blue-900)
 * gallery band, so borders are blue-800 and text runs light. Falls back to
 * a typographic tile when the machine has no image, so a 404'd asset never
 * shows a broken image. Zero radius, no shadow (DESIGN.md).
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
   class="group reveal-scale flex flex-col bg-blue-900 border border-blue-800 transition-colors duration-200 hover:border-blue-500 no-underline">

    <div class="relative aspect-square overflow-hidden bg-blue-800">
        <?php if ($image !== '') : ?>
            <img
                src="<?php echo esc_url($image); ?>"
                alt="<?php echo esc_attr($name); ?>"
                loading="lazy"
                decoding="async"
                class="absolute inset-0 h-full w-full object-contain p-6"
            >
        <?php else : ?>
            <span class="absolute inset-0 flex items-center justify-center p-6 text-center font-mono font-medium text-blue-300" style="font-size: var(--text-heading-sm);">
                <?php echo esc_html((string) ($machine['short_name'] ?? $name)); ?>
            </span>
        <?php endif; ?>

        <?php if ($badge !== '') : ?>
            <span class="absolute left-0 top-0 bg-red px-2 py-1 font-mono font-medium uppercase tracking-wider text-white" style="font-size: var(--text-caption);">
                <?php echo esc_html($badge); ?>
            </span>
        <?php endif; ?>
    </div>

    <div class="flex flex-col gap-1.5 border-t border-blue-800 p-5 lg:p-6">
        <span class="font-medium text-heading-sm text-white transition-colors duration-200 group-hover:text-blue-500">
            <?php echo esc_html($name); ?>
        </span>
        <?php if ($desc !== '') : ?>
            <span class="font-sans text-blue-300" style="font-size: var(--text-body); line-height: var(--leading-body);">
                <?php echo esc_html($desc); ?>
            </span>
        <?php endif; ?>
        <span class="mt-2 flex items-center gap-1.5 font-mono uppercase tracking-wider text-blue-400 transition-colors duration-200 group-hover:text-blue-500" style="font-size: var(--text-caption);">
            <?php esc_html_e('View service content', 'standard'); ?>
            <?php icon('arrow-right', ['class' => 'w-3 h-3']); ?>
        </span>
    </div>
</a>
