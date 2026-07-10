<?php
/**
 * The template for displaying single posts.
 *
 * Displays a single blog post with full content, meta, TOC, and post navigation.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

get_header();

/**
 * Minimum real <h2> headings a post needs before we render the TOC rail.
 * Must match HEADING_SELECTORS + the length<3 guard in
 * resources/js/modules/TableOfContents.js. Below this we skip the aside
 * entirely so the layout collapses to a single column instead of leaving a
 * reserved-but-empty 240px TOC gutter beside the content.
 */
const STANDARD_TOC_MIN_HEADINGS = 3;
?>

<main id="primary" class="pattern-dot-grid pb-6 lg:pb-12">
    <?php while (have_posts()) : the_post();
        // Count real H2s in the rendered content to decide whether the TOC
        // rail earns its column. Bold-<p> pseudo-headings don't count — which
        // is the whole point: a post with fake headings gets no empty rail.
        $rendered_content = apply_filters('the_content', get_the_content());
        $h2_count = preg_match_all('/<h2[\s>]/i', (string) $rendered_content);
        $has_toc  = $h2_count >= STANDARD_TOC_MIN_HEADINGS;
    ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class('grid gap-6 lg:gap-12'); ?>>

            <div class="container">
                <?php get_template_part('templates/parts/single/article-hero'); ?>

                <div class="article-layout<?php echo $has_toc ? '' : ' article-layout--no-toc'; ?>">
                    <?php if ($has_toc) : ?>
                        <aside id="table-of-contents" class="hidden lg:block" aria-label="<?php esc_attr_e('Table of Contents', 'standard'); ?>">
                            <nav class="toc sticky top-24">
                                <p class="toc__title"><?php esc_html_e('On this page', 'standard'); ?></p>
                                <ol id="toc-list" class="toc__list"></ol>
                            </nav>
                        </aside>
                    <?php endif; ?>

                    <div class="min-w-0">
                        <div class="prose prose-lg max-w-full" data-toc-content>
                            <?php echo $rendered_content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- core the_content output ?>
                        </div>

                        <?php get_template_part('templates/parts/disclaimer'); ?>
                    </div>
                </div>
            </div>

            <div class="container">
                <?php get_template_part('templates/parts/post-navigation'); ?>
            </div>
            <div class="container">
                <?php get_template_part('templates/parts/related-posts'); ?>
            </div>
        </article>

    <?php endwhile; ?>
</main>

<?php get_template_part('templates/parts/cta/subscribe'); ?>

<?php
get_footer();
