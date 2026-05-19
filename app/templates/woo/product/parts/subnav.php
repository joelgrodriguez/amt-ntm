<?php
/**
 * Machine Product — Sticky Sub-Navigation Bar
 *
 * Sits in the page flow below the hero/CTA, then sticks to the top
 * when the user scrolls past it.
 * Left: machine switcher dropdown.
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

use function Standard\MachinesData\get_machine_categories;

$product = $args['product'] ?? null;
$machine = $args['machine'] ?? [];

if (!$product) {
    return;
}

$current_name     = $product->get_name();
$configurator_url = \Standard\Url\internal('/configurator/' . $product->get_slug() . '/');

// Resolve the short machine name from machines-data so the switcher
// button doesn't render the long WC product name in mono at body size.
// Resolves the WC slug through the alias map first (e.g.
// ssq-roof-panel-machine -> ssq-ii-multipro), then looks up the short
// label. Falls back to the full name when no match is found.
$current_short = $current_name;
if (
    function_exists('Standard\\MachinesData\\get_all_machines')
    && function_exists('Standard\\MachineProductData\\get_slug_aliases')
) {
    $aliases   = \Standard\MachineProductData\get_slug_aliases();
    $data_slug = $aliases[$product->get_slug()] ?? $product->get_slug();
    foreach (\Standard\MachinesData\get_all_machines() as $m) {
        if (($m['slug'] ?? '') === $data_slug) {
            $current_short = $m['name'] ?? $current_name;
            break;
        }
    }
}

// Anchor links — label => section ID
$nav_links = [
    __('Overview', 'standard')     => 'machine-breakdown',
    __('Gallery', 'standard')      => 'machine-gallery',
    __('Specs', 'standard')        => 'machine-specs',
    __('Reviews', 'standard')      => 'machine-testimonials',
    __('FAQ', 'standard')          => 'machine-faq',
];

// Machine switcher data
$categories = function_exists('Standard\MachinesData\get_machine_categories')
    ? get_machine_categories()
    : [];
?>

<div id="machine-subnav-sentinel"></div>
<nav
    id="machine-subnav"
    class="machine-subnav"
    aria-label="<?php esc_attr_e('Machine page navigation', 'standard'); ?>"
>
    <div class="container">
        <div class="machine-subnav__inner">

            <!-- Left: Machine Switcher -->
            <div class="machine-subnav__left">
                <div class="machine-subnav__switcher">
                    <button
                        type="button"
                        class="machine-subnav__switcher-btn"
                        aria-haspopup="menu"
                        aria-expanded="false"
                        aria-controls="machine-subnav-dropdown"
                    >
                        <span class="machine-subnav__current-name"><?php echo esc_html($current_short); ?></span>
                        <?php icon('chevron-down', ['class' => 'w-3.5 h-3.5 machine-subnav__chevron']); ?>
                    </button>

                    <div
                        id="machine-subnav-dropdown"
                        class="machine-subnav__dropdown"
                        role="menu"
                        aria-label="<?php esc_attr_e('Switch machine', 'standard'); ?>"
                        hidden
                    >
                        <?php foreach ($categories as $cat_key => $category) :
                            $group_id = 'machine-subnav-group-' . esc_attr($cat_key);
                        ?>
                            <div class="machine-subnav__dropdown-group" role="group" aria-labelledby="<?php echo $group_id; ?>">
                                <span id="<?php echo $group_id; ?>" class="machine-subnav__dropdown-label">
                                    <?php echo esc_html($category['label']); ?>
                                </span>
                                <?php foreach ($category['machines'] as $m) : ?>
                                    <a
                                        href="<?php echo esc_url(\Standard\Url\internal($m['url'] ?? '#')); ?>"
                                        class="machine-subnav__dropdown-item <?php echo ($m['slug'] === $product->get_slug()) ? 'is-active' : ''; ?>"
                                        role="menuitem"
                                    >
                                        <?php echo esc_html($m['short_name'] ?? $m['name']); ?>
                                        <?php if (!empty($m['badge'])) : ?>
                                            <span class="machine-subnav__badge"><?php echo esc_html($m['badge']); ?></span>
                                        <?php endif; ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Right: Section Anchors + Build CTA -->
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
