<?php
/**
 * The template for displaying single manual posts.
 *
 * Dashboard-style layout with filter sidebar.
 * Manuals are categorized by machine type and tagged with specific machines.
 * Categories: Seamless Gutter Machines, Roof and Wall Panel Machines, Accessories
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$content = [
    'badge'              => __('Manual', 'standard'),
    'filter_type'        => __('Filter by Type', 'standard'),
    'filter_machine'     => __('Filter by Machine', 'standard'),
    'view_all'           => __('View All Manuals', 'standard'),
    'related_machines'   => __('Related NTM Machines', 'standard'),
    'manuals_available'  => __('%d manuals available', 'standard'),
    'no_machines'        => __('No machines tagged for this manual.', 'standard'),
    'add_tags_hint'      => __('Add machine tags to display related equipment.', 'standard'),
];

get_header();

// Get manual categories and machine tags
$categories = get_the_terms(get_the_ID(), 'category');
$machine_tags = get_the_tags();
$manual_filter_post_ids = get_posts([
    'post_type'              => 'manual',
    'post_status'            => 'publish',
    'posts_per_page'         => 500,
    'fields'                 => 'ids',
    'no_found_rows'          => true,
    'update_post_meta_cache' => false,
    'update_post_term_cache' => false,
]);
?>

<main id="primary" class="pattern-dot-grid gradient-fade-bottom-sm py-6 lg:py-12">
    <?php while (have_posts()) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class('grid gap-6 lg:gap-12'); ?>>

            <!-- Header -->
            <header class="container">
                <div class="grid gap-4 justify-items-start">
                    <span class="badge inline"><?php echo esc_html($content['badge']); ?></span>
                    <?php the_title('<h1 class="text-3xl md:text-4xl lg:text-5xl font-medium font-mono">', '</h1>'); ?>
                </div>
            </header>

            <!-- Two-column layout: Filter Sidebar + Content -->
            <div class="container lg:grid lg:grid-cols-[240px_1fr] lg:gap-12">

                <!-- Filter Sidebar -->
                <aside class="hidden lg:block border-r border-blue-200 pr-8">
                    <nav class="sticky top-16 grid gap-8">

                        <!-- Filter by Category (Machine Type) -->
                        <div>
                            <h3 class="text-sm font-medium text-blue-900 mb-4 flex items-center gap-2">
                                <?php icon('filter', ['class' => 'w-4 h-4']); ?>
                                <?php echo esc_html($content['filter_type']); ?>
                            </h3>
                            <ul class="grid gap-1 border-l border-blue-200">
                                <?php
                                $manual_categories = get_terms([
                                    'taxonomy' => 'category',
                                    'hide_empty' => true,
                                    'object_ids' => $manual_filter_post_ids,
                                ]);

                                if (!empty($manual_categories) && !is_wp_error($manual_categories)) :
                                    foreach ($manual_categories as $cat) :
                                        $is_active = $categories && in_array($cat->term_id, wp_list_pluck($categories, 'term_id'));
                                ?>
                                    <li>
                                        <a href="<?php echo esc_url(get_term_link($cat)); ?>" class="flex items-center justify-between text-sm py-2 pl-4 border-l-2 -ml-px <?php echo $is_active ? 'border-blue-500 text-blue-500 font-medium' : 'border-transparent text-blue-600 hover:text-blue-900 hover:border-blue-300'; ?>">
                                            <span><?php echo esc_html($cat->name); ?></span>
                                            <span class="text-xs text-blue-400"><?php echo esc_html($cat->count); ?></span>
                                        </a>
                                    </li>
                                <?php
                                    endforeach;
                                endif;
                                ?>
                            </ul>
                        </div>

                        <!-- Filter by Machine -->
                        <div>
                            <h3 class="text-sm font-medium text-blue-900 mb-4 flex items-center gap-2">
                                <?php icon('settings', ['class' => 'w-4 h-4']); ?>
                                <?php echo esc_html($content['filter_machine']); ?>
                            </h3>
                            <ul class="grid gap-1 border-l border-blue-200">
                                <?php
                                $machine_terms = get_terms([
                                    'taxonomy' => 'post_tag',
                                    'hide_empty' => true,
                                    'object_ids' => $manual_filter_post_ids,
                                ]);

                                if (!empty($machine_terms) && !is_wp_error($machine_terms)) :
                                    foreach ($machine_terms as $machine) :
                                        $is_active = $machine_tags && in_array($machine->term_id, wp_list_pluck($machine_tags, 'term_id'));
                                ?>
                                    <li>
                                        <a href="<?php echo esc_url(get_term_link($machine)); ?>" class="flex items-center justify-between text-sm py-2 pl-4 border-l-2 -ml-px <?php echo $is_active ? 'border-blue-500 text-blue-500 font-medium' : 'border-transparent text-blue-600 hover:text-blue-900 hover:border-blue-300'; ?>">
                                            <span><?php echo esc_html($machine->name); ?></span>
                                            <span class="text-xs text-blue-400"><?php echo esc_html($machine->count); ?></span>
                                        </a>
                                    </li>
                                <?php
                                    endforeach;
                                endif;
                                ?>
                            </ul>
                        </div>

                        <!-- All Manuals Link -->
                        <a href="<?php echo esc_url(get_post_type_archive_link('manual')); ?>" class="flex items-center gap-2 text-sm font-medium text-blue-500 hover:underline">
                            <?php icon('arrow-left', ['class' => 'w-4 h-4']); ?>
                            <?php echo esc_html($content['view_all']); ?>
                        </a>

                    </nav>
                </aside>

                <!-- Main Content -->
                <div class="grid gap-8">

                    <!-- Manual Content (PDF embedded via Gutenberg) -->
                    <section>
                        <div class="prose prose-lg max-w-full">
                            <?php the_content(); ?>
                        </div>
                    </section>

                    <!-- Related Machine Section -->
                    <section class="border-t border-blue-200 pt-8">
                        <h2 class="text-sm font-medium text-blue-900 mb-6 flex items-center gap-2">
                            <?php icon('settings', ['class' => 'w-4 h-4']); ?>
                            <?php echo esc_html($content['related_machines']); ?>
                        </h2>
                        <?php if ($machine_tags && !empty($machine_tags)) : ?>
                            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                <?php foreach ($machine_tags as $machine_tag) :
                                    // @todo: Connect tag to WooCommerce product
                                    // Find product by matching tag name to product title/SKU
                                    // $product = wc_get_products(['name' => $machine_tag->name, 'limit' => 1]);
                                ?>
                                    <a href="<?php echo esc_url(get_tag_link($machine_tag->term_id)); ?>" class="group block border border-blue-200 bg-white hover:border-blue-500 transition-colors">
                                        <!-- Machine Image Placeholder -->
                                        <div class="aspect-video bg-blue-50 flex items-center justify-center border-b border-blue-200">
                                            <?php icon('settings', ['class' => 'w-12 h-12 text-blue-300 group-hover:text-blue-500 transition-colors']); ?>
                                        </div>
                                        <div class="p-4">
                                            <p class="font-medium text-blue-900 group-hover:text-blue-500 transition-colors">
                                                <?php echo esc_html($machine_tag->name); ?>
                                            </p>
                                            <p class="text-xs text-blue-500 mt-1 font-mono">
                                                <?php
                                                printf(
                                                    esc_html($content['manuals_available']),
                                                    $machine_tag->count
                                                );
                                                ?>
                                            </p>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php else : ?>
                            <div class="text-center py-8 border border-blue-200 bg-white">
                                <?php icon('settings', ['class' => 'w-12 h-12 text-blue-300 mx-auto mb-4']); ?>
                                <p class="text-blue-500"><?php echo esc_html($content['no_machines']); ?></p>
                                <p class="text-blue-400 text-xs mt-1"><?php echo esc_html($content['add_tags_hint']); ?></p>
                            </div>
                        <?php endif; ?>
                    </section>

                </div>

            </div>

        </article>
    <?php endwhile; ?>
</main>

<?php get_footer(); ?>
