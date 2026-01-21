<?php
/**
 * The template for displaying archive pages.
 *
 * Dashboard-style layout with filter sidebar.
 * Displays posts for categories, tags, authors, dates, and custom taxonomies.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Standard
 */

get_header();

// Get current query info
$current_category = get_queried_object();
$is_category = is_category();
$is_tag = is_tag();

// Get all public post types (excluding attachments, etc.)
$post_types = get_post_types([
    'public' => true,
    'has_archive' => true,
], 'objects');

// Get all categories
$categories = get_categories([
    'hide_empty' => true,
    'orderby' => 'name',
    'order' => 'ASC',
]);
?>

<main id="primary" class="pattern-dot-grid gradient-fade-bottom-sm py-6 lg:py-12">

    <!-- Header -->
    <header class="container mx-auto mb-6 lg:mb-12">
        <div class="grid gap-4 justify-items-start">
            <?php if ($is_category || $is_tag) : ?>
                <span class="badge inline"><?php echo $is_category ? esc_html__('Category', 'standard') : esc_html__('Tag', 'standard'); ?></span>
            <?php endif; ?>
            <?php the_archive_title('<h1 class="text-3xl md:text-4xl lg:text-5xl font-bold font-mono">', '</h1>'); ?>
            <?php the_archive_description('<p class="text-slate-600 max-w-2xl">', '</p>'); ?>
        </div>
    </header>

    <!-- Two-column layout: Filter Sidebar + Content -->
    <div class="container mx-auto lg:grid lg:grid-cols-[240px_1fr] lg:gap-12">

        <!-- Filter Sidebar -->
        <aside class="hidden lg:block border-r border-slate-200 pr-8">
            <nav class="sticky top-16 grid gap-8">

                <!-- Filter by Category -->
                <div>
                    <h3 class="text-sm font-semibold text-slate-900 mb-4 flex items-center gap-2">
                        <?php icon('filter', ['class' => 'w-4 h-4']); ?>
                        <?php esc_html_e('Filter by Category', 'standard'); ?>
                    </h3>
                    <ul class="grid gap-1 border-l border-slate-200">
                        <?php if (!empty($categories)) : ?>
                            <?php foreach ($categories as $cat) :
                                $is_active = $is_category && $current_category->term_id === $cat->term_id;
                            ?>
                                <li>
                                    <a href="<?php echo esc_url(get_category_link($cat->term_id)); ?>" class="flex items-center justify-between text-sm py-2 pl-4 border-l-2 -ml-px <?php echo $is_active ? 'border-primary text-primary font-medium' : 'border-transparent text-slate-600 hover:text-slate-900 hover:border-slate-300'; ?>">
                                        <span><?php echo esc_html($cat->name); ?></span>
                                        <span class="text-xs text-slate-400"><?php echo esc_html($cat->count); ?></span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>

                <!-- Filter by Post Type -->
                <div>
                    <h3 class="text-sm font-semibold text-slate-900 mb-4 flex items-center gap-2">
                        <?php icon('settings', ['class' => 'w-4 h-4']); ?>
                        <?php esc_html_e('Filter by Type', 'standard'); ?>
                    </h3>
                    <ul class="grid gap-1 border-l border-slate-200">
                        <!-- All Posts -->
                        <li>
                            <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>" class="flex items-center justify-between text-sm py-2 pl-4 border-l-2 -ml-px border-transparent text-slate-600 hover:text-slate-900 hover:border-slate-300">
                                <span><?php esc_html_e('Blog Posts', 'standard'); ?></span>
                                <span class="text-xs text-slate-400"><?php echo esc_html(wp_count_posts('post')->publish); ?></span>
                            </a>
                        </li>
                        <?php foreach ($post_types as $post_type) :
                            if ($post_type->name === 'post' || $post_type->name === 'page') continue;
                            $archive_link = get_post_type_archive_link($post_type->name);
                            if (!$archive_link) continue;
                            $count = wp_count_posts($post_type->name)->publish;
                        ?>
                            <li>
                                <a href="<?php echo esc_url($archive_link); ?>" class="flex items-center justify-between text-sm py-2 pl-4 border-l-2 -ml-px border-transparent text-slate-600 hover:text-slate-900 hover:border-slate-300">
                                    <span><?php echo esc_html($post_type->labels->name); ?></span>
                                    <span class="text-xs text-slate-400"><?php echo esc_html($count); ?></span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <!-- All Posts Link -->
                <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>" class="flex items-center gap-2 text-sm font-medium text-primary hover:underline">
                    <?php icon('arrow--left', ['class' => 'w-4 h-4']); ?>
                    <?php esc_html_e('View All Posts', 'standard'); ?>
                </a>

            </nav>
        </aside>

        <!-- Main Content -->
        <div class="grid gap-8">
            <?php if (have_posts()) : ?>
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <?php while (have_posts()) : the_post(); ?>
                        <?php get_template_part('templates/parts/card-post'); ?>
                    <?php endwhile; ?>
                </div>

                <?php \Standard\Walkers\Pagination::render(); ?>
            <?php else : ?>
                <?php get_template_part('templates/parts/content', 'none'); ?>
            <?php endif; ?>
        </div>

    </div>

</main>

<?php
get_footer();
