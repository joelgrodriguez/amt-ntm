<?php
/**
 * Choose Your Machine — Seamless Gutter Ledger
 *
 * Renders the gutter-family fit ledger from the assembled catalog, on a
 * tinted surface so the two ledgers read as distinct sections. The fork's
 * "seamless gutter" lane jumps here (#gutter-ledger).
 *
 * @package Standard
 *
 * @usage Choose Your Machine (page-choose-your-machine.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$catalog = include get_template_directory() . '/templates/pages/choose/data.php';

get_template_part('templates/pages/choose/fit-ledger', null, [
    'section_id' => 'gutter-ledger',
    'surface'    => 'bg-blue-50',
    'eyebrow'    => __('Seamless gutter machines', 'standard'),
    'title'      => __('Pick Your Seamless Gutter Machine', 'standard'),
    'subtitle'   => __('Four machines covering K-style and box gutter. Most contractors start with the 5\" MACH II; size up or add box gutter from there.', 'standard'),
    'rows'       => $catalog['gutter'] ?? [],
    'secondary'  => [
        ['label' => __('Gutter machine selection guide', 'standard'), 'url' => '/portable-gutter-machine-selection-guide/'],
        ['label' => __('What coil width should you use?', 'standard'), 'url' => '/what-coil-width-should-you-use/'],
    ],
]);
