<?php
/**
 * Template part for displaying search results.
 *
 * Displays individual search result items with title, date,
 * post type indicator, and excerpt.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Standard
 */

if (!defined('ABSPATH')) {
    exit;
}

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('bg-white border border-blue-200 p-6'); ?>>
    <header class="mb-4">
        <?php the_title(sprintf('<h2 class="text-xl font-medium mb-2"><a href="%s" class="text-blue-900 no-underline hover:text-blue-500">', esc_url(get_permalink())), '</a></h2>'); ?>

        <div class="text-sm text-blue-500">
            <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                <?php echo esc_html(get_the_date()); ?>
            </time>
            <span class="mx-2">&middot;</span>
            <span><?php echo esc_html(get_post_type_object(get_post_type())->labels->singular_name); ?></span>
        </div>
    </header>

    <div class="text-blue-600">
        <?php the_excerpt(); ?>
    </div>
</article>
