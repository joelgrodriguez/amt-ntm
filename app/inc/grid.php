<?php
/**
 * Grid presentation helpers.
 *
 * These helpers return Tailwind class strings for reusable grid layouts.
 * Keep visual/layout calculations here so content/data modules stay focused
 * on returning machine and page content.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard\Grid;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get border classes for a card in a responsive grid.
 *
 * Computes right and bottom border classes per breakpoint
 * so cards form a clean grid with dividers between them.
 *
 * @param int $idx       Zero-based card index.
 * @param int $total     Total cards in this row.
 * @param int $cols      Number of columns at lg breakpoint.
 * @return string        Space-separated Tailwind border classes.
 */
function get_card_border_classes(int $idx, int $total, int $cols): string {
    $is_last_sm = ($idx % 2 === 1) || ($idx === $total - 1);
    $is_last_lg = (($idx + 1) % $cols === 0) || ($idx === $total - 1);

    $classes = 'border-b border-blue-200';
    $classes .= $is_last_sm ? '' : ' sm:border-r';
    $classes .= $is_last_lg ? ' lg:border-r-0' : ' lg:border-r';

    return $classes;
}

/**
 * Tailwind class for an lg: grid column count.
 *
 * Returns explicit strings so Tailwind v4's source scanner sees every
 * possible value at build time. Use this instead of interpolating
 * `lg:grid-cols-{$n}` into a class attribute.
 *
 * @param int $cols Column count (1–4).
 */
function get_lg_grid_cols_class(int $cols): string {
    return [
        1 => 'lg:grid-cols-1',
        2 => 'lg:grid-cols-2',
        3 => 'lg:grid-cols-3',
        4 => 'lg:grid-cols-4',
    ][$cols] ?? 'lg:grid-cols-1';
}

/**
 * Tailwind class for an lg: column start position.
 *
 * @param int $col_start 1-based column start (1–4).
 */
function get_lg_col_start_class(int $col_start): string {
    return [
        1 => 'lg:col-start-1',
        2 => 'lg:col-start-2',
        3 => 'lg:col-start-3',
        4 => 'lg:col-start-4',
    ][$col_start] ?? 'lg:col-start-1';
}

/**
 * Get border classes for overflow (centered) row cards.
 *
 * @param int $idx   Zero-based index within the overflow row.
 * @param int $total Total cards in the overflow row.
 * @return string    Space-separated Tailwind border classes.
 */
function get_overflow_border_classes(int $idx, int $total): string {
    $is_last = ($idx === $total - 1);

    $classes = 'border-b border-blue-200';
    $classes .= ($idx % 2 === 0 && !$is_last) ? ' sm:border-r' : '';
    $classes .= $is_last ? '' : ' lg:border-r';

    return $classes;
}
