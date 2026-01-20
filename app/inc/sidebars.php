<?php
/**
 * Register widget areas.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard;

if (!defined('ABSPATH')) {
    exit;
}

const SIDEBARS = [
    [
        'name'          => 'Sidebar',
        'id'            => 'sidebar-1',
        'description'   => 'Add widgets here.',
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ],
    [
        'name'          => 'Footer',
        'id'            => 'footer-1',
        'description'   => 'Footer widget area.',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ],
];

add_action('widgets_init', function (): void {
    foreach (SIDEBARS as $sidebar) {
        register_sidebar($sidebar);
    }
});
