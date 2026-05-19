<?php
/**
 * Template Name: About
 *
 * Custom template for the About page. Composition:
 *
 *   manifesto   (dark chrome-bar hero with metric strip)
 *   video       (reuses the shared video-section, retitled "Who Is NTM?")
 *   origin      (light blueprint frame: narrative + ledger)
 *   timeline    (dark band: 10 firsts, 1991 → 2021)
 *   leadership  (light frame: memberships + closing CTA)
 *
 * All sections share the chrome-bar / hairline blueprint language used
 * across three-step-plan, case-study, and blueprint. The about page
 * extends the system; it doesn't introduce a new one.
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

</main>

<?php
get_footer();
