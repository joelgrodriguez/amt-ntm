<?php
/**
 * Template Name: Careers
 *
 * User-selectable Careers page template. Editors assign this from the
 * page Template dropdown (allowlisted in page-templates.php). It is not
 * auto-applied by slug — switch it on or off per page.
 *
 * Flow:
 *  1. Hero            — H1, employer lede, primary CTA to open roles
 *  2. Why NTM         — culture facts from original employer copy
 *  3. Career paths    — engineering, production, service, customer support
 *  4. What it's like  — shop-floor imagery (approved marketing assets)
 *  5. Practices       — EEO, benefits (generic), drug-free workplace
 *  6. FAQ             — candidate questions + FAQPage JSON-LD
 *  7. Closer          — single final CTA to Mazzella Search All Jobs
 *
 * Openings URL: official Mazzella EnterTime "Search All Jobs" board.
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Canonical Mazzella Search All Jobs URL (EnterTime careers board).
 * Source: https://www.mazzellacompanies.com/company/careers/
 */
const NTM_CAREERS_OPENINGS_URL = 'https://secure3.entertimeonline.com/ta/6082508.careers?CareersSearch=&lang=en-US';

get_header();
?>

<main id="primary">

    <?php get_template_part('templates/parts/careers/hero'); ?>

    <?php get_template_part('templates/parts/careers/why'); ?>

    <?php get_template_part('templates/parts/careers/paths'); ?>

    <?php get_template_part('templates/parts/careers/life'); ?>

    <?php get_template_part('templates/parts/careers/practices'); ?>

    <?php get_template_part('templates/parts/careers/faq'); ?>

    <?php get_template_part('templates/parts/cta/closer', null, [
        'title'               => __('Browse current openings', 'standard'),
        'text'                => __('New Tech Machinery jobs are listed on the Mazzella Search All Jobs board. View openings and apply there.', 'standard'),
        'cta_primary'         => __('View open positions', 'standard'),
        'cta_primary_url'     => NTM_CAREERS_OPENINGS_URL,
        'cta_primary_new_tab' => true,
        'cta_primary_icon'    => 'external-link',
        'section_id'          => 'careers-closer-title',
    ]); ?>

</main>

<?php
get_footer();
