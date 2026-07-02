<?php
/**
 * Footprints — drawing library grid.
 *
 * Flat grid of every published footprint post (plan-view spec-sheet
 * drawings). Mirrors the Profiles landing grid but skips the filter
 * sidebar — footprints aren't split by category, so a single flat
 * grid is the whole library. Cards reuse the shared card-profile
 * partial, same as the Profiles landing.
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$footprints_query = new \WP_Query([
    'post_type'              => 'footprint',
    'post_status'            => 'publish',
    'posts_per_page'         => -1,
    'orderby'                => 'title',
    'order'                  => 'ASC',
    'update_post_meta_cache' => false,
]);
?>

<section class="bg-white pt-12 pb-24 lg:pt-16 lg:pb-32" aria-label="<?php esc_attr_e('Footprint drawing library', 'standard'); ?>">
    <div class="container">

        <?php if ($footprints_query->have_posts()) : ?>
            <ul class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-x-4 gap-y-8 list-none p-0 m-0">
                <?php while ($footprints_query->have_posts()) : $footprints_query->the_post(); ?>
                    <li>
                        <?php get_template_part('templates/parts/card-profile', null, [
                            'profile' => get_post(),
                            'context' => 'grid',
                        ]); ?>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else : ?>
            <p class="font-sans text-blue-600">
                <?php esc_html_e('No footprints published yet.', 'standard'); ?>
            </p>
        <?php endif; ?>

        <?php wp_reset_postdata(); ?>

    </div>
</section>
