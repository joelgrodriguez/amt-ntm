<?php
/**
 * Service Hub machine mini-page. Routed by inc/service-hub-machines.php.
 *
 * A spec-sheet for one machine's service content. Two-column light hero:
 * identity + mono meta rail + service-request CTA on the left, the machine's
 * footprint drawing (blueprint) opposite on the right. Below: service content
 * grouped by type (Manuals, Videos, Troubleshooting, Parts), empty groups
 * hidden. When nothing is tagged yet, the empty state surfaces the machine's
 * manual and brochure directly so an owner still leaves with the document.
 *
 * Detailed machine data (stats, dimensions, footprint, manual/brochure URLs)
 * is loaded by slug via Standard\MachineProductData — the same loader the
 * WooCommerce product page uses — so this virtual route reuses real content
 * with no post in context and no duplication. Reuses card-post.php (no args;
 * reads the loop).
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\ServiceHubMachines\find_machine;
use function Standard\ServiceHubMachines\get_content_groups;
use function Standard\ServiceHubMachines\get_machine_footprint;
use function Standard\MachineProductData\get_machine_product_data;

$slug    = (string) get_query_var(\Standard\ServiceHubMachines\QUERY_VAR);
$machine = find_machine($slug);

if ($machine === null) {
    // Defensive: routing already 404s unknown slugs.
    get_header();
    echo '<main id="primary"></main>';
    get_footer();
    return;
}

// Trademark marks are stripped for display: this is a support page for an
// owner who already bought the machine, not the marketing catalog. Source
// data keeps the marks (machines-data.php).
$strip_tm = static fn(string $s): string => trim(str_replace(["\u{2122}", "\u{00AE}"], '', $s));

$name        = $strip_tm((string) ($machine['name'] ?? $machine['short_name'] ?? $slug));
$descriptor  = (string) ($machine['descriptor'] ?? '');
$kicker      = $strip_tm((string) ($machine['short_name'] ?? $name));
$request_url = \Standard\Url\internal('/service-hub/request/?machine=' . rawurlencode($slug));

// Detailed data file (stats, dimensions, footprint, resources) by slug.
$data      = get_machine_product_data($slug) ?? [];
$footprint = get_machine_footprint($slug);

// Meta rail: up to four mono spec call-outs from the data file's stats. These
// identify the machine at a glance the way the legacy page buried in body copy.
$meta = [];
foreach (($data['stats'] ?? []) as $stat) {
    $value = (string) ($stat['value'] ?? '');
    $label = (string) ($stat['label'] ?? '');
    if ($value === '' || $label === '') {
        continue;
    }
    $meta[] = ['label' => $label, 'value' => $value];
    if (count($meta) === 4) {
        break;
    }
}

// Owner-facing documents from the data file. Available even when zero WP
// content is tagged, so the hero and the empty state always have something
// real to offer.
$resources    = $data['resources'] ?? [];
$manual_url   = (string) ($resources['manual'] ?? '');
$brochure_url = (string) ($resources['brochure'] ?? '');

$groups      = get_content_groups($slug);
$has_content = false;
foreach ($groups as $group) {
    if ($group['query']->have_posts()) {
        $has_content = true;
        break;
    }
}

get_header();
?>

<main id="primary">

    <?php get_template_part('templates/parts/breadcrumbs'); ?>

    <?php /* Hero — spec sheet. Identity + meta rail + service CTA on the left,
            footprint drawing opposite on the right. Light surface, hairline
            structural borders (DESIGN.md §8). One column when no footprint. */ ?>
    <header class="bg-white border-b border-blue-200" aria-labelledby="service-hub-machine-title">
        <div class="container section-compact">
            <?php $has_footprint = $footprint['image'] !== ''; ?>
            <div class="grid gap-10 lg:gap-16 <?php echo $has_footprint ? 'lg:grid-cols-2 lg:items-start' : 'max-w-3xl'; ?>">

                <div class="grid gap-6 content-start">
                    <p class="font-mono font-medium uppercase tracking-wider text-blue-500" style="font-size: var(--text-caption);">
                        <?php
                        /* translators: %s machine short name. */
                        printf(esc_html__('Service Hub // %s', 'standard'), esc_html($kicker));
                        ?>
                    </p>

                    <div class="grid gap-3">
                        <h1 id="service-hub-machine-title" class="font-sans font-medium text-heading-lg lg:text-display text-blue-900 leading-tight tracking-tight text-balance m-0">
                            <?php echo esc_html($name); ?>
                        </h1>
                        <?php if ($descriptor !== '') : ?>
                            <p class="font-sans text-blue-600 max-w-xl m-0" style="font-size: var(--text-body); line-height: var(--leading-body);">
                                <?php echo esc_html($descriptor); ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <a href="<?php echo esc_url($request_url); ?>" class="btn btn-primary">
                            <?php esc_html_e('Start a service request', 'standard'); ?>
                            <?php icon('arrow-right', ['class' => 'w-5 h-5']); ?>
                        </a>
                        <?php if ($manual_url !== '') : ?>
                            <a href="<?php echo esc_url($manual_url); ?>" class="btn btn-secondary">
                                <?php icon('file-text', ['class' => 'w-4 h-4']); ?>
                                <?php esc_html_e('Open the manual', 'standard'); ?>
                            </a>
                        <?php endif; ?>
                    </div>

                    <?php if (!empty($meta)) :
                        // Literal class strings only: Tailwind v4 scans source for
                        // literal tokens, so a concatenated `grid-cols-$n` could be
                        // purged from the build. Map the count explicitly.
                        $meta_cols = [
                            1 => 'grid-cols-1',
                            2 => 'grid-cols-2',
                            3 => 'grid-cols-3',
                            4 => 'grid-cols-2 sm:grid-cols-4',
                        ][count($meta)] ?? 'grid-cols-2 sm:grid-cols-4';
                    ?>
                        <dl class="grid <?php echo esc_attr($meta_cols); ?> gap-x-6 gap-y-5 border-t border-blue-200 pt-6 mt-1">
                            <?php foreach ($meta as $item) : ?>
                                <div class="grid gap-1 min-w-0">
                                    <dt class="font-mono uppercase tracking-mono-meta text-blue-500" style="font-size: 10px;">
                                        <?php echo esc_html($item['label']); ?><span class="sr-only">:</span>
                                    </dt>
                                    <dd class="font-mono font-medium text-blue-900 break-words m-0" style="font-size: var(--text-heading-sm);">
                                        <?php echo esc_html($item['value']); ?>
                                    </dd>
                                </div>
                            <?php endforeach; ?>
                        </dl>
                    <?php endif; ?>
                </div>

                <?php if ($has_footprint) : ?>
                    <?php get_template_part('templates/parts/service-hub/machine-footprint', null, [
                        'image' => $footprint['image'],
                        'alt'   => $footprint['alt'],
                        'pdf'   => $footprint['pdf'],
                        'name'  => $name,
                    ]); ?>
                <?php endif; ?>

            </div>
        </div>
    </header>

    <?php if ($has_content) : ?>
        <?php foreach ($groups as $group) : ?>
            <?php if (!$group['query']->have_posts()) { continue; } ?>
            <section class="container section-compact" aria-label="<?php echo esc_attr($group['label']); ?>">
                <h2 class="font-mono font-medium uppercase tracking-wider text-blue-700 mb-6" style="font-size: var(--text-caption);">
                    <?php echo esc_html($group['label']); ?>
                    <span class="text-blue-400">&middot; <?php echo (int) $group['query']->found_posts; ?></span>
                </h2>
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <?php while ($group['query']->have_posts()) : $group['query']->the_post(); ?>
                        <?php get_template_part('templates/parts/card-post'); ?>
                    <?php endwhile; ?>
                </div>
            </section>
            <?php wp_reset_postdata(); ?>
        <?php endforeach; ?>
    <?php else : ?>
        <?php /* Empty state — a real surface, not a dead end. The machine's
                manual and brochure come from the data file and exist even with
                zero tagged WP content, so an owner still leaves with the
                document. (Content is migrating off the old site; coverage is
                uneven, so this state ships often.) */ ?>
        <section class="container section-compact" aria-labelledby="service-hub-machine-empty-title">
            <div class="border-t border-blue-200 pt-12 grid gap-6 max-w-2xl">
                <div class="grid gap-3">
                    <span class="font-mono font-medium uppercase tracking-wider text-blue-500" style="font-size: var(--text-caption);">
                        <?php esc_html_e('Start here', 'standard'); ?>
                    </span>
                    <h2 id="service-hub-machine-empty-title" class="font-sans font-medium text-heading text-blue-900 leading-tight m-0">
                        <?php
                        /* translators: %s machine name. */
                        printf(esc_html__('The %s manual is ready.', 'standard'), esc_html($name));
                        ?>
                    </h2>
                    <p class="font-sans text-blue-600 m-0" style="font-size: var(--text-body); line-height: var(--leading-body);">
                        <?php esc_html_e('We are still tagging videos and troubleshooting articles to this machine. For anything the documents do not cover, open a service request and the team will help directly.', 'standard'); ?>
                    </p>
                </div>

                <?php if ($manual_url !== '' || $brochure_url !== '') : ?>
                    <div class="border border-blue-200">
                        <?php if ($manual_url !== '') : ?>
                            <a href="<?php echo esc_url($manual_url); ?>"
                               class="group flex items-center gap-4 p-5 lg:p-6 no-underline transition-colors duration-200 hover:bg-blue-50 <?php echo $brochure_url !== '' ? 'border-b border-blue-200' : ''; ?>">
                                <?php icon('file-text', ['class' => 'w-5 h-5 text-blue-500 shrink-0', 'aria-hidden' => 'true']); ?>
                                <span class="flex-1 font-mono font-medium uppercase tracking-wider text-blue-900 transition-colors duration-200 group-hover:text-blue-500" style="font-size: var(--text-caption);">
                                    <?php esc_html_e('Operation & maintenance manual', 'standard'); ?>
                                </span>
                                <?php icon('arrow-right', ['class' => 'w-4 h-4 text-blue-400 shrink-0 transition-all duration-200 group-hover:text-blue-500 group-hover:translate-x-1', 'aria-hidden' => 'true']); ?>
                            </a>
                        <?php endif; ?>
                        <?php if ($brochure_url !== '') : ?>
                            <a href="<?php echo esc_url($brochure_url); ?>"
                               class="group flex items-center gap-4 p-5 lg:p-6 no-underline transition-colors duration-200 hover:bg-blue-50">
                                <?php icon('file-text', ['class' => 'w-5 h-5 text-blue-500 shrink-0', 'aria-hidden' => 'true']); ?>
                                <span class="flex-1 font-mono font-medium uppercase tracking-wider text-blue-900 transition-colors duration-200 group-hover:text-blue-500" style="font-size: var(--text-caption);">
                                    <?php esc_html_e('Specification brochure', 'standard'); ?>
                                </span>
                                <?php icon('arrow-right', ['class' => 'w-4 h-4 text-blue-400 shrink-0 transition-all duration-200 group-hover:text-blue-500 group-hover:translate-x-1', 'aria-hidden' => 'true']); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <div class="flex flex-wrap gap-3">
                    <a href="<?php echo esc_url($request_url); ?>" class="btn btn-md btn-secondary">
                        <?php esc_html_e('Open a service request', 'standard'); ?>
                    </a>
                    <a href="<?php echo esc_url(\Standard\Url\internal('/service-hub/#search')); ?>" class="btn btn-md btn-ghost">
                        <?php esc_html_e('Search the knowledge base', 'standard'); ?>
                    </a>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php
    get_template_part('templates/parts/cta/closer', null, [
        'title'           => __('Still need a hand?', 'standard'),
        'text'            => __('Our service team has been on the other end of the phone for more than 30 years.', 'standard'),
        'cta_primary'     => __('Talk to a service specialist', 'standard'),
        'cta_primary_url' => '/contact/',
        'section_id'      => 'service-hub-machine-closer-title',
    ]);
    ?>
</main>

<?php
get_footer();
