<?php
/**
 * Learning Center Section Template Part
 *
 * Highlights the Rollforming Learning Center with latest content.
 * Displays recent posts from multiple post types using card-post component.
 *
 * @package Standard
 *
 * @usage Front Page (front-page.php)
 *
 * @param string       $eyebrow    Optional. Eyebrow text.
 * @param string       $title      Optional. Section title.
 * @param string       $subtitle   Optional. Section subtitle.
 * @param int          $post_count Optional. Number of posts to show. Default 4.
 * @param string|array $post_type  Optional. Post type(s) to query.
 * @param string       $cta_url       Optional. CTA button URL.
 * @param string       $cta_text      Optional. CTA button text.
 * @param string       $category_slug Optional. WordPress category slug to filter by.
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\LearningCenter\get_latest_query;
// Callers pass DATA only (title, subtitle, posts, CTA, category). The
// visual treatment — left-aligned header, no eyebrow dot, max-w-3xl lede —
// is owned here so every Learning Center section reads identically.
$defaults = [
    'title'      => __('The Rollforming Learning Center', 'standard'),
    'subtitle'   => __('Expert guides, tips, and resources to help you get the most from your equipment.', 'standard'),
    'post_count' => 4,
    'post_type'  => ['post', 'video', 'resource', 'download'],
    'cta_url'    => '/learning-center/',
    'cta_text'      => __('View All Resources', 'standard'),
    'category_slug' => '',
];

$args = wp_parse_args($args ?? [], $defaults);

$post_count = (int) $args['post_count'];
if ($post_count < 1) {
    return;
}

$query = get_latest_query($post_count, $args['post_type'], (string) $args['category_slug']);

if (!$query->have_posts()) {
    return;
}
?>

<section class="section pattern-dot-grid" aria-labelledby="learning-center-title">
    <div class="container section-content">

        <?php // Header left, "view all" link bottom-right on the opposite side.
        // Mobile: link stacks under the header. md+: side-by-side, baseline-aligned. ?>
        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between md:gap-6">
            <?php get_template_part('templates/parts/section-header', null, [
                'id'             => 'learning-center-title',
                'align'          => 'left',
                'title'          => $args['title'],
                'lede'           => $args['subtitle'],
                // max-w-3xl keeps the short subtitle off a second line / orphan.
                'lede_max_width' => 'max-w-3xl',
            ]); ?>

            <?php if ($args['cta_url']) : ?>
                <a href="<?php echo esc_url(\Standard\Url\internal($args['cta_url'])); ?>" class="inline-flex items-center gap-1 text-sm font-medium text-blue-500 hover:text-blue-500/80 transition-colors no-underline shrink-0">
                    <?php echo esc_html($args['cta_text']); ?>
                    <?php icon('arrow-right', ['class' => 'w-4 h-4']); ?>
                </a>
            <?php endif; ?>
        </div>

        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            <?php while ($query->have_posts()) : $query->the_post(); ?>
                <?php get_template_part('templates/parts/card', 'post'); ?>
            <?php endwhile; ?>
        </div>
        <?php wp_reset_postdata(); ?>

    </div>
</section>
