<?php
/**
 * Default page template.
 *
 * Used for any WordPress page that does not match a more specific
 * template (template-*.php or page-{slug}.php). Ships a title-block
 * hero on bg-blue-50 with dot-grid pattern, a single-column prose body,
 * and a footer seam with the modified date + parent backlink. No
 * featured image hero by design; pages that need an image hero should
 * use a dedicated template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-page
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<?php while (have_posts()) : the_post(); ?>
    <?php
    $parent_id      = wp_get_post_parent_id(get_the_ID());
    $parent_title   = $parent_id ? get_the_title($parent_id) : '';
    $parent_link    = $parent_id ? get_permalink($parent_id) : '';
    $eyebrow_label  = $parent_title !== '' ? $parent_title : __('Information', 'standard');
    $has_excerpt    = has_excerpt();
    ?>

    <main id="primary">
        <header class="pattern-dot-grid bg-blue-50 border-b border-blue-200">
            <div class="container pt-6 lg:pt-12 pb-6 lg:pb-12">
                <div class="grid gap-5 lg:gap-6 max-w-3xl">
                    <div class="font-mono uppercase tracking-widest text-caption text-blue-700">
                        <?php echo esc_html(sprintf('%s · %s', __('Page', 'standard'), $eyebrow_label)); ?>
                    </div>

                    <?php the_title('<h1 class="font-mono font-medium text-heading lg:text-heading-lg text-blue-900 leading-tight tracking-tight m-0">', '</h1>'); ?>

                    <?php if ($has_excerpt) : ?>
                        <p class="text-blue-600 text-base lg:text-lg leading-relaxed m-0 max-w-2xl">
                            <?php echo esc_html(get_the_excerpt()); ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </header>

        <article id="post-<?php the_ID(); ?>" <?php post_class('section'); ?>>
            <div class="container">
                <div class="prose max-w-3xl mx-auto">
                    <?php the_content(); ?>
                </div>
            </div>
        </article>

        <?php if ($parent_id || get_the_modified_date('U') !== get_the_date('U')) : ?>
            <footer class="bg-blue-50 border-t border-blue-200">
                <div class="container py-8 lg:py-10">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between font-mono text-caption uppercase tracking-widest text-blue-600">
                        <?php if (get_the_modified_date('U') !== get_the_date('U')) : ?>
                            <span>
                                <?php
                                printf(
                                    /* translators: %s: last modified date. */
                                    esc_html__('Updated %s', 'standard'),
                                    '<time datetime="' . esc_attr(get_the_modified_date('c')) . '">' . esc_html(get_the_modified_date('j M Y')) . '</time>'
                                );
                                ?>
                            </span>
                        <?php else : ?>
                            <span aria-hidden="true"></span>
                        <?php endif; ?>

                        <?php if ($parent_id) : ?>
                            <a href="<?php echo esc_url($parent_link); ?>"
                               class="inline-flex items-center gap-2 text-blue-700 no-underline hover:text-blue-500 transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2">
                                <span aria-hidden="true">←</span>
                                <?php
                                printf(
                                    /* translators: %s: parent page title. */
                                    esc_html__('Back to %s', 'standard'),
                                    esc_html($parent_title)
                                );
                                ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </footer>
        <?php endif; ?>
    </main>
<?php endwhile; ?>

<?php
get_footer();
