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

declare(strict_types=1);

$content = [
    'label'       => __('Search for:', 'standard'),
    'placeholder' => __('Search...', 'standard'),
    'button'      => __('Search', 'standard'),
];
?>

<form role="search" method="get" class="search-form flex gap-2" action="<?php echo esc_url(home_url('/')); ?>">
    <label class="sr-only" for="search-field"><?php echo esc_html($content['label']); ?></label>
    <input
        type="search"
        id="search-field"
        class="flex-1 px-4 py-2 border border-slate-300 bg-white font-mono focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
        placeholder="<?php echo esc_attr($content['placeholder']); ?>"
        value="<?php echo esc_attr(get_search_query()); ?>"
        name="s"
    >
    <button type="submit" class="btn btn-primary font-mono">
        <?php echo esc_html($content['button']); ?>
    </button>
</form>
