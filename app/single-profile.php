<?php
/**
 * The template for displaying single profile posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Standard
 */

get_header();
?>

<main id="primary" class="pattern-dot-grid gradient-fade-bottom py-6 lg:py-12">
    <?php while (have_posts()) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class('grid gap-6 lg:gap-12'); ?>>
            <header class="container mx-auto grid gap-6">
                <?php the_title('<h1 class="text-3xl md:text-4xl lg:text-5xl font-bold font-mono">', '</h1>'); ?>
            </header>

            <!-- Two-column layout: Content + Data Sidebar -->
            <div class="container mx-auto lg:grid lg:grid-cols-[1fr_300px] lg:gap-12">
                <!-- Content -->
                <div>
                    <!-- PDF Embed Section -->
                    <div class="mb-8 p-6 bg-white border border-slate-300">
                        <div class="aspect-[8.5/11] bg-slate-100 flex items-center justify-center">
                            <?php
                            // @todo: Replace with ACF PDF field
                            // $pdf = get_field('profile_pdf');
                            ?>
                            <span class="text-slate-500 font-mono text-sm">PDF Embed Placeholder</span>
                        </div>
                    </div>

                    <div class="prose prose-lg max-w-full">
                        <?php the_content(); ?>
                    </div>

                    <?php get_template_part('templates/parts/disclaimer'); ?>
                </div>

                <!-- Data Sidebar -->
                <aside class="hidden lg:block">
                    <div class="sticky top-16 grid gap-6">
                        <div class="p-6 bg-white border border-slate-300">
                            <h3 class="font-semibold text-slate-900 mb-4"><?php esc_html_e('Profile Data', 'standard'); ?></h3>
                            <dl class="grid gap-3 text-sm">
                                <?php
                                // @todo: Replace with ACF fields
                                // $profile_data = get_field('profile_data');
                                ?>
                                <div>
                                    <dt class="text-slate-500"><?php esc_html_e('Field 1', 'standard'); ?></dt>
                                    <dd class="font-medium text-slate-900">Value placeholder</dd>
                                </div>
                                <div>
                                    <dt class="text-slate-500"><?php esc_html_e('Field 2', 'standard'); ?></dt>
                                    <dd class="font-medium text-slate-900">Value placeholder</dd>
                                </div>
                            </dl>
                        </div>

                        <div class="p-6 bg-white border border-slate-300">
                            <h3 class="font-semibold text-slate-900 mb-4"><?php esc_html_e('Machine Data', 'standard'); ?></h3>
                            <dl class="grid gap-3 text-sm">
                                <?php
                                // @todo: Replace with ACF fields
                                // $machine_data = get_field('machine_data');
                                ?>
                                <div>
                                    <dt class="text-slate-500"><?php esc_html_e('Machine', 'standard'); ?></dt>
                                    <dd class="font-medium text-slate-900">Value placeholder</dd>
                                </div>
                                <div>
                                    <dt class="text-slate-500"><?php esc_html_e('Model', 'standard'); ?></dt>
                                    <dd class="font-medium text-slate-900">Value placeholder</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </aside>
            </div>
        </article>
    <?php endwhile; ?>
</main>

<?php get_template_part('templates/parts/cta/subscribe'); ?>

<?php get_footer(); ?>
