<?php
/**
 * Template Name: About
 *
 * Renders the About page using reusable section template parts.
 *
 * Flow:
 *  1. Manifesto       — partnership headline, "Who Is NTM?" company-overview video, metric strip
 *  2. Capabilities    — design, engineer, manufacture, ship, train & service
 *  3. People          — four portraits across the disciplines that touch every customer
 *  4. Origin          — "Built it. Still building it." (merged origin + 5-machine timeline)
 *  5. Support         — Aurora + Hermosillo, parts, training, financing, memberships
 *  6. Closer CTA      — partnership-aligned close
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
