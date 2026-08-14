<?php
/**
 * Shared product-card action rules.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard\ProductCard;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * @param array<string, mixed> $product
 * @return array{label: string, url: string, new_tab: bool}
 */
function get_action(array $product): array {
    $explore_url  = (string) ($product['explore_url'] ?? '');
    $is_accessory = (bool) ($product['is_accessory'] ?? false);

    if ($is_accessory) {
        return [
            'label'   => \__('Explore', 'standard'),
            'url'     => $explore_url,
            'new_tab' => false,
        ];
    }

    $explicit_url = (string) ($product['cta_url'] ?? '');
    if ($explicit_url !== '') {
        return [
            'label'   => (string) ($product['cta_label'] ?? \__('Get a Quote', 'standard')),
            'url'     => $explicit_url,
            'new_tab' => str_contains($explicit_url, '/configurator/'),
        ];
    }

    $build_url = (string) ($product['build_url'] ?? '');
    if ($build_url !== '') {
        return [
            'label'   => \__('Build & Quote', 'standard'),
            'url'     => $build_url,
            'new_tab' => true,
        ];
    }

    return [
        'label'   => \__('Get a Quote', 'standard'),
        'url'     => \Standard\Url\internal('/contact/'),
        'new_tab' => false,
    ];
}
