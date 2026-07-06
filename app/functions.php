<?php
/**
 * Theme functions and definitions.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard;

if (!defined('ABSPATH')) {
    exit;
}
define('THEME_VERSION', '1.0.0');
define('THEME_DIR', get_template_directory());
define('THEME_URI', get_template_directory_uri());

/**
 * Load theme includes.
 */
$theme_includes = [
    'inc/urls.php',
    'inc/vite.php',
    'inc/setup.php',
    'inc/search.php',
    'inc/filters.php',
    'inc/mobile-nav.php',
    'inc/desktop-nav.php',
    'inc/sidebars.php',
    'inc/fonts.php',
    'inc/cleanup.php',
    'inc/icons.php',
    'inc/images.php',
    'inc/grid.php',
    'inc/video.php',
    'inc/hubspot.php',
    'inc/page-templates.php',
    'inc/content-taxonomy.php',
    'inc/breadcrumbs.php',
    'inc/related-posts.php',
    'inc/mega-menu.php',
    'inc/knowledgebase.php',
    'inc/service-hub.php',
    'inc/woo/setup.php',
    'inc/woo/cache.php',
    'inc/woo/catalog.php',
    'inc/woo/accessories.php',
    'inc/woo/accessory-tag-map.php',
    'inc/machine-product-data.php',
    'inc/woo/machine-template.php',
    'inc/machines.php',
    'inc/learning-center.php',
    'inc/machines-data.php',
    'inc/service-hub-machines.php',
    'inc/contact-data.php',
    'inc/finance-data.php',
    'inc/accessories-data.php',
    'inc/machine-schema.php',
    'inc/seo.php',
    'inc/post-types.php',
    'inc/post-archive.php',
    'inc/pdf-attachment.php',
    'inc/footprints.php',
    'inc/walkers/class-pagination.php',
    'inc/walkers/class-primary-nav-walker.php',
];

foreach ($theme_includes as $file) {
    $filepath = THEME_DIR . '/' . $file;
    if (file_exists($filepath)) {
        require_once $filepath;
    }
}
