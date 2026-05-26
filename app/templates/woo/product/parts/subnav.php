<?php
/**
 * Machine Product — Sticky Sub-Navigation Bar
 *
 * Sits in the page flow below the hero/CTA, then sticks to the top
 * when the user scrolls past it.
 * Left: current machine label.
 * Right: section anchor links + Build CTA.
 *
 * @package Standard
 * @var array{product: \WC_Product, machine: array} $args
 *
 * @usage Single Machine Product (single-machine.php)
 * @see js/modules/MachineSubnav.js
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$product = $args['product'] ?? null;
$machine = $args['machine'] ?? [];

if (!$product) {
    return;
}

$current_name     = $product->get_name();
$configurator_url = \Standard\Url\internal('/configurator/' . $product->get_slug() . '/');
$current_short = $current_name;
if (
    function_exists('Standard\\MachinesData\\get_all_machines')
    && function_exists('Standard\\MachineProductData\\get_slug_aliases')
) {
    $aliases   = \Standard\MachineProductData\get_slug_aliases();
    $data_slug = $aliases[$product->get_slug()] ?? $product->get_slug();
    foreach (\Standard\MachinesData\get_all_machines(true) as $m) {
        if (($m['slug'] ?? '') === $data_slug) {
            $current_short = $m['name'] ?? $current_name;
            break;
        }
    }
}
$nav_links = [];

if (!empty($machine['breakdown'])) {
    $nav_links[__('Overview', 'standard')] = 'machine-breakdown';
}

$fit = $machine['fit'] ?? null;
if (!empty($fit['is_for']) || !empty($fit['is_not_for'])) {
    $nav_links[__('Fit', 'standard')] = 'machine-fit';
}

if (!empty($machine['profiles']['tag_slugs'])) {
    $nav_links[__('Profiles', 'standard')] = 'machine-profiles';
}

if (!empty($machine['accessories']['product_tag'])) {
    $nav_links[__('Accessories', 'standard')] = 'machine-accessories';
}

if (!empty($machine['specs']['dimensions'])) {
    $nav_links[__('Footprint', 'standard')] = 'machine-blueprint';
}

if (!empty($machine['specs'])) {
    $nav_links[__('Specs', 'standard')] = 'machine-specs';
}

$resources = $machine['resources'] ?? [];
if (!empty($resources['manual']) || !empty($resources['brochure']) || !empty($resources['service_training_url'])) {
    $nav_links[__('Resources', 'standard')] = 'machine-resources';
}

if (!empty($machine['faq'])) {
    $nav_links[__('FAQ', 'standard')] = 'machine-faq';
}

if (!empty($machine['case_study'])) {
    $nav_links[__('Case Study', 'standard')] = 'machine-case-study';
}
?>

<div id="machine-subnav-sentinel"></div>
<nav
    id="machine-subnav"
    class="machine-subnav"
    aria-label="<?php esc_attr_e('Machine page navigation', 'standard'); ?>"
>
    <div class="container">
        <div class="machine-subnav__inner">
            <div class="machine-subnav__left">
                <span class="machine-subnav__current-name"><?php echo esc_html($current_short); ?></span>
            </div>
            <div class="machine-subnav__right">
                <ul class="machine-subnav__links">
                    <?php foreach ($nav_links as $label => $section_id) : ?>
                        <li>
                            <a
                                href="#<?php echo esc_attr($section_id); ?>"
                                class="machine-subnav__link"
                                data-section="<?php echo esc_attr($section_id); ?>"
                            >
                                <?php echo esc_html($label); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <div class="machine-subnav__cta">
                    <a href="<?php echo esc_url($configurator_url); ?>" class="btn btn-primary btn-sm">
                        <?php esc_html_e('Build', 'standard'); ?>
                    </a>
                </div>
            </div>

        </div>
    </div>
</nav>
