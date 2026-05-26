<?php
/**
 * Template Name: About
 *
 * Renders the About page using reusable section template parts.
 *
 * Flow:
 *  1. Manifesto       — partnership headline, single engineer portrait, metric strip
 *  2. Video           — "Who is NTM?" company overview
 *  3. Capabilities    — design, engineer, manufacture, ship, train & service
 *  4. People          — four portraits across the disciplines that touch every customer
 *  5. Origin          — "Built it. Still building it." (merged origin + 5-machine timeline)
 *  6. Support         — Aurora + Hermosillo, parts, training, financing, memberships
 *  7. Closer CTA      — partnership-aligned close
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

    <div class="bg-white border-b border-blue-800" aria-hidden="true"></div>

    <?php get_template_part('templates/parts/about/capabilities'); ?>

    <?php get_template_part('templates/parts/about/people'); ?>

    <?php get_template_part('templates/parts/about/origin'); ?>

    <?php get_template_part('templates/parts/about/support'); ?>

    <?php get_template_part('templates/parts/cta/closer', null, [
        'title'           => __('Talk to a team committed to your success.', 'standard'),
        'text'            => __('Sales, engineering, service, and support, all in Aurora. The same people, for as long as you own the machine.', 'standard'),
        'cta_primary'     => __('Talk to a Specialist', 'standard'),
        'cta_primary_url' => '/contact/',
        'section_id'      => 'about-closer-title',
    ]); ?>

</main>

<?php
get_footer();
