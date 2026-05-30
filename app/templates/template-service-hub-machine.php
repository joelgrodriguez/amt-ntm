<?php
/**
 * Service Hub machine mini-page. Routed by inc/service-hub-machines.php.
 *
 * Machine header + per-machine request CTA + content grouped by type.
 * Empty groups are hidden. Reuses card-post.php (no args; reads the loop).
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\ServiceHubMachines\find_machine;
use function Standard\ServiceHubMachines\get_content_groups;

$slug    = (string) get_query_var(\Standard\ServiceHubMachines\QUERY_VAR);
$machine = find_machine($slug);

if ($machine === null) {
    // Defensive: routing already 404s unknown slugs.
    get_header();
    echo '<main id="primary"></main>';
    get_footer();
    return;
}

$name        = (string) ($machine['name'] ?? $machine['short_name'] ?? $slug);
$descriptor  = (string) ($machine['descriptor'] ?? '');
$request_url = \Standard\Url\internal('/service-hub/request/?machine=' . rawurlencode($slug));
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

    <header class="pattern-dot-grid pattern-dot-grid--surface bg-blue-50 border-b border-blue-200">
        <div class="container section-compact">
            <div class="grid gap-4 max-w-4xl">
                <span class="section-eyebrow"><?php esc_html_e('Service Hub', 'standard'); ?></span>
                <h1 class="font-semibold text-heading-lg lg:text-display text-blue-900 leading-tight tracking-tight">
                    <?php echo esc_html($name); ?>
                </h1>
                <?php if ($descriptor !== '') : ?>
                    <p class="font-sans text-blue-600 max-w-2xl" style="font-size: var(--text-body); line-height: var(--leading-body);">
                        <?php echo esc_html($descriptor); ?>
                    </p>
                <?php endif; ?>
                <div class="mt-2">
                    <a href="<?php echo esc_url($request_url); ?>" class="btn btn-primary">
                        <?php esc_html_e('Start a service request for this machine', 'standard'); ?>
                    </a>
                </div>
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
        <section class="container section-compact">
            <div class="border-t border-blue-200 pt-12 grid gap-4 max-w-xl">
                <span class="font-mono font-medium uppercase tracking-wider text-blue-700" style="font-size: var(--text-caption);">
                    <?php esc_html_e('Nothing here yet', 'standard'); ?>
                </span>
                <h3 class="font-mono font-medium text-blue-900" style="font-size: var(--text-heading-sm);">
                    <?php esc_html_e('We have not tagged content for this machine yet.', 'standard'); ?>
                </h3>
                <p class="font-sans text-blue-600" style="font-size: var(--text-body); line-height: var(--leading-body);">
                    <?php esc_html_e('Open a service request and the team will help directly, or search the full library.', 'standard'); ?>
                </p>
                <div class="mt-2 flex flex-wrap gap-3">
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
