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

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$content = [
    'eyebrow'           => __('Learning Center', 'standard'),
    'filter_category'   => __('Filter by Category', 'standard'),
    'filter_type'       => __('Filter by Type', 'standard'),
    'blog_posts'        => __('Blog Posts', 'standard'),
    'view_all'          => __('View All Posts', 'standard'),
];

get_header();
$current_category = get_queried_object();
$is_category = is_category();
$is_tag = is_tag();
$post_types = get_post_types([
    'public' => true,
    'has_archive' => true,
], 'objects');
$categories = get_categories([
    'hide_empty' => true,
    'orderby' => 'name',
    'order' => 'ASC',
]);
?>

<main id="primary" class="pattern-dot-grid py-6 lg:py-12">
    <header class="container mb-6 lg:mb-12">
        <div class="grid gap-4 justify-items-start">
            <span class="text-xs font-mono uppercase tracking-widest text-red"><?php echo esc_html($content['eyebrow']); ?></span>
            <?php the_archive_title('<h1 class="text-3xl md:text-4xl lg:text-5xl font-medium font-mono">', '</h1>'); ?>
            <?php the_archive_description('<p class="text-blue-600 max-w-2xl">', '</p>'); ?>
        </div>
    </header>
    <div class="container lg:grid lg:grid-cols-[240px_1fr] lg:gap-12">
        <aside class="hidden lg:block border-r border-blue-200 pr-8">
            <nav class="sticky top-16 grid gap-8">
                <div>
                    <h3 class="text-sm font-medium text-blue-900 mb-4 flex items-center gap-2">
                        <?php icon('filter', ['class' => 'w-4 h-4']); ?>
                        <?php echo esc_html($content['filter_category']); ?>
                    </h3>
                    <ul class="grid gap-1 border-l border-blue-200">
                        <?php if (!empty($categories)) : ?>
                            <?php foreach ($categories as $cat) :
                                $is_active = $is_category && $current_category->term_id === $cat->term_id;
                            ?>
                                <li>
                                    <a href="<?php echo esc_url(get_category_link($cat->term_id)); ?>" class="flex items-center justify-between text-sm py-2 pl-4 border-l-2 -ml-px <?php echo $is_active ? 'border-blue-500 text-blue-500 font-medium' : 'border-transparent text-blue-600 hover:text-blue-900 hover:border-blue-300'; ?>">
                                        <span><?php echo esc_html($cat->name); ?></span>
                                        <span class="text-xs text-blue-400"><?php echo esc_html($cat->count); ?></span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-blue-900 mb-4 flex items-center gap-2">
                        <?php icon('settings', ['class' => 'w-4 h-4']); ?>
                        <?php echo esc_html($content['filter_type']); ?>
                    </h3>
                    <ul class="grid gap-1 border-l border-blue-200">
                        <li>
                            <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>" class="flex items-center justify-between text-sm py-2 pl-4 border-l-2 -ml-px border-transparent text-blue-600 hover:text-blue-900 hover:border-blue-300">
                                <span><?php echo esc_html($content['blog_posts']); ?></span>
                                <span class="text-xs text-blue-400"><?php echo esc_html(wp_count_posts('post')->publish); ?></span>
                            </a>
                        </li>
                        <?php foreach ($post_types as $post_type) :
                            if ($post_type->name === 'post' || $post_type->name === 'page') continue;
                            $archive_link = get_post_type_archive_link($post_type->name);
                            if (!$archive_link) continue;
                            $count = wp_count_posts($post_type->name)->publish;
                        ?>
                            <li>
                                <a href="<?php echo esc_url($archive_link); ?>" class="flex items-center justify-between text-sm py-2 pl-4 border-l-2 -ml-px border-transparent text-blue-600 hover:text-blue-900 hover:border-blue-300">
                                    <span><?php echo esc_html($post_type->labels->name); ?></span>
                                    <span class="text-xs text-blue-400"><?php echo esc_html($count); ?></span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>" class="flex items-center gap-2 text-sm font-medium text-blue-500 hover:underline">
                    <?php icon('arrow-left', ['class' => 'w-4 h-4']); ?>
                    <?php echo esc_html($content['view_all']); ?>
                </a>

            </nav>
        </aside>
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
