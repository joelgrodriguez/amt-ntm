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

// Keep event facts in one config so each page section renders the same details.
$metalcon_config = [
    'booth_number'  => '1525',
    'demo_length'   => '20-minute',
    'demo_format'   => 'presentation',
    'demo_schedule' => metalcon_presentation_schedule(),
    'hero_image_url' => 'https://newtechmachinery.com/wp-content/uploads/2026/08/Covered-machine-tradeshow-image.png',
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
