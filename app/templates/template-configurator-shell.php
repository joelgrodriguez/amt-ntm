<?php
/**
 * Configurator-only document shell.
 *
 * Renders the /configurator/ page tree without the normal site chrome. The
 * page content owns the dynamic viewport height so iOS browser controls can
 * resize without a competing 100vh minimum on the shell or iframe.
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
        body.configurator-shell {
            height: 100%;
            margin: 0;
        }

        body.configurator-shell #primary,
        body.configurator-shell #primary > * {
            width: 100%;
            height: 100%;
            margin: 0;
        }

        body.configurator-shell #primary iframe,
        body.configurator-shell #primary .op-interactive {
            display: block;
            width: 100%;
            height: 100%;
            min-height: 0;
            border: 0;
        }
    </style>
</head>

<body <?php body_class('configurator-shell'); ?>>
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
