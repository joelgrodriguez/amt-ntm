<?php
/**
 * The template for displaying search results.
 *
 * Displays search results in a grid layout with pagination.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\Search\get_post_type_filter_keys;
use function Standard\Search\get_post_type_filter_options;
use function Standard\Search\get_request_values;

$content = [
    'eyebrow'     => __('Search', 'standard'),
    'title'       => __('Search Results', 'standard'),
    'title_query' => __('Search results for "%s"', 'standard'),
    'all_types'   => __('All content', 'standard'),
    'placeholder' => __('Search machines, manuals, profiles, articles...', 'standard'),
    'submit'      => __('Search', 'standard'),
    'reset'       => __('Clear filters', 'standard'),
    'prev'        => __('Previous', 'standard'),
    'next'        => __('Next', 'standard'),
];

$search_query = get_search_query();
$type_options = get_post_type_filter_options();
$requested_types = get_request_values(get_post_type_filter_keys(), 'post_type');
$active_type = count($requested_types) === 1 ? $requested_types[0] : '';
$active_type_label = $active_type !== '' && isset($type_options[$active_type])
    ? $type_options[$active_type]
    : '';
$result_count = isset($GLOBALS['wp_query']) && $GLOBALS['wp_query'] instanceof WP_Query
    ? (int) $GLOBALS['wp_query']->found_posts
    : 0;
$reset_url = $search_query !== ''
    ? add_query_arg(['s' => $search_query], \Standard\Url\internal('/'))
    : \Standard\Url\internal('/');
$count_label = sprintf(
    /* translators: %1$d result count, %2$s optional post type label. */
    _n('%1$d result%2$s', '%1$d results%2$s', $result_count, 'standard'),
    $result_count,
    $active_type_label !== '' ? ' / ' . $active_type_label : ''
);

get_header();
?>

<main id="primary">
    <header class="pattern-dot-grid pattern-dot-grid--surface bg-blue-50 border-b border-blue-200">
        <div class="container section-compact">
            <div class="section-header-left max-w-4xl">
                <p class="section-eyebrow"><?php echo esc_html($content['eyebrow']); ?></p>
                <div class="section-divider"></div>
                <h1 class="font-sans font-semibold text-heading-lg lg:text-display text-blue-900 leading-tight tracking-tight">
                    <?php
                    echo $search_query !== ''
                        ? esc_html(sprintf($content['title_query'], $search_query))
                        : esc_html($content['title']);
                    ?>
                </h1>
                <p class="text-blue-600 max-w-2xl">
                    <?php echo esc_html($count_label); ?>
                </p>
            </div>
        </div>
    </header>

    <section class="border-b border-blue-200" aria-labelledby="search-filters-title">
        <div class="container py-6 lg:py-8">
            <h2 id="search-filters-title" class="sr-only">
                <?php esc_html_e('Refine search results', 'standard'); ?>
            </h2>

            <form class="grid gap-4 md:grid-cols-[minmax(0,1fr)_16rem_auto_auto] md:items-end" role="search" method="get" action="<?php echo esc_url(\Standard\Url\internal('/')); ?>">
                <div class="field">
                    <label for="global-search-field" class="field-label">
                        <?php esc_html_e('Search', 'standard'); ?>
                    </label>
                    <input
                        id="global-search-field"
                        class="field-input"
                        type="search"
                        name="s"
                        value="<?php echo esc_attr($search_query); ?>"
                        placeholder="<?php echo esc_attr($content['placeholder']); ?>"
                    >
                </div>

                <div class="field">
                    <label for="global-search-type" class="field-label">
                        <?php esc_html_e('Type', 'standard'); ?>
                    </label>
                    <select id="global-search-type" name="post_type" class="field-select">
                        <option value=""><?php echo esc_html($content['all_types']); ?></option>
                        <?php foreach ($type_options as $post_type => $label) : ?>
                            <option value="<?php echo esc_attr($post_type); ?>" <?php selected($active_type, $post_type); ?>>
                                <?php echo esc_html($label); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary w-full md:w-auto">
                    <?php echo esc_html($content['submit']); ?>
                </button>

                <?php if ($active_type !== '') : ?>
                    <a href="<?php echo esc_url($reset_url); ?>" class="btn btn-ghost w-full md:w-auto">
                        <?php echo esc_html($content['reset']); ?>
                    </a>
                <?php endif; ?>
            </form>
        </div>
    </section>

    <?php if (have_posts()) : ?>
        <section class="container section-compact" aria-label="<?php esc_attr_e('Search results', 'standard'); ?>">
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                <?php while (have_posts()) : the_post(); ?>
                    <?php get_template_part('templates/parts/content', 'search'); ?>
                <?php endwhile; ?>
            </div>

            <nav class="mt-8 lg:mt-10">
                <?php the_posts_pagination([
                    'mid_size'  => 2,
                    'prev_text' => '&larr; ' . esc_html($content['prev']),
                    'next_text' => esc_html($content['next']) . ' &rarr;',
                ]); ?>
            </nav>
        </section>
    <?php else : ?>
        <section class="container section-compact">
            <?php get_template_part('templates/parts/content', 'none'); ?>
        </section>
    <?php endif; ?>
</main>

<?php
get_footer();
