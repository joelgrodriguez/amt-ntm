<?php
/**
 * Machines Page — Comparison Table (curated)
 *
 * Parent /machines page surfaces a curated four-machine comparison
 * (flagship + top picks across both categories) so the table fits
 * desktop naturally. The full per-category tables live on the
 * sub-pages, where 5–7 columns fit cleanly.
 *
 * Why curated, not "show everything": ten columns can't fit a desktop
 * container without horizontal scroll, and the parent pillar's job is
 * to direct readers down into a category, not exhaust every SKU.
 *
 * @package Standard
 *
 * @usage Machines Page (page-machines.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\MachinesData\get_all_machines;

$featured_slugs = [
    'ssq3-multipro',         // roof-wall flagship
    'ssh-multipro',          // roof-wall residential
    'mach-ii-combo-gutter',  // gutter featured
];

$all      = get_all_machines();
$by_slug  = array_column($all, null, 'slug');
$machines = [];
foreach ($featured_slugs as $slug) {
    if (isset($by_slug[$slug])) {
        $machines[] = $by_slug[$slug];
    }
}

get_template_part('templates/parts/comparison-table', null, [
    'section_id' => 'comparison-title',
    'content'    => [
        'eyebrow' => __('Compare', 'standard'),
        'title'   => __('Top Picks, Side by Side', 'standard'),
    ],
    'machines'   => $machines,
    'rows'       => [
        'profiles' => __('Profiles', 'standard'),
        'speed'    => __('Speed', 'standard'),
        'power'    => __('Power', 'standard'),
        'shear'    => __('Shear', 'standard'),
        'best_for' => __('Best For', 'standard'),
    ],
]);
