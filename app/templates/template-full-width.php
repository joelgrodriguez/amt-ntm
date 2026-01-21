<?php
/**
 * Template Name: Full Width
 *
 * A simple template with no container or wrapper.
 * Just header, raw content, and footer.
 *
 * @package Standard
 */

get_header();
?>

<main id="primary">
    <?php
    while (have_posts()) :
        the_post();
        the_content();
    endwhile;
    ?>
</main>

<?php
get_footer();
