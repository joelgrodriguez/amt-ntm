<?php
/**
 * Accessories Page — Hero Banner
 *
 * Data wrapper for the shared hero-category part.
 *
 * @package Standard
 *
 * @usage Accessories Page (page-accessories.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\AccessoriesData\get_accessory_count;

$count = get_accessory_count();
$count_label = $count > 0 ? $count . '+' : '60+';

get_template_part('templates/parts/hero-category', null, [
    'section_id' => 'accessories-hero',
    'content'    => [
        'eyebrow'           => __('Accessories · Upgrades', 'standard'),
        'title'             => __('No Machine Ships Finished.', 'standard'),
        'subtitle'          => __('Reel stands, controllers, runout tables, and roll tooling engineered to fit every machine NTM has built in 34 years.', 'standard'),
        'cta_primary'       => __('Browse the Catalog', 'standard'),
        'cta_primary_url'   => '#catalog',
        'cta_secondary'     => __('Talk to a Specialist', 'standard'),
        'cta_secondary_url' => '/contact/',
        'video'             => '',
        'poster'            => 'https://newtechmachinery.com/wp-content/uploads/2025/09/Machine-on-rooftop-scaled.jpg',
    ],
    'stats' => [
        ['value' => $count_label,    'label' => __('Accessories', 'standard')],
        ['value' => '8',             'label' => __('Machine Families', 'standard')],
        ['value' => '34 yrs',        'label' => __('Of Fit', 'standard')],
    ],
]);
