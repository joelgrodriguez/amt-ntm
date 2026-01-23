<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * Displays a friendly error message with search, helpful links,
 * and navigation to key resources.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#404-not-found
 *
 * @package Standard
 */

declare(strict_types=1);

$content = [
    'error_code'    => __('404', 'standard'),
    'title'         => __('Page Not Found', 'standard'),
    'text'          => __("The page you're looking for doesn't exist or has been moved.", 'standard'),
    'links_title'   => __('Helpful Links', 'standard'),
    'back_home'     => __('Back to Home', 'standard'),
];

get_header();

$helpful_links = [
    [
        'title' => __('Learning Center', 'standard'),
        'description' => __('Articles, videos, and resources', 'standard'),
        'url' => home_url('/learning-center/'),
        'icon' => 'file-text',
    ],
    [
        'title' => __('Profiles', 'standard'),
        'description' => __('Panel and gutter profiles', 'standard'),
        'url' => get_post_type_archive_link('profile') ?: home_url('/profiles/'),
        'icon' => 'folder',
    ],
    [
        'title' => __('Manuals', 'standard'),
        'description' => __('Machine documentation', 'standard'),
        'url' => get_post_type_archive_link('manual') ?: home_url('/manuals/'),
        'icon' => 'file-text',
    ],
    [
        'title' => __('Resources', 'standard'),
        'description' => __('Downloads and literature', 'standard'),
        'url' => get_post_type_archive_link('resource') ?: home_url('/resources/'),
        'icon' => 'download',
    ],
    [
        'title' => __('Machines', 'standard'),
        'description' => __('Rollforming equipment', 'standard'),
        'url' => home_url('/machines/'),
        'icon' => 'settings',
    ],
    [
        'title' => __('Contact Us', 'standard'),
        'description' => __('Get in touch with our team', 'standard'),
        'url' => home_url('/contact/'),
        'icon' => 'mail',
    ],
];
?>

<main id="primary" class="pattern-dot-grid gradient-fade-bottom-sm py-12 lg:py-24">
    <div class="container">

        <!-- Error Message -->
        <div class="max-w-2xl mx-auto text-center mb-12 lg:mb-16">
            <p class="font-mono text-lg text-secondary font-bold uppercase tracking-wider mt-1 mb-4">
                <?php echo esc_html($content['error_code']); ?>
            </p>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold font-mono text-slate-900 mb-6">
                <?php echo esc_html($content['title']); ?>
            </h1>
            <p class="text-lg text-slate-600 mb-8">
                <?php echo esc_html($content['text']); ?>
            </p>

            <!-- Search Form -->
            <div class="max-w-md mx-auto">
                <?php get_search_form(); ?>
            </div>
        </div>

        <!-- Helpful Links -->
        <div class="max-w-4xl mx-auto">
            <h2 class="text-sm font-semibold text-slate-900 uppercase tracking-wider text-center mb-8">
                <?php echo esc_html($content['links_title']); ?>
            </h2>

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <?php foreach ($helpful_links as $link) : ?>
                    <a href="<?php echo esc_url($link['url']); ?>" class="group block p-6 bg-white border border-slate-200 hover:border-primary transition-colors">
                        <div class="flex items-start gap-4">
                            <span class="shrink-0 text-slate-400 group-hover:text-primary transition-colors">
                                <?php icon($link['icon'], ['class' => 'w-6 h-6']); ?>
                            </span>
                            <div>
                                <h3 class="font-semibold text-slate-900 group-hover:text-primary transition-colors">
                                    <?php echo esc_html($link['title']); ?>
                                </h3>
                                <p class="text-sm text-slate-500 mt-1">
                                    <?php echo esc_html($link['description']); ?>
                                </p>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>

            <!-- Back to Home -->
            <div class="text-center mt-12">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="inline-flex items-center gap-2 text-sm font-medium text-primary hover:underline">
                    <?php icon('arrow-left', ['class' => 'w-4 h-4']); ?>
                    <?php echo esc_html($content['back_home']); ?>
                </a>
            </div>
        </div>

    </div>
</main>

<?php
get_footer();
