<?php
/**
 * Template Name: Corbel
 *
 * Blank full-screen embed canvas. No header, no footer, no body chrome.
 * The post content (typically an iframe, WebGL canvas, or third-party
 * embed) renders into a 100vw x 100vh container. wpautop is disabled
 * so raw markup ships untouched.
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}
remove_filter('the_content', 'wpautop');
remove_filter('the_content', 'wptexturize');

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        html, body { margin: 0; padding: 0; height: 100%; overflow: hidden; }
        .corbel-embed { width: 100vw; height: 100vh; display: block; }
        .corbel-embed > iframe { width: 100%; height: 100%; border: 0; display: block; }
    </style>
    <?php wp_head(); ?>
</head>
<body <?php body_class('corbel-embed-canvas'); ?>>
<?php wp_body_open(); ?>

<main id="primary" class="corbel-embed">
    <?php
    while (have_posts()) :
        the_post();
        the_content();
    endwhile;
    ?>
</main>

<?php wp_footer(); ?>
</body>
</html>
