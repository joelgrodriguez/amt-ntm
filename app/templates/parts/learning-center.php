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

// Default values - can be overridden via $args when using get_template_part
$defaults = [
    'eyebrow'    => '',
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

        <!-- Section Header -->
        <?php get_template_part('templates/parts/section-header', null, [
            'id'          => 'learning-center-title',
            'align'       => 'center',
            'eyebrow'     => $args['eyebrow'],
            'eyebrow_dot' => false,
            'title'       => $args['title'],
            'lede'        => $args['subtitle'],
        ]); ?>

        <!-- Posts Grid -->
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            <?php while ($query->have_posts()) : $query->the_post(); ?>
                <?php get_template_part('templates/parts/card', 'post'); ?>
            <?php endwhile; ?>
        </div>
        <?php wp_reset_postdata(); ?>

        <!-- CTA -->
        <?php if ($args['cta_url']) : ?>
            <div class="text-center">
                <a href="<?php echo esc_url(\Standard\Url\internal($args['cta_url'])); ?>" class="btn btn-primary">
                    <?php echo esc_html($args['cta_text']); ?>
                    <?php icon('arrow-right', ['class' => 'w-5 h-5']); ?>
                </a>
            </div>
        <?php endif; ?>

    </div>
</section>
