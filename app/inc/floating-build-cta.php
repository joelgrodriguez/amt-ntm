<?php
/**
 * Floating Build & Quote CTA — page eligibility and URLs.
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
const LANDING_PAGE_SLUGS = [
    'no-payments-until-2027',
    'second-profile-50',
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

    if (is_page(LANDING_PAGE_SLUGS)) {
        return true;
    }

    foreach (LANDING_TEMPLATES as $template) {
        if (is_page_template($template)) {
            return true;
        }
    }

    if (!is_singular('product') || !function_exists('wc_get_product')) {
        return false;
    }

    $product = wc_get_product(get_queried_object_id());

    return $product instanceof \WC_Product
        && is_eligible_product_slug($product->get_slug());
}

/**
 * Product pages only get the floating control when it has an honest target.
 */
function is_eligible_product_slug(string $slug): bool {
    if (\Standard\MachineStatus\is_discontinued($slug)) {
        return true;
    }

    return get_configurator_url($slug) !== '';
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

            return '';
        }
    }

    return \Standard\Url\internal('/configurator/');
}

/**
 * @return array{url: string, label: string, aria_label: string}|null
 */
function get_context(): ?array {
    if (!is_eligible_page()) {
        return null;
    }

    if (is_singular('product') && function_exists('wc_get_product')) {
        $product = wc_get_product(get_queried_object_id());
        if (
            $product instanceof \WC_Product
            && \Standard\MachineStatus\is_discontinued($product->get_slug())
        ) {
            return [
                'url'        => \Standard\MachineStatus\get_replacement_url($product->get_slug()),
                'label'      => __('Explore SSQ3', 'standard'),
                'aria_label' => __('Explore the SSQ3 MultiPro replacement machine', 'standard'),
            ];
        }
    }

    $url = get_url();

    if ($url === '') {
        return null;
    }

    return [
        'url'        => $url,
        'label'      => __('Build & Quote', 'standard'),
        'aria_label' => __('Build and quote your NTM machine', 'standard'),
    ];
}
