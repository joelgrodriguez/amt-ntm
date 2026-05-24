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
            <p class="search-modal__eyebrow">
                <?php esc_html_e('Search', 'standard'); ?>
                <span class="search-modal__shortcut" data-search-modal-shortcut aria-hidden="true">
                    <kbd>/</kbd>
                </span>
            </p>

            <button
                type="button"
                class="search-modal__close"
                aria-label="<?php esc_attr_e('Close search', 'standard'); ?>"
                data-search-modal-close
            >
                <?php icon('x', ['class' => 'w-4 h-4', 'aria-hidden' => 'true']); ?>
            </button>
        </header>

        <div class="search-modal__field">
            <label for="site-search-modal-field" class="sr-only">
                <?php esc_html_e('Search the site', 'standard'); ?>
            </label>
            <span class="search-modal__field-icon" aria-hidden="true">
                <?php icon('search', ['class' => 'w-5 h-5']); ?>
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
        </div>

        <fieldset class="search-modal__chips" data-search-modal-chips>
            <legend class="sr-only"><?php esc_html_e('Filter by content type', 'standard'); ?></legend>

            <button
                type="button"
                class="search-modal__chip"
                data-search-modal-chip
                data-value=""
                aria-pressed="<?php echo $active_post_type === '' ? 'true' : 'false'; ?>"
            >
                <?php esc_html_e('All', 'standard'); ?>
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
        </fieldset>

        <!-- post_type[] is owned by the chip group above. JS keeps a single
             hidden input in sync; submit posts the chosen post_type. -->
        <input
            type="hidden"
            name="post_type"
            value="<?php echo esc_attr($active_post_type); ?>"
            data-search-modal-post-type
            <?php echo $active_post_type === '' ? 'disabled' : ''; ?>
        >

        <?php if ($popular !== []) : ?>
            <section class="search-modal__suggestions" aria-label="<?php esc_attr_e('Popular searches', 'standard'); ?>">
                <p class="search-modal__suggestions-label">
                    <span data-search-modal-helper data-helper-default="<?php esc_attr_e('Try', 'standard'); ?>" data-helper-typed="<?php esc_attr_e('Press Enter to search', 'standard'); ?>">
                        <?php esc_html_e('Try', 'standard'); ?>
                    </span>
                </p>
                <ul class="search-modal__suggestions-list">
                    <?php foreach ($popular as $item) :
                        $query_args = ['s' => $item['query']];
                        if (!empty($item['post_type'])) {
                            $query_args['post_type'] = $item['post_type'];
                        }
                        $href = \add_query_arg(
                            array_map('rawurlencode', $query_args),
                            \Standard\Url\internal('/')
                        );
                    ?>
                        <li>
                            <a
                                class="search-modal__suggestion"
                                href="<?php echo esc_url($href); ?>"
                                data-search-modal-suggestion
                                data-query="<?php echo esc_attr($item['query']); ?>"
                                <?php if (!empty($item['post_type'])) : ?>data-post-type="<?php echo esc_attr($item['post_type']); ?>"<?php endif; ?>
                            >
                                <?php icon('arrow-right', ['class' => 'w-3 h-3', 'aria-hidden' => 'true']); ?>
                                <span><?php echo esc_html($item['label']); ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </section>
        <?php endif; ?>

        <button type="submit" class="search-modal__submit">
            <span class="search-modal__submit-label">
                <?php esc_html_e('Search', 'standard'); ?>
            </span>
            <span class="search-modal__submit-icon" aria-hidden="true">
                <?php icon('arrow-right', ['class' => 'w-4 h-4']); ?>
            </span>
        </button>
    </form>
</dialog>
