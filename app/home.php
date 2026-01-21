<?php
/**
 * The blog landing page template (Learning Center).
 *
 * Displays when a static front page is set and this is the posts page.
 * Features hero with latest content, filtered sections by post type,
 * and category/machine tag filters.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Standard
 */

declare(strict_types=1);

get_header();

// Get featured post (latest from any learning center post type)
$featured_query = new WP_Query([
    'post_type'      => ['post', 'video', 'resource', 'download'],
    'posts_per_page' => 1,
    'post_status'    => 'publish',
]);

// Get recent posts for hero sidebar (excluding featured)
$featured_id = $featured_query->have_posts() ? $featured_query->posts[0]->ID : 0;
$recent_query = new WP_Query([
    'post_type'      => ['post', 'video', 'resource', 'download'],
    'posts_per_page' => 4,
    'post_status'    => 'publish',
    'post__not_in'   => $featured_id ? [$featured_id] : [],
]);

// Get categories for filter
$categories = get_categories([
    'hide_empty' => true,
    'orderby'    => 'count',
    'order'      => 'DESC',
    'number'     => 8,
]);

// Get machine tags for filter
$machine_tags = get_tags([
    'hide_empty' => true,
    'orderby'    => 'count',
    'order'      => 'DESC',
    'number'     => 8,
]);

// Content type sections config
$content_sections = [
    [
        'title'     => __('Latest Articles', 'standard'),
        'post_type' => 'post',
        'icon'      => 'document',
        'link'      => home_url('/learning-center/articles/'),
        'link_text' => __('View All Articles', 'standard'),
    ],
    [
        'title'     => __('Latest Videos', 'standard'),
        'post_type' => 'video',
        'icon'      => 'play--solid',
        'link'      => get_post_type_archive_link('video'),
        'link_text' => __('View All Videos', 'standard'),
    ],
    [
        'title'     => __('Latest Resources', 'standard'),
        'post_type' => 'resource',
        'icon'      => 'folder',
        'link'      => get_post_type_archive_link('resource'),
        'link_text' => __('View All Resources', 'standard'),
    ],
    [
        'title'     => __('Latest Downloads', 'standard'),
        'post_type' => 'download',
        'icon'      => 'download',
        'link'      => get_post_type_archive_link('download'),
        'link_text' => __('View All Downloads', 'standard'),
    ],
];
?>

<main id="primary">

    <!-- Hero Section -->
    <section class="pattern-dot-grid gradient-fade-bottom-sm border-b border-slate-200">
        <div class="container mx-auto py-8 lg:py-12">

            <!-- Header -->
            <header class="mb-8 lg:mb-12">
                <span class="text-xs font-mono uppercase tracking-widest text-secondary">
                    <?php esc_html_e('Learning Center', 'standard'); ?>
                </span>
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold font-mono mt-2">
                    <?php esc_html_e('The Rollforming Learning Center', 'standard'); ?>
                </h1>
                <p class="text-slate-600 mt-4 max-w-2xl">
                    <?php esc_html_e('Articles, videos, and resources to help you get the most out of your portable rollforming equipment.', 'standard'); ?>
                </p>
            </header>

            <!-- Featured + Recent Grid -->
            <div class="grid lg:grid-cols-[2fr_1fr] gap-6">

                <!-- Featured Post (Large) -->
                <?php if ($featured_query->have_posts()) : $featured_query->the_post(); ?>
                    <article class="group border border-slate-200 bg-white">
                        <a href="<?php the_permalink(); ?>" class="block no-underline">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="aspect-video overflow-hidden border-b border-slate-200">
                                    <?php the_post_thumbnail('large', [
                                        'class' => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-300',
                                    ]); ?>
                                </div>
                            <?php endif; ?>
                            <div class="p-6">
                                <?php
                                $featured_post_type = get_post_type();
                                $featured_type_config = [
                                    'post'     => ['icon' => 'document', 'cta' => __('Read Article', 'standard')],
                                    'video'    => ['icon' => 'play--solid', 'cta' => __('Watch Video', 'standard')],
                                    'resource' => ['icon' => 'folder', 'cta' => __('View Resource', 'standard')],
                                    'download' => ['icon' => 'download', 'cta' => __('View Download', 'standard')],
                                ];
                                $featured_icon = $featured_type_config[$featured_post_type]['icon'] ?? 'document';
                                $featured_cta = $featured_type_config[$featured_post_type]['cta'] ?? __('Read More', 'standard');
                                ?>
                                <span class="inline-flex items-center px-3 py-1 bg-primary text-white text-xs font-mono uppercase tracking-wider mb-4">
                                    <?php esc_html_e('Latest', 'standard'); ?>
                                </span>
                                <h2 class="text-xl lg:text-2xl font-bold font-mono mb-3 text-slate-900 group-hover:text-primary transition-colors">
                                    <?php the_title(); ?>
                                </h2>
                                <p class="text-slate-600 text-sm line-clamp-2 mb-4">
                                    <?php echo esc_html(get_the_excerpt()); ?>
                                </p>
                                <div class="flex items-center gap-4 text-xs text-slate-500 font-mono mb-4">
                                    <span class="flex items-center gap-1.5">
                                        <?php icon('calendar', ['class' => 'w-3 h-3']); ?>
                                        <?php echo esc_html(get_the_date()); ?>
                                    </span>
                                    <span>&middot;</span>
                                    <span class="flex items-center gap-1.5">
                                        <?php icon('user', ['class' => 'w-3 h-3']); ?>
                                        <?php the_author(); ?>
                                    </span>
                                </div>
                                <span class="inline-flex items-center gap-2 text-sm font-medium text-primary">
                                    <?php echo esc_html($featured_cta); ?>
                                    <?php icon('arrow--right', ['class' => 'w-4 h-4']); ?>
                                </span>
                            </div>
                        </a>
                    </article>
                <?php wp_reset_postdata(); endif; ?>

                <!-- Recent Posts (Stacked) + Subscribe CTA -->
                <div class="flex flex-col gap-3">
                    <?php
                    $type_icons = [
                        'post'     => 'document',
                        'video'    => 'play--solid',
                        'resource' => 'folder',
                        'download' => 'download',
                    ];
                    ?>
                    <?php if ($recent_query->have_posts()) : ?>
                        <?php while ($recent_query->have_posts()) : $recent_query->the_post();
                            $recent_post_type = get_post_type();
                            $recent_icon = $type_icons[$recent_post_type] ?? 'document';
                        ?>
                            <article class="group bg-white border border-slate-200 hover:border-slate-300 transition-colors shrink-0">
                                <a href="<?php the_permalink(); ?>" class="flex items-center gap-4 p-3 no-underline">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <div class="shrink-0 w-16 h-16 overflow-hidden">
                                            <?php the_post_thumbnail('thumbnail', [
                                                'class' => 'w-full h-full object-cover',
                                            ]); ?>
                                        </div>
                                    <?php endif; ?>
                                    <div class="flex-1 min-w-0">
                                        <span class="inline-flex items-center gap-1.5 text-xs text-slate-400 font-mono uppercase tracking-wide mb-1">
                                            <?php icon($recent_icon, ['class' => 'w-3 h-3']); ?>
                                            <?php
                                            $type_labels = [
                                                'post'     => __('Article', 'standard'),
                                                'video'    => __('Video', 'standard'),
                                                'resource' => __('Resource', 'standard'),
                                                'download' => __('Download', 'standard'),
                                            ];
                                            echo esc_html($type_labels[$recent_post_type] ?? '');
                                            ?>
                                        </span>
                                        <h3 class="font-semibold text-slate-900 group-hover:text-primary transition-colors line-clamp-2 text-sm">
                                            <?php the_title(); ?>
                                        </h3>
                                    </div>
                                </a>
                            </article>
                        <?php endwhile; wp_reset_postdata(); ?>
                    <?php endif; ?>

                    <!-- Subscribe CTA -->
                    <div class="bg-primary p-5 flex-1 flex flex-col justify-center">
                        <span class="text-xs font-mono uppercase tracking-widest text-white/70 mb-2">
                            <?php esc_html_e('Stay Updated', 'standard'); ?>
                        </span>
                        <h3 class="text-white font-bold font-mono text-lg mb-2">
                            <?php esc_html_e('Subscribe to the Learning Center', 'standard'); ?>
                        </h3>
                        <p class="text-white/80 text-sm mb-4">
                            <?php esc_html_e('Get the latest articles, videos, and resources delivered to your inbox.', 'standard'); ?>
                        </p>
                        <a href="#subscribe" class="inline-flex items-center gap-2 bg-white text-primary px-4 py-2 text-sm font-medium hover:bg-slate-100 transition-colors w-fit">
                            <?php icon('email', ['class' => 'w-4 h-4']); ?>
                            <?php esc_html_e('Subscribe Now', 'standard'); ?>
                        </a>
                    </div>
                </div>

            </div>

        </div>
    </section>

    <!-- Quick Filters -->
    <section class="border-b border-slate-200 bg-slate-50 ">
        <div class="container mx-auto py-6">
            <form class="flex flex-wrap items-center justify-center gap-8" method="get" action="<?php echo esc_url(home_url('/')); ?>">
                <span class="text-sm font-semibold text-slate-700 flex items-center gap-2">
                    <?php icon('filter', ['class' => 'w-4 h-4']); ?>
                    <?php esc_html_e('Filters:', 'standard'); ?>
                </span>

                <!-- Category Dropdown -->
                <div class="flex items-center gap-2">
                    <label class="flex items-center gap-1.5 text-sm text-slate-600">
                        <?php icon('folder', ['class' => 'w-4 h-4']); ?>
                        <?php esc_html_e('Category', 'standard'); ?>
                    </label>
                    <div class="relative">
                        <select name="category_name" class="appearance-none bg-white border border-slate-200 text-slate-700 text-sm font-mono pl-3 pr-8 py-2 cursor-pointer hover:border-slate-300 focus:border-primary focus:outline-none" onchange="this.form.submit()">
                            <option value=""><?php esc_html_e('All', 'standard'); ?></option>
                            <?php foreach ($categories as $cat) : ?>
                                <option value="<?php echo esc_attr($cat->slug); ?>">
                                    <?php echo esc_html($cat->name); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                            <?php icon('caret--down', ['class' => 'w-3 h-3 text-slate-400']); ?>
                        </div>
                    </div>
                </div>

                <!-- Resource Type Dropdown -->
                <div class="flex items-center gap-2">
                    <label class="flex items-center gap-1.5 text-sm text-slate-600">
                        <?php icon('document', ['class' => 'w-4 h-4']); ?>
                        <?php esc_html_e('Resource Type', 'standard'); ?>
                    </label>
                    <div class="relative">
                        <select name="post_type" class="appearance-none bg-white border border-slate-200 text-slate-700 text-sm font-mono pl-3 pr-8 py-2 cursor-pointer hover:border-slate-300 focus:border-primary focus:outline-none" onchange="this.form.submit()">
                            <option value=""><?php esc_html_e('All', 'standard'); ?></option>
                            <option value="post"><?php esc_html_e('Articles', 'standard'); ?></option>
                            <option value="video"><?php esc_html_e('Videos', 'standard'); ?></option>
                            <option value="resource"><?php esc_html_e('Resources', 'standard'); ?></option>
                            <option value="download"><?php esc_html_e('Downloads', 'standard'); ?></option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                            <?php icon('caret--down', ['class' => 'w-3 h-3 text-slate-400']); ?>
                        </div>
                    </div>
                </div>

                <!-- Machine Dropdown -->
                <?php if (!empty($machine_tags)) : ?>
                    <div class="flex items-center gap-2">
                        <label class="flex items-center gap-1.5 text-sm text-slate-600">
                            <?php icon('settings', ['class' => 'w-4 h-4']); ?>
                            <?php esc_html_e('Machine', 'standard'); ?>
                        </label>
                        <div class="relative">
                            <select name="tag" class="appearance-none bg-white border border-slate-200 text-slate-700 text-sm font-mono pl-3 pr-8 py-2 cursor-pointer hover:border-slate-300 focus:border-primary focus:outline-none" onchange="this.form.submit()">
                                <option value=""><?php esc_html_e('All', 'standard'); ?></option>
                                <?php foreach ($machine_tags as $tag) : ?>
                                    <option value="<?php echo esc_attr($tag->slug); ?>">
                                        <?php echo esc_html($tag->name); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                                <?php icon('caret--down', ['class' => 'w-3 h-3 text-slate-400']); ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </section>

    <!-- Content Sections by Post Type -->
    <?php foreach ($content_sections as $section) :
        $section_query = new WP_Query([
            'post_type'      => $section['post_type'],
            'posts_per_page' => 4,
            'post_status'    => 'publish',
        ]);

        if (!$section_query->have_posts()) {
            wp_reset_postdata();
            continue;
        }
    ?>
        <section class="py-12 lg:py-16 border-b border-slate-200">
            <div class="container mx-auto">

                <!-- Section Header -->
                <header class="flex items-center justify-between mb-8">
                    <h2 class="text-xl lg:text-2xl font-bold font-mono flex items-center gap-3">
                        <?php icon($section['icon'], ['class' => 'w-6 h-6 text-slate-400']); ?>
                        <?php echo esc_html($section['title']); ?>
                    </h2>
                    <?php if ($section['link']) : ?>
                        <a href="<?php echo esc_url($section['link']); ?>" class="hidden sm:inline-flex items-center gap-2 text-sm font-medium text-primary hover:underline">
                            <?php echo esc_html($section['link_text']); ?>
                            <?php icon('arrow--right', ['class' => 'w-4 h-4']); ?>
                        </a>
                    <?php endif; ?>
                </header>

                <!-- Posts Grid -->
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    <?php while ($section_query->have_posts()) : $section_query->the_post(); ?>
                        <?php get_template_part('templates/parts/card-post'); ?>
                    <?php endwhile; ?>
                </div>

                <!-- Mobile View All Link -->
                <?php if ($section['link']) : ?>
                    <div class="mt-6 sm:hidden">
                        <a href="<?php echo esc_url($section['link']); ?>" class="inline-flex items-center gap-2 text-sm font-medium text-primary hover:underline">
                            <?php echo esc_html($section['link_text']); ?>
                            <?php icon('arrow--right', ['class' => 'w-4 h-4']); ?>
                        </a>
                    </div>
                <?php endif; ?>

            </div>
        </section>
    <?php wp_reset_postdata(); endforeach; ?>

    <!-- Subscribe CTA -->
    <?php get_template_part('templates/parts/cta/subscribe'); ?>

</main>

<?php
get_footer();
