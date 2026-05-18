<?php
/**
 * Two-Door CTA Template Part
 *
 * The canonical Configure & Quote + Talk to a Specialist pair.
 * One door is self-serve, one door is human. Equal weight, equal voice.
 *
 * Defaults to the front-page `#contact` anchor for the specialist door so
 * a same-page scroll works without round-trips. Pass `specialist_url` to
 * point elsewhere (e.g. `/contact/` from non-front-page surfaces).
 *
 * @package Standard
 *
 * @param array $args {
 *     @type string $primary_label    Optional. Defaults to 'Configure & Quote'.
 *     @type string $primary_url      Optional. Defaults to '/configurator/'.
 *     @type string $specialist_label Optional. Defaults to 'Talk to a Specialist'.
 *     @type string $specialist_url   Optional. Defaults to '#contact'.
 *     @type string $align            Optional. 'left' (default) | 'center'.
 *     @type string $theme            Optional. 'light' (default, dark text on light bg)
 *                                    | 'dark' (light text on dark bg).
 * }
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$defaults = [
    'primary_label'    => __('Configure & Quote', 'standard'),
    'primary_url'      => '/configurator/',
    'specialist_label' => __('Talk to a Specialist', 'standard'),
    'specialist_url'   => '#contact',
    'align'            => 'left',
    'theme'            => 'light',
];

$cta = wp_parse_args($args ?? [], $defaults);

$justify = $cta['align'] === 'center' ? 'justify-center' : '';
$secondary_class = $cta['theme'] === 'dark' ? 'btn-outline-light' : 'btn-secondary';
?>

<div class="flex flex-wrap items-center gap-4 <?php echo esc_attr($justify); ?>">
    <a
        href="<?php echo esc_url(\Standard\Url\internal($cta['primary_url'])); ?>"
        class="btn btn-primary"
    >
        <?php echo esc_html($cta['primary_label']); ?>
    </a>
    <a
        href="<?php echo esc_url(\Standard\Url\internal($cta['specialist_url'])); ?>"
        class="btn <?php echo esc_attr($secondary_class); ?>"
    >
        <?php echo esc_html($cta['specialist_label']); ?>
    </a>
</div>
