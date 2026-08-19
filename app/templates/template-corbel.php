<?php
/**
 * Template Name: Corbel
 *
 * Full-screen Corbel configurator canvas. No header, no footer, no body
 * chrome. The Corbel plugin replaces the target with its embedded frame.
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
    <style>
        html, body { margin: 0; padding: 0; height: 100%; overflow: hidden; }
        .corbel-embed { width: 100vw; height: 100vh; display: block; }
        .corbel-embed > #corbelConfigurator { width: 100%; height: 100%; display: block; }
        .corbel-embed > #corbelConfigurator > iframe { width: 100%; height: 100%; border: 0; display: block; }
    </style>
    <?php wp_head(); ?>
</head>
<body <?php body_class('corbel-embed-canvas'); ?>>
<?php wp_body_open(); ?>

<main id="primary" class="corbel-embed">
    <?php \Standard\Corbel\render_configurator_placeholder(); ?>
</main>

<?php wp_footer(); ?>
</body>
</html>
