<?php
/**
 * Template Name: Empty Shell
 *
 * Full document shell for embedded tools that bring their own UI.
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
    <style>
        html,
        body.configurator-empty-shell {
            min-height: 100%;
            margin: 0;
        }

        body.configurator-empty-shell #primary {
            width: 100%;
            min-height: 100vh;
        }

        body.configurator-empty-shell #primary > * {
            margin: 0;
        }

        body.configurator-empty-shell iframe,
        body.configurator-empty-shell .op-interactive,
        body.configurator-empty-shell .op-interactive iframe {
            display: block;
            width: 100%;
            min-height: 100vh;
            border: 0;
        }
    </style>
</head>

<body <?php body_class('configurator-empty-shell'); ?>>
<?php wp_body_open(); ?>

<main id="primary">
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
