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
