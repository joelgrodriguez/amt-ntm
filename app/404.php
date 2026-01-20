<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * Displays a friendly error message with a link home and search form.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#404-not-found
 *
 * @package Standard
 */

get_header();
?>

<main id="primary" class="container py-16">
    <div class="max-w-xl mx-auto text-center">
        <h1 class="text-6xl font-bold text-slate-900 mb-4">404</h1>
        <h2 class="text-2xl font-semibold text-slate-700 mb-4"><?php esc_html_e('Page Not Found', 'standard-press'); ?></h2>
        <p class="text-slate-600 mb-8"><?php esc_html_e('The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.', 'standard-press'); ?></p>

        <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary">
            <?php esc_html_e('Back to Home', 'standard-press'); ?>
        </a>

        <div class="mt-12">
            <h3 class="text-lg font-medium mb-4"><?php esc_html_e('Try searching:', 'standard-press'); ?></h3>
            <?php get_search_form(); ?>
        </div>
    </div>
</main>

<?php
get_footer();
