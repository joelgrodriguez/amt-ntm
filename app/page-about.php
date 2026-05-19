<?php
/**
 * Template Name: About
 *
 * Custom template for the About page. Composition:
 *
 *   manifesto   (light editorial: eyebrow + headline + lede + photo + metric strip)
 *   video       (dark, chrome-bar framed Who Is NTM)
 *   origin      (light: institutional narrative + spec ledger)
 *   timeline    (dark, chrome-bar framed: 5 signature firsts)
 *   leadership  (light: industry standing + MCA/NRCA/CRA memberships)
 *   closer      (shared dark CTA, reused from the front page)
 *
 * Light dominates; the two dark sections (video, timeline) carry the
 * chrome-bar grammar where it earns its seat. Calm, paced, leadership-
 * voiced, not busy.
 *
 * Set this template on the About page from Page > Page Attributes >
 * Template > "About".
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<main id="primary">

    <?php get_template_part('templates/parts/about/manifesto'); ?>

    <?php get_template_part('templates/parts/video-section', null, [
        'title'      => __('Who Is NTM?', 'standard'),
        'channel'    => __('Portable Rollforming Channel', 'standard'),
        'video_type' => __('Company Overview', 'standard'),
        'section_id' => 'about-who-is-ntm',
    ]); ?>

    <?php get_template_part('templates/parts/about/origin'); ?>

    <?php get_template_part('templates/parts/about/timeline'); ?>

    <?php get_template_part('templates/parts/about/leadership'); ?>

    <?php get_template_part('templates/parts/cta/closer', null, [
        'title'           => __('Ready to Take Control of Your Business?', 'standard'),
        'text'            => __('Join thousands of contractors who stopped waiting on suppliers and started rolling their own profits.', 'standard'),
        'cta_primary'     => __('Talk to a Specialist', 'standard'),
        'cta_primary_url' => '/contact/',
        'section_id'      => 'about-closer-title',
    ]); ?>

</main>

<?php
get_footer();
