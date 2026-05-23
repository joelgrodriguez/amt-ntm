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
    'title'      => __('Focus on Your Business,<br class="hidden md:inline"> Not Your Machine.', 'standard'),
    'text'       => __(
        "New Tech Machinery's portable seamless gutter machines were built for precision, speed, and reliability on the jobsite. NTM invented the polyurethane drive rollers that are now standard across the gutter machine industry, and the MACH II line has been the benchmark for portable seamless gutter machines for over 30 years. Trusted by contractors on all seven continents, our gutter machines run smoothly with little need for adjustment, so your crews can focus on installation.",
        'standard'
    ),
    'image'      => content_url('/uploads/2026/05/ntm-mach2-gutter-install-abel-002.jpg'),
    'image_alt'  => __('Abel installing a seamless gutter run from an NTM MACH II machine', 'standard'),
]);
