<?php
/**
 * Header search modal.
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}
?>

<dialog id="site-search-modal" class="search-modal w-[calc(100%-2rem)] max-w-2xl border border-blue-200 bg-white p-0 text-blue-900">
    <div class="border-b border-blue-200 px-4 py-4 sm:px-6">
        <div class="flex items-center justify-between gap-4">
            <h2 class="font-sans text-xl font-semibold tracking-tight text-blue-900">
                <?php esc_html_e('Search', 'standard'); ?>
            </h2>

            <button
                type="button"
                class="flex h-11 w-11 items-center justify-center border border-blue-200 text-blue-700 transition-colors hover:border-blue-500 hover:text-blue-500 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                aria-label="<?php esc_attr_e('Close search', 'standard'); ?>"
                data-search-modal-close
            >
                <?php icon('x', ['class' => 'w-4 h-4', 'aria-hidden' => 'true']); ?>
            </button>
        </div>
    </div>

    <form role="search" method="get" action="<?php echo esc_url(\Standard\Url\internal('/')); ?>" class="grid gap-4 p-4 sm:p-6">
        <div class="field">
            <label for="site-search-modal-field" class="field-label">
                <?php esc_html_e('Search the site', 'standard'); ?>
            </label>
            <input
                id="site-search-modal-field"
                class="field-input"
                type="search"
                name="s"
                value="<?php echo esc_attr(get_search_query()); ?>"
                placeholder="<?php esc_attr_e('Machines, manuals, profiles, articles...', 'standard'); ?>"
                data-search-modal-input
            >
        </div>

        <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
            <button type="button" class="btn btn-ghost w-full sm:w-auto" data-search-modal-close>
                <?php esc_html_e('Cancel', 'standard'); ?>
            </button>
            <button type="submit" class="btn btn-primary w-full sm:w-auto">
                <?php esc_html_e('Search', 'standard'); ?>
            </button>
        </div>
    </form>
</dialog>
