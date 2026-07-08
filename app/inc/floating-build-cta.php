<?php
/**
 * Floating Build & Configure CTA — page eligibility and URLs.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard\FloatingBuildCta;

use function Standard\Woo\Catalog\get_configurator_url;

if (!defined('ABSPATH')) {
    exit;
}

/** @var list<string> */
const LANDING_TEMPLATES = [
    'page-machines.php',
    'page-roof-wall-panel-machines.php',
    'page-seamless-gutter-machines.php',
];

/** @var list<string> */
const MACHINE_CATEGORIES = [
    'roof-wall-panel-machines',
    'gutter-machines',
];

/**
 * Whether the current request should render the floating CTA.
 */
function is_eligible_page(): bool {
    if (is_admin() || is_404()) {
        return false;
    }

    if (is_front_page()) {
        return true;
    }

    foreach (LANDING_TEMPLATES as $template) {
        if (is_page_template($template)) {
            return true;
        }
    }

    return is_singular('product') && has_term(MACHINE_CATEGORIES, 'product_cat');
}

/**
 * Element id observed to reveal the CTA after scroll (IntersectionObserver).
 */
function get_scroll_anchor(): string {
    if (is_singular('product')) {
        return 'machine-hero';
    }

    if (is_front_page()) {
        return 'hero-slider';
    }

    if (is_page_template('page-machines.php')) {
        return 'machines-hero';
    }

    if (is_page_template('page-roof-wall-panel-machines.php')) {
        return 'roof-wall-hero';
    }

    if (is_page_template('page-seamless-gutter-machines.php')) {
        return 'gutter-hero';
    }

    return '';
}

/**
 * Configurator destination for the current page.
 */
function get_url(): string {
    if (is_singular('product') && function_exists('wc_get_product')) {
        $product = wc_get_product(get_queried_object_id());

        if ($product instanceof \WC_Product) {
            $url = get_configurator_url($product->get_slug());

            if ($url !== '') {
                return $url;
            }
        }
    }

    return \Standard\Url\internal('/configurator/');
}

/**
 * @return array{url: string, label: string, aria_label: string, scroll_anchor: string}|null
 */
function get_context(): ?array {
    if (!is_eligible_page()) {
        return null;
    }

    $url = get_url();

    if ($url === '') {
        return null;
    }

    return [
        'url'           => $url,
        'label'         => __('Build & Configure', 'standard'),
        'aria_label'    => __('Build and configure your NTM machine', 'standard'),
        'scroll_anchor' => get_scroll_anchor(),
    ];
}