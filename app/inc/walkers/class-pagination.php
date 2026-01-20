<?php
/**
 * Custom pagination component for archive pages.
 *
 * Renders accessible pagination with previous/next links, page numbers,
 * and ellipsis indicators. Uses SVG icons for navigation arrows.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard\Walkers;

if (!defined('ABSPATH')) {
    exit;
}

use WP_Query;

class Pagination
{
    public const SVG_PREV = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4" aria-hidden="true">
        <path fill-rule="evenodd" d="M9.78 4.22a.75.75 0 0 1 0 1.06L7.06 8l2.72 2.72a.75.75 0 1 1-1.06 1.06L5.47 8.53a.75.75 0 0 1 0-1.06l3.25-3.25a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd" />
    </svg>';

    public const SVG_NEXT = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4" aria-hidden="true">
        <path fill-rule="evenodd" d="M6.22 4.22a.75.75 0 0 1 1.06 0l3.25 3.25a.75.75 0 0 1 0 1.06l-3.25 3.25a.75.75 0 0 1-1.06-1.06L8.94 8 6.22 5.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
    </svg>';

    public const SVG_ELLIPSIS = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4" aria-hidden="true">
        <path d="M2 8a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0ZM6.5 8a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0ZM12.5 6.5a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3Z" />
    </svg>';

    public static function render(?WP_Query $query = null): void
    {
        if (is_singular()) {
            return;
        }

        global $wp_query;
        $original_query = null;

        if ($query) {
            $original_query = $wp_query;
            $wp_query = $query;
        }

        if ($wp_query->max_num_pages <= 1) {
            if ($original_query) {
                $wp_query = $original_query;
            }
            return;
        }

        $paged = max(1, absint($wp_query->get('paged', 1)));
        $max = (int) $wp_query->max_num_pages;
        $links = self::generate_pagination_links($paged, $max);

        echo '<nav aria-label="Page navigation">';
        echo '<ul class="mt-12 border-t border-slate-200 py-6 flex items-center justify-center gap-1">';

        self::render_previous_link($paged);
        self::render_page_links($paged, $max, $links);
        self::render_next_link($paged, $max);

        echo '</ul></nav>';

        if ($original_query) {
            $wp_query = $original_query;
        }
    }

    private static function generate_pagination_links(int $paged, int $max): array
    {
        $links = [$paged];

        // Always include first and last pages
        $links[] = 1;
        $links[] = $max;

        // Add surrounding pages
        if ($paged >= 2) {
            $links[] = $paged - 1;
        }
        if ($paged >= 3) {
            $links[] = $paged - 2;
        }
        if ($paged + 1 <= $max) {
            $links[] = $paged + 1;
        }
        if ($paged + 2 <= $max) {
            $links[] = $paged + 2;
        }

        sort($links);
        return array_unique($links);
    }

    private static function render_previous_link(int $paged): void
    {
        if ($paged > 1) {
            printf(
                '<li><a href="%s" class="flex items-center justify-center w-10 h-10  text-slate-500 hover:bg-slate-100 hover:text-slate-900 transition-colors" aria-label="Previous page">%s</a></li>',
                esc_url(get_pagenum_link($paged - 1)),
                self::SVG_PREV
            );
        } else {
            printf(
                '<li><span class="flex items-center justify-center w-10 h-10  text-slate-300 cursor-not-allowed" aria-hidden="true">%s</span></li>',
                self::SVG_PREV
            );
        }
    }

    private static function render_page_links(int $paged, int $max, array $links): void
    {
        $prev = 0;

        foreach ($links as $link) {
            // Render ellipsis if there's a gap
            if ($prev > 0 && $link - $prev > 1) {
                printf(
                    '<li><span class="flex items-center justify-center w-10 h-10 text-slate-400" aria-hidden="true">%s</span></li>',
                    self::SVG_ELLIPSIS
                );
            }

            if ($paged === $link) {
                printf(
                    '<li><span aria-current="page" class="flex items-center justify-center w-10 h-10  bg-primary text-white font-medium">%s</span></li>',
                    esc_html($link)
                );
            } else {
                printf(
                    '<li><a href="%s" class="flex items-center justify-center w-10 h-10  text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-colors">%s</a></li>',
                    esc_url(get_pagenum_link($link)),
                    esc_html($link)
                );
            }

            $prev = $link;
        }
    }

    private static function render_next_link(int $paged, int $max): void
    {
        if ($paged < $max) {
            printf(
                '<li><a href="%s" class="flex items-center justify-center w-10 h-10  text-slate-500 hover:bg-slate-100 hover:text-slate-900 transition-colors" aria-label="Next page">%s</a></li>',
                esc_url(get_pagenum_link($paged + 1)),
                self::SVG_NEXT
            );
        } else {
            printf(
                '<li><span class="flex items-center justify-center w-10 h-10  text-slate-300 cursor-not-allowed" aria-hidden="true">%s</span></li>',
                self::SVG_NEXT
            );
        }
    }
}
