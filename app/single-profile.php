<?php
/**
 * The template for displaying single profile posts.
 *
 * Dashboard-style layout with filter sidebar.
 * Profiles are tagged with machines (WooCommerce products).
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Standard
 */

get_header();

// Get profile categories and machine tags
$categories = get_the_terms(get_the_ID(), 'category');
$machine_tags = get_the_tags();
?>

<main id="primary" class="bg-slate-50 min-h-screen">
    <?php while (have_posts()) : the_post(); ?>

        <!-- Dashboard Header -->
        <header class="bg-white border-b border-slate-200">
            <div class="container mx-auto py-6">
                <div>
                    <p class="text-xs font-mono uppercase tracking-wider text-slate-500"><?php esc_html_e('Profile', 'standard'); ?></p>
                    <?php the_title('<h1 class="text-2xl font-bold font-mono">', '</h1>'); ?>
                </div>
            </div>
        </header>

        <!-- Dashboard Layout -->
        <div class="container mx-auto py-6 lg:py-12">
            <div class="lg:grid lg:grid-cols-[280px_1fr] lg:gap-8">

                <!-- Filter Sidebar -->
                <aside class="hidden lg:block">
                    <div class="sticky top-20 grid gap-6">

                        <!-- Filter by Category -->
                        <div class="bg-white border border-slate-200 p-6">
                            <h3 class="text-sm font-semibold text-slate-900 mb-4 flex items-center gap-2">
                                <?php icon('filter', ['class' => 'w-4 h-4']); ?>
                                <?php esc_html_e('Filter by Type', 'standard'); ?>
                            </h3>
                            <ul class="grid gap-2">
                                <?php
                                // @todo: Connect to actual profile categories
                                $profile_categories = get_terms([
                                    'taxonomy' => 'category',
                                    'hide_empty' => true,
                                    'object_ids' => get_posts([
                                        'post_type' => 'profile',
                                        'posts_per_page' => -1,
                                        'fields' => 'ids',
                                    ]),
                                ]);

                                if (!empty($profile_categories) && !is_wp_error($profile_categories)) :
                                    foreach ($profile_categories as $cat) :
                                        $is_active = $categories && in_array($cat->term_id, wp_list_pluck($categories, 'term_id'));
                                ?>
                                    <li>
                                        <a href="<?php echo esc_url(get_term_link($cat)); ?>" class="flex items-center justify-between text-sm py-2 px-3 <?php echo $is_active ? 'bg-primary/10 text-primary font-medium' : 'text-slate-600 hover:bg-slate-50'; ?>">
                                            <span><?php echo esc_html($cat->name); ?></span>
                                            <span class="text-xs text-slate-400"><?php echo esc_html($cat->count); ?></span>
                                        </a>
                                    </li>
                                <?php
                                    endforeach;
                                endif;
                                ?>
                            </ul>
                        </div>

                        <!-- Filter by Machine -->
                        <div class="bg-white border border-slate-200 p-6">
                            <h3 class="text-sm font-semibold text-slate-900 mb-4 flex items-center gap-2">
                                <?php icon('settings', ['class' => 'w-4 h-4']); ?>
                                <?php esc_html_e('Filter by Machine', 'standard'); ?>
                            </h3>
                            <ul class="grid gap-2">
                                <?php
                                // @todo: Connect to actual machine tags
                                $machine_terms = get_terms([
                                    'taxonomy' => 'post_tag',
                                    'hide_empty' => true,
                                    'object_ids' => get_posts([
                                        'post_type' => 'profile',
                                        'posts_per_page' => -1,
                                        'fields' => 'ids',
                                    ]),
                                ]);

                                if (!empty($machine_terms) && !is_wp_error($machine_terms)) :
                                    foreach ($machine_terms as $machine) :
                                        $is_active = $machine_tags && in_array($machine->term_id, wp_list_pluck($machine_tags, 'term_id'));
                                ?>
                                    <li>
                                        <a href="<?php echo esc_url(get_term_link($machine)); ?>" class="flex items-center justify-between text-sm py-2 px-3 <?php echo $is_active ? 'bg-primary/10 text-primary font-medium' : 'text-slate-600 hover:bg-slate-50'; ?>">
                                            <span><?php echo esc_html($machine->name); ?></span>
                                            <span class="text-xs text-slate-400"><?php echo esc_html($machine->count); ?></span>
                                        </a>
                                    </li>
                                <?php
                                    endforeach;
                                endif;
                                ?>
                            </ul>
                        </div>

                        <!-- All Profiles Link -->
                        <a href="<?php echo esc_url(get_post_type_archive_link('profile')); ?>" class="flex items-center justify-center gap-2 text-sm font-medium text-primary hover:underline py-3">
                            <?php icon('arrow--left', ['class' => 'w-4 h-4']); ?>
                            <?php esc_html_e('View All Profiles', 'standard'); ?>
                        </a>

                    </div>
                </aside>

                <!-- Main Content -->
                <article id="post-<?php the_ID(); ?>" <?php post_class('grid gap-8'); ?>>

                    <!-- Profile Content (PDF embedded via Gutenberg) -->
                    <section class="bg-white border border-slate-200 p-6">
                        <div class="prose prose-lg max-w-full">
                            <?php the_content(); ?>
                        </div>
                    </section>

                    <!-- Compatible Machines Section -->
                    <section class="bg-white border border-slate-200">
                        <div class="border-b border-slate-200 px-6 py-4">
                            <h2 class="text-sm font-semibold text-slate-900 flex items-center gap-2">
                                <?php icon('settings', ['class' => 'w-4 h-4']); ?>
                                <?php esc_html_e('Compatible NTM Machines', 'standard'); ?>
                            </h2>
                        </div>
                        <div class="p-6">
                            <?php if ($machine_tags && !empty($machine_tags)) : ?>
                                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                    <?php foreach ($machine_tags as $machine_tag) :
                                        // @todo: Connect tag to WooCommerce product
                                        // Find product by matching tag name to product title/SKU
                                        // $product = wc_get_products(['name' => $machine_tag->name, 'limit' => 1]);
                                    ?>
                                        <a href="<?php echo esc_url(get_tag_link($machine_tag->term_id)); ?>" class="group block border border-slate-200 hover:border-primary transition-colors">
                                            <!-- Machine Image Placeholder -->
                                            <div class="aspect-video bg-slate-100 flex items-center justify-center">
                                                <?php icon('settings', ['class' => 'w-12 h-12 text-slate-300 group-hover:text-primary transition-colors']); ?>
                                            </div>
                                            <div class="p-4 border-t border-slate-200">
                                                <p class="font-semibold text-slate-900 group-hover:text-primary transition-colors">
                                                    <?php echo esc_html($machine_tag->name); ?>
                                                </p>
                                                <p class="text-xs text-slate-500 mt-1 font-mono">
                                                    <?php
                                                    printf(
                                                        esc_html__('%d profiles available', 'standard'),
                                                        $machine_tag->count
                                                    );
                                                    ?>
                                                </p>
                                            </div>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            <?php else : ?>
                                <div class="text-center py-8">
                                    <?php icon('settings', ['class' => 'w-12 h-12 text-slate-300 mx-auto mb-4']); ?>
                                    <p class="text-slate-500"><?php esc_html_e('No machines tagged for this profile.', 'standard'); ?></p>
                                    <p class="text-slate-400 text-xs mt-1"><?php esc_html_e('Add machine tags to display compatible equipment.', 'standard'); ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </section>

                </article>

            </div>
        </div>

    <?php endwhile; ?>
</main>

<?php get_footer(); ?>
