<?php
/**
 * Service Hub machine grid card. Links to the machine's mini-page.
 *
 * Args:
 *   machine (array): a machines-data entry (slug, name, short_name, descriptor)
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
$name = (string) ($machine['short_name'] ?? $machine['name'] ?? $slug);
$desc = (string) ($machine['descriptor'] ?? '');
$url  = \Standard\Url\internal('/service-hub/' . $slug . '/');
?>
<a href="<?php echo esc_url($url); ?>"
   class="group flex flex-col gap-2 min-h-[44px] bg-white border border-blue-200 p-5 lg:p-6 transition-colors duration-200 hover:border-blue-500 no-underline">
    <span class="font-medium text-heading-sm text-blue-900 transition-colors duration-200 group-hover:text-blue-500">
        <?php echo esc_html($name); ?>
    </span>
    <?php if ($desc !== '') : ?>
        <span class="font-sans text-blue-600" style="font-size: var(--text-body); line-height: var(--leading-body);">
            <?php echo esc_html($desc); ?>
        </span>
    <?php endif; ?>
    <span class="mt-2 flex items-center gap-1.5 font-mono uppercase tracking-wider text-blue-700 transition-colors duration-200 group-hover:text-blue-500" style="font-size: var(--text-caption);">
        <?php esc_html_e('View service content', 'standard'); ?>
        <?php icon('arrow-right', ['class' => 'w-3 h-3']); ?>
    </span>
</a>
