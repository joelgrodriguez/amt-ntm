<?php
/**
 * Seamless Gutter Machines — Brand Statement
 *
 * Data wrapper for the shared brand-statement template part. Carries
 * the keyword-rich mid-funnel narrative: gutter category leadership,
 * polyurethane drive rollers (the NTM-invented industry standard),
 * and the on-site fabrication thesis specific to gutters.
 *
 * @package Standard
 *
 * @usage Seamless Gutter Machines (page-seamless-gutter-machines.php)
 * @see   templates/parts/brand-statement.php
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

get_template_part('templates/parts/brand-statement', null, [
    'section_id' => 'gutter-brand-statement-title',
    'title'      => __('Focus on Your Business, Not Your Machine.', 'standard'),
    'text'       => __(
        "NTM portable seamless gutter machines were designed for precision, speed, and reliability on the jobsite. New Tech Machinery invented the polyurethane drive rollers that are now standard across the gutter machine industry, and the MACH II line has been the benchmark for portable seamless gutter machines for over 30 years. Trusted by contractors on all seven continents, our gutter machines run smoothly with little need for adjustment, letting your crews focus on installation.",
        'standard'
    ),
]);
