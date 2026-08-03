<?php
/**
 * Template Name: NTM — METALCON 2026
 *
 * Conversion page for private booth-meeting requests at METALCON 2026.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard;

if (!defined('ABSPATH')) {
    exit;
}

// Stakeholder placeholders for issue #130. Replace these values once the
// booth assignment and final demo plan are approved; section parts must read
// from this array so the temporary details never get scattered across files.
$metalcon_config = [
    'booth_number'  => 'TBD',          // TODO (#130): Replace with the confirmed booth number.
    'demo_length'   => '20-minute',    // TODO (#130): Placeholder until the demo length is confirmed.
    'demo_format'   => 'private demo', // TODO (#130): Placeholder until the demo format is confirmed.
    // TODO (#130): Placeholder SSQ3 image. Replace with the approved SSM artwork.
    'hero_image_id' => 20976,
];

get_header();

while (have_posts()) :
    the_post();
    ?>

    <main id="primary">
        <?php get_template_part('templates/pages/metalcon/hero', null, ['config' => $metalcon_config]); ?>

        <?php get_template_part('templates/pages/metalcon/meeting-form'); ?>

        <?php get_template_part('templates/pages/metalcon/what-youll-see'); ?>

        <?php get_template_part('templates/pages/metalcon/why-book-ahead'); ?>

        <?php get_template_part('templates/pages/metalcon/proof'); ?>

        <?php get_template_part('templates/pages/metalcon/practical', null, ['config' => $metalcon_config]); ?>
    </main>

    <?php
endwhile;

get_footer();
