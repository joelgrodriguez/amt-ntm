<?php
/**
 * Header search modal.
 *
 * Desktop: top-anchored panel pinned under the header.
 * Mobile : bottom sheet, anchored to the safe-area-inset-bottom edge.
 *
 * Topology: <dialog> + native showModal() so the backdrop, focus trap,
 * and Esc-to-close come from the platform. The viewport-specific
 * positioning is the only thing the CSS overrides.
 *
 * Vocabulary:
 *   - oversized search input with leading magnifier + clear-text affordance
 *   - content-type chips (All, Machines, Profiles, Manuals, ...) that toggle
 *     post_type[] hidden inputs; shares the 4px stripe vocabulary with the
 *     filter sidebar so chip-state and rail-state read as one system
 *   - curated "Try" row of popular searches (editorial, not log-derived)
 *   - imposing slab submit, same language as the filter footer
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\Search\get_post_type_filter_options;
use function Standard\Search\get_popular_searches;

$post_type_options = get_post_type_filter_options();
$popular           = get_popular_searches();

// Preselected chip mirrors current page state so re-opening on a results
// page reads as refinement, not reset. Empty string = "All".
$active_post_type = '';
if (\function_exists('is_search') && \is_search() && isset($_GET['post_type'])) {
    $requested = \wp_unslash($_GET['post_type']);
    $requested = is_array($requested) ? reset($requested) : $requested;
    if (is_string($requested) && $requested !== '') {
        $candidate = \sanitize_key($requested);
        if (isset($post_type_options[$candidate])) {
            $active_post_type = $candidate;
        }
    }
}

$current_query = (string) \get_search_query();
?>

<dialog
    id="site-search-modal"
    class="search-modal t-panel-slide"
    data-open="false"
    aria-label="<?php esc_attr_e('Site search', 'standard'); ?>"
>
    <form
        role="search"
        method="get"
        action="<?php echo esc_url(\Standard\Url\internal('/')); ?>"
        class="search-modal__form"
        data-search-modal-form
    >
        <header class="search-modal__bar">
            <div class="container search-modal__bar-inner">
                <p class="search-modal__eyebrow">
                    <?php esc_html_e('Search NTM', 'standard'); ?>
                </p>

                <button
                    type="button"
                    class="search-modal__close"
                    aria-label="<?php esc_attr_e('Close search', 'standard'); ?>"
                    data-search-modal-close
                >
                    <?php icon('x', ['class' => 'w-4 h-4', 'aria-hidden' => 'true']); ?>
                </button>
            </div>
        </header>

        <div class="search-modal__field">
            <div class="container search-modal__field-inner">
                <label for="site-search-modal-field" class="sr-only">
                    <?php esc_html_e('Search the site', 'standard'); ?>
                </label>
                <span class="search-modal__field-icon" aria-hidden="true">
                    <?php icon('search', ['class' => 'w-6 h-6']); ?>
                </span>
                <input
                    id="site-search-modal-field"
                    class="search-modal__input"
                    type="search"
                    name="s"
                    value="<?php echo esc_attr($current_query); ?>"
                    placeholder="<?php esc_attr_e('Machines, manuals, profiles, articles…', 'standard'); ?>"
                    autocomplete="off"
                    autocapitalize="off"
                    spellcheck="false"
                    role="combobox"
                    aria-controls="site-search-modal-results-list"
                    aria-autocomplete="list"
                    aria-expanded="false"
                    data-search-modal-input
                >
                <button
                    type="button"
                    class="search-modal__clear"
                    aria-label="<?php esc_attr_e('Clear search', 'standard'); ?>"
                    data-search-modal-clear
                    hidden
                >
                    <?php icon('x', ['class' => 'w-4 h-4', 'aria-hidden' => 'true']); ?>
                </button>
                <kbd class="search-modal__hint" aria-hidden="true" title="<?php esc_attr_e('Press Enter to see all results', 'standard'); ?>">
                    <?php echo esc_html('↩'); ?>
                </kbd>
            </div>
        </div>

        <!-- Scope chips. Hidden until the user has committed at least
             MIN_QUERY_LENGTH characters; JS flips data-revealed when the
             debounced query fires. Contractors type first, refine second.
             Pre-revealed if the modal opens onto an existing search-page
             state ($current_query has content). -->
        <div
            class="search-modal__chips-row"
            data-search-modal-chips-row
            data-revealed="<?php echo $current_query !== '' ? 'true' : 'false'; ?>"
        >
            <div class="container">
                <fieldset class="search-modal__chips" data-search-modal-chips>
                    <legend class="search-modal__chips-label">
                        <?php esc_html_e('Narrow', 'standard'); ?>
                    </legend>

                    <div class="search-modal__chips-list">
                        <button
                            type="button"
                            class="search-modal__chip"
                            data-search-modal-chip
                            data-value=""
                            aria-pressed="<?php echo $active_post_type === '' ? 'true' : 'false'; ?>"
                        >
                            <?php esc_html_e('Everything', 'standard'); ?>
                        </button>

                        <?php foreach ($post_type_options as $slug => $label) : ?>
                            <button
                                type="button"
                                class="search-modal__chip"
                                data-search-modal-chip
                                data-value="<?php echo esc_attr((string) $slug); ?>"
                                aria-pressed="<?php echo $active_post_type === $slug ? 'true' : 'false'; ?>"
                            >
                                <?php echo esc_html((string) $label); ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                </fieldset>
            </div>
        </div>

        <!-- post_type is driven by the chip group above. JS keeps the
             hidden input in sync; submit posts the chosen post_type. -->
        <input
            type="hidden"
            name="post_type"
            value="<?php echo esc_attr($active_post_type); ?>"
            data-search-modal-post-type
            <?php echo $active_post_type === '' ? 'disabled' : ''; ?>
        >

        <?php if ($popular !== []) : ?>
            <!-- Cold-open on-ramp. Curated suggestions live here as one-tap
                 buttons; tapping fills the input, sets the scope chip if the
                 entry carries a post_type, and triggers a fetch. Hidden once
                 the user types and results arrive. Re-rendered inside the
                 empty-state block below so a no-match doesn't leave the
                 user staring at a dead end. -->
            <section
                class="search-modal__popular"
                data-search-modal-popular
                aria-label="<?php esc_attr_e('Popular searches', 'standard'); ?>"
            >
                <div class="container">
                    <p class="search-modal__popular-label">
                        <?php esc_html_e('Popular searches', 'standard'); ?>
                    </p>
                    <ul class="search-modal__popular-list">
                        <?php foreach ($popular as $item) : ?>
                            <li>
                                <button
                                    type="button"
                                    class="search-modal__popular-item"
                                    data-search-modal-popular-item
                                    data-query="<?php echo esc_attr($item['query']); ?>"
                                    <?php if (!empty($item['post_type'])) : ?>
                                        data-post-type="<?php echo esc_attr($item['post_type']); ?>"
                                    <?php endif; ?>
                                >
                                    <?php echo esc_html($item['label']); ?>
                                </button>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </section>
        <?php endif; ?>

        <!-- Quick results region. Hidden until the user types. JS owns
             the inner DOM; PHP only renders the shell so the role,
             label and live-region attributes exist before scripts run.
             Capped at 5 results via REST `per_page=5`.
             The empty-state subblock surfaces popular searches inline +
             an "Ask a specialist" CTA so a no-match has a soft exit. -->
        <section
            class="search-modal__results"
            data-search-modal-results
            data-state="idle"
            hidden
        >
            <div class="container">
                <div
                    class="search-modal__results-status"
                    role="status"
                    aria-live="polite"
                    data-search-modal-results-status
                ></div>

                <ul
                    id="site-search-modal-results-list"
                    class="search-modal__results-list"
                    role="listbox"
                    aria-label="<?php esc_attr_e('Quick results', 'standard'); ?>"
                    data-search-modal-results-list
                ></ul>

                <a
                    class="search-modal__results-all"
                    href="#"
                    data-search-modal-results-all
                    hidden
                >
                    <span data-search-modal-results-all-label></span>
                    <?php icon('arrow-right', ['class' => 'w-3 h-3', 'aria-hidden' => 'true']); ?>
                </a>

                <!-- Empty + error fallback. Hidden by default; JS reveals
                     it when state = 'empty' or 'error'. Same popular row
                     vocabulary as the cold open, plus a specialist CTA. -->
                <div
                    class="search-modal__results-fallback"
                    data-search-modal-fallback
                    hidden
                >
                    <p class="search-modal__results-fallback-prompt">
                        <?php esc_html_e('Try a popular search', 'standard'); ?>
                    </p>
                    <?php if ($popular !== []) : ?>
                        <ul class="search-modal__popular-list">
                            <?php foreach ($popular as $item) : ?>
                                <li>
                                    <button
                                        type="button"
                                        class="search-modal__popular-item"
                                        data-search-modal-popular-item
                                        data-query="<?php echo esc_attr($item['query']); ?>"
                                        <?php if (!empty($item['post_type'])) : ?>
                                            data-post-type="<?php echo esc_attr($item['post_type']); ?>"
                                        <?php endif; ?>
                                    >
                                        <?php echo esc_html($item['label']); ?>
                                    </button>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                    <p class="search-modal__results-fallback-cta">
                        <?php esc_html_e('Still stuck?', 'standard'); ?>
                        <a href="<?php echo esc_url(\Standard\Url\internal('/contact/')); ?>">
                            <?php esc_html_e('Ask a specialist', 'standard'); ?>
                        </a>
                    </p>
                    <button
                        type="button"
                        class="search-modal__results-fallback-retry"
                        data-search-modal-retry
                        hidden
                    >
                        <?php esc_html_e('Try again', 'standard'); ?>
                    </button>
                </div>
            </div>
        </section>
    </form>
</dialog>
