<?php
/**
 * Template Name: Prose Page
 *
 * Clean long-form page template for policy, resource, and editorial pages.
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

get_header();

while (have_posts()) :
    the_post();

    $post_id = get_the_ID();
    $eyebrow = \Standard\PageTemplates\get_label($post_id, ['page_eyebrow', 'hero_eyebrow'], '');
    $summary = \Standard\PageTemplates\get_label($post_id, ['page_summary', 'hero_description'], '');
?>

<main id="primary" class="bg-white">
    <article id="post-<?php the_ID(); ?>" <?php post_class('section'); ?>>
        <div class="container grid gap-10 lg:gap-12">
            <header class="grid gap-4 max-w-3xl">
                <?php if ($eyebrow !== '') : ?>
                    <p class="section-eyebrow"><?php echo esc_html($eyebrow); ?></p>
                <?php endif; ?>

                <h1 class="font-sans text-4xl md:text-5xl lg:text-6xl font-medium tracking-tight text-blue-900 leading-none">
                    <?php the_title(); ?>
                </h1>

                <?php if ($summary !== '') : ?>
                    <p class="text-lg text-blue-600 max-w-2xl">
                        <?php echo esc_html($summary); ?>
                    </p>
                <?php endif; ?>
            </header>

            <?php if (has_post_thumbnail()) : ?>
                <figure class="featured-image border border-blue-200">
                    <?php the_post_thumbnail('large', [
                        'class' => 'w-full h-full object-cover',
                        'loading' => 'eager',
                        'fetchpriority' => 'high',
                    ]); ?>
                </figure>
            <?php endif; ?>

            <div class="grid lg:grid-cols-[minmax(0,1fr)_minmax(0,760px)_minmax(0,1fr)]">
                <div class="prose prose-lg max-w-none lg:col-start-2 prose-headings:font-medium prose-headings:tracking-tight prose-headings:text-blue-900 prose-p:text-blue-600 prose-li:text-blue-600 prose-strong:text-blue-900 prose-a:text-blue-500">
                    <?php the_content(); ?>
                </div>
            </div>
        </div>
    </article>
</main>

<?php
endwhile;

get_footer();
