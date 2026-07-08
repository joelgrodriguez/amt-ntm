<?php
/**
 * Floating Build & Configure CTA
 *
 * Fixed bottom-left shortcut to the configurator. Machine pages route to
 * the machine-specific build; category landings and the home page route
 * to /configurator/.
 *
 * @package Standard
 *
 * @param array $args {
 *     @type string $url           Configurator URL.
 *     @type string $label         Visible label.
 *     @type string $aria_label    Accessible name.
 *     @type string $scroll_anchor Element id to observe for reveal.
 * }
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$url           = $args['url'] ?? '';
$label         = $args['label'] ?? __('Build & Configure', 'standard');
$aria_label    = $args['aria_label'] ?? $label;
$scroll_anchor = $args['scroll_anchor'] ?? '';

if ($url === '') {
    return;
}
?>

<a
    id="floating-build-cta"
    href="<?php echo esc_url($url); ?>"
    class="floating-build-cta"
    aria-label="<?php echo esc_attr($aria_label); ?>"
    <?php if ($scroll_anchor !== '') : ?>
        data-scroll-anchor="<?php echo esc_attr($scroll_anchor); ?>"
    <?php endif; ?>
    target="_blank"
    rel="noopener"
>
    <?php icon('settings', ['class' => 'w-5 h-5']); ?>
    <span class="floating-build-cta__label"><?php echo esc_html($label); ?></span>
</a>