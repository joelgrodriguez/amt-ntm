<?php
/**
 * Template Name: About
 *
 * Custom template for the About page. Composition:
 *
 *   manifesto   (light: eyebrow + headline + lede + photo + metric strip)
 *   video       (dark, chrome-bar framed Who Is NTM)
 *   origin      (light: single-column narrative with right-gutter callouts)
 *   timeline    (light bg-blue-50: 5 signature firsts on a hairline rail)
 *   leadership  (light: industry-standing posture + memberships + lineup CTA)
 *   closer      (shared dark CTA, reused from the front page)
 *
 * Light dominates. The video is the only dark section that still carries
 * chrome-bar grammar; the timeline moved to bg-blue-50 and the origin and
 * leadership sections lost their chrome frames in favor of quieter
 * single-column light blocks. The page reads paced, not framed.
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
