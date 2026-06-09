<?php
/**
 * MACH II Family — Comparison Table
 *
 * Same shared comparison-table part the /seamless-gutter-machines/
 * page uses. Rows match the gutter category schema (profile / size /
 * speed / drive / lead time / best for). Mobile gets horizontal
 * scroll with first column sticky.
 *
 * @package Standard
 *
 * @usage MACH II Family (page-machii.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\MachinesData\get_gutter_machines;

// MACH II family only. BG7 belongs to the broader gutter category page.
$machines = array_values(array_filter(
    get_gutter_machines(),
    static fn (array $m): bool => str_starts_with((string) ($m['slug'] ?? ''), 'mach-ii-')
));

// Featured (Combo) first; matches the order in family-portrait and
// variant-matrix so the comparison reads with the lead pick on the left.
usort($machines, static function (array $a, array $b): int {
    return ((int) !empty($b['featured'])) <=> ((int) !empty($a['featured']));
});

get_template_part('templates/parts/comparison-table', null, [
    'section_id' => 'machii-comparison-title',
    'content'    => [
        'eyebrow' => '',
        'title'   => __('Three machines, side by side.', 'standard'),
        // TODO(copy): confirm wording — Evita flagged the bare title+table as
        // looking incomplete; this fills the gap. Remove to fall back to the
        // tighter title-only spacing.
        'subtitle' => __('Same MACH II build, three K-style widths. Match the profile and speed to the work your crew runs most.', 'standard'),
    ],
    'machines'   => $machines,
    'rows'       => [
        'profiles'  => __('Profile',  'standard'),
        'size'      => __('Size',     'standard'),
        'speed'     => __('Speed',    'standard'),
        'drive'     => __('Drive',    'standard'),
        'lead_time' => __('Lead Time','standard'),
        'best_for'  => __('Best For', 'standard'),
    ],
]);
