<?php
/**
 * Custom walker for mobile navigation menus.
 *
 * Extends Walker_Nav_Menu to output mobile-optimized menu markup
 * with full-width items, icons for submenus, and proper hierarchy.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard\Walkers;

if (!defined('ABSPATH')) {
    exit;
}

use Walker_Nav_Menu;

class Mobile_Nav_Walker extends Walker_Nav_Menu
{
    /**
     * Starts the element output.
     */
    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0): void
    {
        $classes = empty($item->classes) ? [] : (array) $item->classes;
        $has_children = in_array('menu-item-has-children', $classes);

        $output .= '<li class="' . esc_attr(implode(' ', $classes)) . '">';

        $link_classes = 'flex items-center justify-between px-4 py-3 text-sm text-slate-900 hover:bg-slate-50 transition-colors';

        if ($depth > 0) {
            $link_classes = 'block px-4 py-3 pl-8 text-sm text-slate-600 hover:bg-slate-50 transition-colors';
        }

        $output .= '<a href="' . esc_url($item->url) . '" class="' . $link_classes . '">';
        $output .= '<span>' . esc_html($item->title) . '</span>';

        if ($has_children && $depth === 0) {
            $output .= '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4 text-slate-400" aria-hidden="true">';
            $output .= '<path fill-rule="evenodd" d="M6.22 4.22a.75.75 0 0 1 1.06 0l3.25 3.25a.75.75 0 0 1 0 1.06l-3.25 3.25a.75.75 0 0 1-1.06-1.06L8.94 8 6.22 5.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />';
            $output .= '</svg>';
        }

        $output .= '</a>';
    }

    /**
     * Ends the element output.
     */
    public function end_el(&$output, $item, $depth = 0, $args = null): void
    {
        $output .= '</li>';
    }

    /**
     * Starts the list before the elements are added.
     */
    public function start_lvl(&$output, $depth = 0, $args = null): void
    {
        $output .= '<ul class="bg-slate-50 border-t border-slate-200">';
    }

    /**
     * Ends the list after the elements are added.
     */
    public function end_lvl(&$output, $depth = 0, $args = null): void
    {
        $output .= '</ul>';
    }
}
