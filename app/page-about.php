<?php
/**
 * Template Name: About
 *
 * Renders the About page using reusable section template parts.
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
