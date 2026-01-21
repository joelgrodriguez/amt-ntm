<?php
/**
 * Custom walker for primary navigation menus.
 *
 * Extends Walker_Nav_Menu to output mega menu markup
 * with container-wrapped submenus.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard\Walkers;

if (!defined('ABSPATH')) {
    exit;
}

use Walker_Nav_Menu;

class Primary_Nav_Walker extends Walker_Nav_Menu
{
    /**
     * Starts the list before the elements are added.
     */
    public function start_lvl(&$output, $depth = 0, $args = null): void
    {
        if ($depth === 0) {
            $output .= '<div class="sub-menu"><ul class="mx-auto lg:container">';
        } else {
            $output .= '<ul class="sub-menu">';
        }
    }

    /**
     * Ends the list after the elements are added.
     */
    public function end_lvl(&$output, $depth = 0, $args = null): void
    {
        if ($depth === 0) {
            $output .= '</ul></div>';
        } else {
            $output .= '</ul>';
        }
    }
}
