<?php
/**
 * Template Name: Accessories
 *
 * Landing page for the Accessories & Upgrades catalog.
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

    <?php get_template_part('templates/pages/accessories/hero'); ?>

    <section class="section bg-white" aria-labelledby="catalog-title">
        <div class="container layout-with-rail">

            <?php get_template_part('templates/pages/accessories/catalog-nav'); ?>

            <div class="min-w-0">
                <?php get_template_part('templates/pages/accessories/catalog-grid'); ?>
            </div>

        </div>
    </section>

    <?php get_template_part('templates/pages/accessories/fit-by-machine'); ?>

    <?php get_template_part('templates/pages/accessories/final-cta'); ?>

</main>

<?php
get_footer();
