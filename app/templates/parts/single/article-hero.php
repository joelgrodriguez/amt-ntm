<?php
/**
 * Single-article hero (single.php only).
 *
 * Lives inside the same .container as the article body so the page reads
 * as one full-width column with a fixed-width TOC rail. No background
 * chrome — the surrounding section carries a fading dot-grid instead.
 * One category in blue above the title, no breadcrumb-style category
 * list, no featured image.
 *
 * Renders the optional excerpt + meta (date, author, read time) so the
 * top of the article still carries the editorial signals that used to
 * live in post-hero.php.
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\PostTypes\get_primary_category;

$primary_category = get_primary_category((int) get_the_ID());
$has_excerpt      = has_excerpt();
$word_count       = str_word_count(wp_strip_all_tags(get_the_content()));
$reading_time     = max(1, (int) ceil($word_count / 220));
?>

<header class="grid gap-5 lg:gap-6 pt-8 lg:pt-12 pb-8 lg:pb-10">
    <?php if ($primary_category instanceof \WP_Term) : ?>
        <div>
            <a href="<?php echo esc_url(get_category_link($primary_category->term_id)); ?>"
               class="font-mono uppercase tracking-widest text-caption text-blue-500 no-underline hover:text-blue-700 transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2">
                <?php echo esc_html($primary_category->name); ?>
            </a>
        </div>
    <?php endif; ?>

    <?php the_title('<h1 class="font-sans font-semibold text-heading lg:text-heading-lg text-blue-900 leading-tight tracking-tight m-0">', '</h1>'); ?>

    <?php if ($has_excerpt) : ?>
        <p class="text-blue-600 text-base lg:text-lg leading-relaxed m-0 max-w-2xl">
            <?php echo esc_html(get_the_excerpt()); ?>
        </p>
    <?php endif; ?>

    <div class="flex flex-wrap items-center gap-x-6 gap-y-2 text-blue-600 font-mono text-sm pt-1">
        <span class="flex items-center gap-2">
            <?php icon('calendar', ['class' => 'w-4 h-4', 'aria-hidden' => 'true']); ?>
            <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                <?php echo esc_html(get_the_date('j F Y')); ?>
            </time>
        </span>
        <span class="flex items-center gap-2">
            <?php icon('user', ['class' => 'w-4 h-4', 'aria-hidden' => 'true']); ?>
            <span><?php echo esc_html(get_the_author_meta('display_name')); ?></span>
        </span>
        <span class="text-blue-400" aria-hidden="true">/</span>
        <span><?php
            /* translators: %d minutes of reading time. */
            printf(esc_html(_n('%d min read', '%d min read', $reading_time, 'standard')), $reading_time);
        ?></span>
    </div>
</header>
