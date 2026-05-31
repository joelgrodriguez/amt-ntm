<?php
/**
 * Service Hub — machine footprint panel (hero right column).
 *
 * The technical "footprint" that sits opposite the hero text on a machine
 * service page: the plan-view drawing on a dot-grid panel, framed by mono
 * engineering chrome bars (top: ENGINEERING SPECS, bottom: Footprint + PDF
 * link). A blueprint, not a product shot. Light surface (white), hairline
 * blue-200 borders carry the structure (DESIGN.md §8).
 *
 * Unlike woo/product/parts/blueprint.php, this part is fully arg-driven so it
 * renders off-loop (the service-hub machine route is virtual, no post in
 * context). The footprint is resolved by machine slug upstream via
 * Standard\ServiceHubMachines\get_machine_footprint(). The dimension cells
 * live in the hero's left meta rail, so this panel is the drawing alone.
 *
 * Renders nothing unless there's a real footprint image. The SVG-name
 * placeholder is intentionally dropped here: a "ssh-machine.svg" text stub is
 * fine on a marketing product page, but on an owner's support page it reads as
 * a missing asset. No drawing -> the hero falls back to one column upstream.
 *
 * Args:
 *   image (string): footprint drawing URL (featured image of the footprint post)
 *   alt   (string): alt text for the drawing
 *   pdf   (string): full-resolution PDF URL, or '' (no link rendered)
 *   name  (string): machine display name, for the chrome label
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$image = (string) ($args['image'] ?? '');
$pdf   = (string) ($args['pdf'] ?? '');
$alt   = (string) ($args['alt'] ?? '');
$name  = (string) ($args['name'] ?? '');

if ($image === '') {
    return;
}

$alt = $alt !== ''
    ? $alt
    : ($name !== '' ? sprintf(__('%s machine footprint drawing', 'standard'), $name) : __('Machine footprint drawing', 'standard'));
?>

<figure class="bg-white border border-blue-200 m-0">

    <figcaption class="flex items-center justify-between border-b border-blue-200 py-3 font-mono uppercase tracking-wider text-blue-700" style="font-size: var(--text-caption);">
        <span class="flex items-center gap-3 pl-3">
            <span class="w-2 h-2 bg-red" aria-hidden="true"></span>
            <?php esc_html_e('Engineering Specs', 'standard'); ?>
        </span>
        <span class="pr-3 text-blue-400"><?php esc_html_e('Plan View', 'standard'); ?></span>
    </figcaption>

    <div class="pattern-dot-grid pattern-dot-grid--solid p-6 lg:p-8">
        <?php if ($pdf !== '') : ?>
            <a
                href="<?php echo esc_url($pdf); ?>"
                target="_blank"
                rel="noopener"
                aria-label="<?php echo esc_attr(sprintf(__('Open %s footprint PDF in a new tab', 'standard'), $name !== '' ? $name : __('machine', 'standard'))); ?>"
                class="block w-full transition-opacity duration-200 hover:opacity-90"
            >
                <?php \Standard\Images\responsive_image($image, $alt, 'large', [
                    'class' => 'block w-full h-auto',
                ]); ?>
            </a>
        <?php else : ?>
            <?php \Standard\Images\responsive_image($image, $alt, 'large', [
                'class' => 'block w-full h-auto',
            ]); ?>
        <?php endif; ?>
    </div>

    <div class="flex items-center justify-between border-t border-blue-200 py-3 font-mono uppercase tracking-wider text-blue-900" style="font-size: var(--text-caption);">
        <span class="flex items-center gap-2 pl-3">
            <?php icon('file-text', ['class' => 'w-3 h-3 text-red']); ?>
            <?php esc_html_e('Machine Footprint', 'standard'); ?>
        </span>
        <?php if ($pdf !== '') : ?>
            <a
                href="<?php echo esc_url($pdf); ?>"
                target="_blank"
                rel="noopener"
                class="flex items-center gap-2 pr-3 text-blue-700 hover:text-blue-500 transition-colors duration-200 no-underline"
            >
                <?php esc_html_e('Open PDF', 'standard'); ?>
                <?php icon('external-link', ['class' => 'w-3 h-3']); ?>
            </a>
        <?php else : ?>
            <span class="flex gap-1 pr-3" aria-hidden="true">
                <span class="w-1 h-3 bg-blue-300"></span>
                <span class="w-1 h-3 bg-blue-300"></span>
                <span class="w-1 h-3 bg-blue-300"></span>
                <span class="w-1 h-3 bg-red"></span>
            </span>
        <?php endif; ?>
    </div>

</figure>
