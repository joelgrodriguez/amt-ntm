<?php
/**
 * Pagination component for archive, search, and templated index views.
 *
 * Three sections: "Page N of M" caption on the left, page-number strip in
 * the center (collapsed to the orientation label on <lg), and prev/next
 * controls on the right. Active page wears a 2px red bottom underline —
 * the DESIGN.md §8.8 "active tab" treatment, applied to pagination because
 * conceptually it's the same job.
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
    /**
     * Render the pagination strip for the current main query, or for a
     * supplied secondary WP_Query (used by template-articles.php and
     * template-service-hub.php which run their own queries).
     */
    public static function render(?WP_Query $query = null): void
    {
        if (is_singular() && !$query) {
            return;
        }

        global $wp_query;
        $original_query = null;

        if ($query) {
            $original_query = $wp_query;
            $wp_query       = $query;
        }

        if ($wp_query->max_num_pages <= 1) {
            if ($original_query) {
                $wp_query = $original_query;
            }
            return;
        }

        $paged = max(1, (int) $wp_query->get('paged', 1));
        $max   = (int) $wp_query->max_num_pages;
        $links = self::generate_pagination_links($paged, $max);

        ?>
        <nav class="pagination mt-12 border-t border-blue-200 pt-6" aria-label="<?php esc_attr_e('Pagination', 'standard'); ?>">
            <div class="flex items-center justify-between gap-6">

                <!-- Orientation label -->
                <p class="pagination__label font-mono font-medium uppercase tracking-widest text-caption text-blue-400 m-0 whitespace-nowrap">
                    <?php
                    printf(
                        /* translators: 1: current page number, 2: total pages. */
                        esc_html__('Page %1$s of %2$s', 'standard'),
                        '<span class="text-blue-700">' . esc_html((string) $paged) . '</span>',
                        esc_html((string) $max)
                    );
                    ?>
                </p>

                <!-- Page-number strip — desktop only -->
                <ol class="pagination__strip hidden lg:flex items-center gap-6 m-0 p-0 list-none">
                    <?php self::render_page_links($paged, $max, $links); ?>
                </ol>

                <!-- Prev / Next controls -->
                <div class="flex items-center gap-2">
                    <?php self::render_prev($paged); ?>
                    <?php self::render_next($paged, $max); ?>
                </div>

            </div>
        </nav>
        <?php

        if ($original_query) {
            $wp_query = $original_query;
        }
    }

    /**
     * Build the visible page-number set: always page 1 and max, the
     * current page, two adjacent on each side. Gaps render as ellipsis.
     *
     * @return array<int, int>
     */
    private static function generate_pagination_links(int $paged, int $max): array
    {
        $links = [1, $max, $paged];

        for ($i = 1; $i <= 2; $i++) {
            if ($paged - $i >= 1) {
                $links[] = $paged - $i;
            }
            if ($paged + $i <= $max) {
                $links[] = $paged + $i;
            }
        }

        $links = array_unique($links);
        sort($links);

        return $links;
    }

    private static function render_page_links(int $paged, int $max, array $links): void
    {
        $prev = 0;

        foreach ($links as $link) {
            if ($prev > 0 && $link - $prev > 1) {
                echo '<li class="pagination__ellipsis font-mono text-blue-300" aria-hidden="true">&middot;&middot;&middot;</li>';
            }

            if ($paged === $link) {
                printf(
                    '<li><span aria-current="page" class="pagination__current font-mono font-medium text-blue-900" style="font-size: 14px;">%s</span></li>',
                    esc_html((string) $link)
                );
            } else {
                printf(
                    '<li><a href="%s" class="pagination__link font-mono font-medium text-blue-400 no-underline transition-colors duration-150 hover:text-blue-700" style="font-size: 14px;">%s</a></li>',
                    esc_url(get_pagenum_link($link)),
                    esc_html((string) $link)
                );
            }

            $prev = $link;
        }
    }

    private static function render_prev(int $paged): void
    {
        if ($paged > 1) {
            ?>
            <a
                href="<?php echo esc_url(get_pagenum_link($paged - 1)); ?>"
                class="pagination__nav inline-flex items-center gap-2 px-3 h-11 font-mono font-medium uppercase tracking-widest text-caption text-blue-700 no-underline transition-colors duration-150 hover:text-blue-500"
                rel="prev"
                aria-label="<?php esc_attr_e('Previous page', 'standard'); ?>"
            >
                <?php icon('chevron-left', ['class' => 'w-4 h-4', 'aria-hidden' => 'true']); ?>
                <span class="hidden lg:inline"><?php esc_html_e('Previous', 'standard'); ?></span>
            </a>
            <?php
        } else {
            ?>
            <span class="pagination__nav pagination__nav--disabled inline-flex items-center gap-2 px-3 h-11 font-mono font-medium uppercase tracking-widest text-caption text-blue-300" aria-disabled="true">
                <?php icon('chevron-left', ['class' => 'w-4 h-4', 'aria-hidden' => 'true']); ?>
                <span class="hidden lg:inline"><?php esc_html_e('Previous', 'standard'); ?></span>
            </span>
            <?php
        }
    }

    private static function render_next(int $paged, int $max): void
    {
        if ($paged < $max) {
            ?>
            <a
                href="<?php echo esc_url(get_pagenum_link($paged + 1)); ?>"
                class="pagination__nav inline-flex items-center gap-2 px-3 h-11 font-mono font-medium uppercase tracking-widest text-caption text-blue-700 no-underline transition-colors duration-150 hover:text-blue-500"
                rel="next"
                aria-label="<?php esc_attr_e('Next page', 'standard'); ?>"
            >
                <span class="hidden lg:inline"><?php esc_html_e('Next', 'standard'); ?></span>
                <?php icon('chevron-right', ['class' => 'w-4 h-4', 'aria-hidden' => 'true']); ?>
            </a>
            <?php
        } else {
            ?>
            <span class="pagination__nav pagination__nav--disabled inline-flex items-center gap-2 px-3 h-11 font-mono font-medium uppercase tracking-widest text-caption text-blue-300" aria-disabled="true">
                <span class="hidden lg:inline"><?php esc_html_e('Next', 'standard'); ?></span>
                <?php icon('chevron-right', ['class' => 'w-4 h-4', 'aria-hidden' => 'true']); ?>
            </span>
            <?php
        }
    }
}
