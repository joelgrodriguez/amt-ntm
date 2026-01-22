<?php
/**
 * Theme functions and definitions.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard;

// Theme version
define('THEME_VERSION', '1.0.0');
define('THEME_DIR', get_template_directory());
define('THEME_URI', get_template_directory_uri());

/**
 * Load theme includes.
 */
$theme_includes = [
    'inc/vite.php',
    'inc/setup.php',
    'inc/sidebars.php',
    'inc/fonts.php',
    'inc/icons.php',
    'inc/related-posts.php',
    'inc/machines.php',
    'inc/products.php',
    'inc/walkers/class-pagination.php',
    'inc/walkers/class-mobile-nav-walker.php',
    'inc/walkers/class-primary-nav-walker.php',
];

foreach ($theme_includes as $file) {
    $filepath = THEME_DIR . '/' . $file;
    if (file_exists($filepath)) {
        require_once $filepath;
    }
}
