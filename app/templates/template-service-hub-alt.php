<?php
/**
 * Template Name: Service Hub (Alt)
 *
 * Alternate Service Hub landing. Reframes the hub as a high-end service
 * option for NTM machine owners: a drenched dark hero + category-grouped
 * full-bleed machine gallery as the primary wayfinding (the machine IS the
 * entry), then a light "what you get" strip, a specialist band, and the
 * existing search relocated verbatim. Parallel to template-service-hub.php
 * for A/B; shares its search query plumbing (Standard\ServiceHub).
 *
 * Dark cinematic top (hero + gallery), light spec-sheet workspace below.
 * Hairline structural borders carry the seams (DESIGN.md §8).
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\LearningCenter\get_filter_groups;
use function Standard\ServiceHub\get_active_filters;
use function Standard\ServiceHub\get_post_type_label;
use function Standard\ServiceHub\get_post_type_options;
use function Standard\ServiceHub\get_results_query;

$filters = get_active_filters();
$paged = max(1, (int) get_query_var('paged'), (int) get_query_var('page'));
$service_query = get_results_query($filters, $paged);
$post_type_options = get_post_type_options();
$form_action = get_permalink() ?: \Standard\Url\internal('/service-hub/');
$has_filters = $filters['search'] !== ''
    || $filters['type'] !== ''
    || $filters['category'] !== ''
    || $filters['machine'] !== '';

$service_form_id = 'service-hub-alt-form';

$type_choice_options = ['' => __('All types', 'standard')];
foreach ($post_type_options as $post_type => $option) {
    $type_choice_options[$post_type] = (string) $option['label'];
}

$service_groups = get_filter_groups([
    'category' => $filters['category'],
    'type'     => $filters['type'],
    'machine'  => $filters['machine'],
], [
    'names' => [
        'category' => 'service_category',
        'type'     => 'service_type',
        'machine'  => 'service_machine',
    ],
    'type_options' => $type_choice_options,
]);

$machine_categories = \Standard\MachinesData\get_machine_categories(false);

get_header();
?>

<main id="primary">

    <?php /* Band 1 — Drenched hero. Dark stage, the promise + two universal actions. */ ?>
    <header class="pattern-dot-grid pattern-dot-grid--dark bg-blue-900">
        <div class="container section">
            <div class="grid gap-6 max-w-3xl">
                <span class="section-eyebrow flex items-center gap-2 text-blue-400">
                    <span class="inline-block h-1 w-1 bg-red" aria-hidden="true"></span>
                    <?php esc_html_e('Service Hub', 'standard'); ?>
                </span>

                <h1 class="font-sans font-medium text-heading-lg lg:text-display text-white leading-tight tracking-tight">
                    <?php esc_html_e('Your machine. Everything it needs. One place.', 'standard'); ?>
                </h1>

                <p class="font-sans text-blue-200 max-w-2xl" style="font-size: var(--text-body); line-height: var(--leading-body);">
                    <?php esc_html_e('Find your machine for manuals, troubleshooting, parts, and videos. Or talk to the people who have built and backed these machines since 1991.', 'standard'); ?>
                </p>

                <form
                    method="get"
                    action="<?php echo esc_url($form_action); ?>#search"
                    class="mt-2 grid gap-3 sm:grid-cols-[minmax(0,1fr)_auto]"
                    role="search"
                    aria-label="<?php esc_attr_e('Search the service library', 'standard'); ?>"
                >
                    <label for="service-hub-alt-hero-search" class="sr-only">
                        <?php esc_html_e('Search the service library', 'standard'); ?>
                    </label>
                    <input
                        id="service-hub-alt-hero-search"
                        name="service_search"
                        type="search"
                        value="<?php echo esc_attr($filters['search']); ?>"
                        class="field-input"
                        placeholder="<?php esc_attr_e('Machine, manual, problem, topic…', 'standard'); ?>"
                    >
                    <button type="submit" class="btn btn-primary w-full sm:w-auto h-11!">
                        <?php icon('search', ['class' => 'w-4 h-4']); ?>
                        <?php esc_html_e('Search', 'standard'); ?>
                    </button>
                </form>

                <p class="font-mono uppercase tracking-wider text-blue-400 m-0" style="font-size: var(--text-caption);">
                    <?php esc_html_e('Need to talk to us?', 'standard'); ?>
                    <a href="<?php echo esc_url(\Standard\Url\internal('/service-hub/request/')); ?>" class="text-blue-200 underline underline-offset-2 transition-colors duration-200 hover:text-white">
                        <?php esc_html_e('Open a service request', 'standard'); ?>
                    </a>
                </p>
            </div>
        </div>
    </header>

    <?php /* Band 2 — Category-grouped machine gallery. Dark stage continues; the machine is the door. */ ?>
    <section class="bg-blue-900 border-t border-blue-800" aria-labelledby="service-hub-alt-machines-title">
        <div class="container section">
            <h2 id="service-hub-alt-machines-title" class="font-sans font-medium text-heading text-white m-0 mb-2">
                <?php esc_html_e('Find your machine', 'standard'); ?>
            </h2>
            <p class="font-sans text-blue-300 max-w-2xl m-0 mb-10" style="font-size: var(--text-body); line-height: var(--leading-body);">
                <?php esc_html_e('Pick your machine to open its service content: manuals, troubleshooting, parts, and videos for that exact model.', 'standard'); ?>
            </p>

            <div class="grid gap-12 lg:gap-16">
                <?php foreach ($machine_categories as $category) : ?>
                    <?php
                    $cat_label    = (string) ($category['label'] ?? '');
                    $cat_machines = $category['machines'] ?? [];
                    if (empty($cat_machines)) {
                        continue;
                    }
                    ?>
                    <div class="grid gap-6">
                        <h3 class="font-mono font-medium uppercase tracking-wider text-blue-400 m-0" style="font-size: var(--text-caption);">
                            <?php echo esc_html($cat_label); ?>
                        </h3>
                        <div class="stagger grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                            <?php foreach ($cat_machines as $machine) : ?>
                                <?php get_template_part('templates/parts/service-hub/machine-photo-card', null, ['machine' => $machine]); ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

</main>

<?php
get_footer();
