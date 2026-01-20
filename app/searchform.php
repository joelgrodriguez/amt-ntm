<?php
/**
 * Custom search form template.
 *
 * Displays an accessible search form with label, input, and submit button.
 *
 * @link https://developer.wordpress.org/reference/functions/get_search_form/
 *
 * @package Standard
 */

?>

<form role="search" method="get" class="search-form flex gap-2" action="<?php echo esc_url(home_url('/')); ?>">
    <label class="sr-only" for="search-field"><?php esc_html_e('Search for:', 'standard-press'); ?></label>
    <input
        type="search"
        id="search-field"
        class="flex-1 px-4 py-2 border border-slate-300 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
        placeholder="<?php esc_attr_e('Search...', 'standard-press'); ?>"
        value="<?php echo esc_attr(get_search_query()); ?>"
        name="s"
    >
    <button type="submit" class="btn btn-primary">
        <?php esc_html_e('Search', 'standard-press'); ?>
    </button>
</form>
