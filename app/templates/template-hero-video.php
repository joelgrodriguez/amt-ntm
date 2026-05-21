<?php
/**
 * Template Name: Video Landing
 *
 * Page template with an optional text/video hero and long-form content.
 *
 * Supported page fields:
 * - hero_video
 * - hero_title
 * - hero_description
 * - hero_content (legacy WYSIWYG)
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
    $hero = \Standard\PageTemplates\get_hero_data($post_id);
    $has_hero = $hero['has_content'] || $hero['has_video'];
?>

<main id="primary" class="bg-white">
    <?php
    if ($has_hero) {
        get_template_part('templates/parts/page-video-hero', null, [
            'hero' => $hero,
            'section_id' => 'video-landing-hero',
        ]);
    }
    ?>

    <section class="section" aria-labelledby="video-landing-content-title">
        <div class="container">
            <article id="post-<?php the_ID(); ?>" <?php post_class('grid gap-8'); ?>>
                <?php if (!$has_hero) : ?>
                    <header class="section-header-left max-w-3xl">
                        <p class="section-eyebrow"><?php esc_html_e('New Tech Machinery', 'standard'); ?></p>
                        <h1 id="video-landing-content-title" class="font-sans text-4xl md:text-5xl lg:text-6xl font-medium tracking-tight text-blue-900 leading-none">
                            <?php the_title(); ?>
                        </h1>
                    </header>
                <?php else : ?>
                    <h2 id="video-landing-content-title" class="sr-only"><?php the_title(); ?></h2>
                <?php endif; ?>

                <div class="prose prose-lg max-w-4xl prose-headings:font-medium prose-headings:tracking-tight prose-headings:text-blue-900 prose-p:text-blue-600 prose-li:text-blue-600 prose-strong:text-blue-900 prose-a:text-blue-500">
                    <?php the_content(); ?>
                </div>
            </article>
        </div>
    </section>
</main>

<?php
endwhile;

get_footer();
