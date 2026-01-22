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
 * @param string       $cta_url    Optional. CTA button URL.
 * @param string       $cta_text   Optional. CTA button text.
 */

declare(strict_types=1);

// Default values - can be overridden via $args when using get_template_part
$defaults = [
    'eyebrow'    => __('Learning Center', 'standard'),
    'title'      => __('The Rollforming Learning Center', 'standard'),
    'subtitle'   => __('Expert guides, tips, and resources to help you get the most from your equipment.', 'standard'),
    'post_count' => 4,
    'post_type'  => ['post', 'video', 'resource', 'download'],
    'cta_url'    => '/learning-center/',
    'cta_text'   => __('View All Resources', 'standard'),
];

$args = wp_parse_args($args ?? [], $defaults);

// Query latest posts from multiple post types
$query = new WP_Query([
    'post_type'      => $args['post_type'],
    'posts_per_page' => $args['post_count'],
    'post_status'    => 'publish',
    'orderby'        => 'date',
    'order'          => 'DESC',
    'no_found_rows'  => true,
]);

if (!$query->have_posts()) {
    return;
}
?>

<section class="py-16 bg-slate-50 md:py-20 lg:py-24" aria-labelledby="learning-center-title">
    <div class="container">

        <!-- Section Header -->
        <div class="text-center mb-12 lg:mb-16">
            <p class="text-sm font-semibold uppercase tracking-wider text-secondary mb-2">
                <?php echo esc_html($args['eyebrow']); ?>
            </p>
            <div class="w-12 h-1 bg-secondary mx-auto mb-6"></div>

            <h2 id="learning-center-title" class="text-3xl font-bold text-slate-900 mb-4 md:text-4xl lg:text-5xl">
                <?php echo esc_html($args['title']); ?>
            </h2>
            <p class="text-lg text-slate-600 max-w-2xl mx-auto">
                <?php echo esc_html($args['subtitle']); ?>
            </p>
        </div>

        <!-- Posts Grid -->
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            <?php while ($query->have_posts()) : $query->the_post(); ?>
                <?php get_template_part('templates/parts/card', 'post'); ?>
            <?php endwhile; ?>
        </div>
        <?php wp_reset_postdata(); ?>

        <!-- CTA -->
        <?php if ($args['cta_url']) : ?>
            <div class="text-center mt-12">
                <a href="<?php echo esc_url($args['cta_url']); ?>" class="btn btn-primary">
                    <?php echo esc_html($args['cta_text']); ?>
                    <?php icon('arrow--right', ['class' => 'w-5 h-5 ml-2']); ?>
                </a>
            </div>
        <?php endif; ?>

    </div>
</section>
