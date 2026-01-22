<?php
/**
 * The front page template.
 *
 * Displays the site's front page with hero slider showcasing featured machines.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Standard
 */

declare(strict_types=1);

get_header();
?>

<main id="primary">

    <?php get_template_part('templates/parts/front-page/hero-slider'); ?>

</main>

<?php
get_footer();
