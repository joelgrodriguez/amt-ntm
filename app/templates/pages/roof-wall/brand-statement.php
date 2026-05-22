<?php
/**
 * Roof & Wall Panel Machines — Brand Statement
 *
 * Data wrapper for the shared brand-statement template part. Carries
 * the keyword-rich mid-funnel narrative the old theme used to set on
 * this page: industry-leader claim, polyurethane drive rollers (the
 * NTM-invented standard), seven-continents reach, and the on-site
 * fabrication thesis.
 *
 * @package Standard
 *
 * @usage Roof & Wall Panel Machines (page-roof-wall-panel-machines.php)
 * @see   templates/parts/brand-statement.php
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

get_template_part('templates/parts/brand-statement', null, [
    'section_id' => 'roof-wall-brand-statement-title',
    'title'      => __('Take Your Roof Panel Operation<br class="hidden md:inline"> to the Next Level.', 'standard'),
    'text'       => __(
        "From standing seam roofing to flush wall and board and batten siding, New Tech Machinery's portable rollformers fabricate panels on-site, anywhere the job takes you. NTM is the industry leader in portable rollforming: we invented the polyurethane drive rollers now standard across the industry, and our roof and wall panel machines are trusted by contractors on all seven continents, from rugged mountains to scorching deserts.",
        'standard'
    ),
]);
