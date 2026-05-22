<?php
/**
 * Manuals — Page hero.
 *
 * Same asymmetric two-column rhythm as the profiles hero: H1 + lede on
 * the left, quick-link nav to the catalog sections on the right.
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$hero_nav = [
    [
        'href'  => '#manuals-gutter',
        'label' => __('Gutter Machines', 'standard'),
    ],
    [
        'href'  => '#manuals-roof-wall',
        'label' => __('Roof & Wall Panel Machines', 'standard'),
    ],
];
?>

<section class="pattern-square-grid border-b border-blue-200 bg-blue-50 pt-6 pb-6 lg:pt-12 lg:pb-12">
    <div class="pattern-square-grid__overlay pattern-square-grid__overlay--top-left" aria-hidden="true"></div>
    <div class="pattern-square-grid__overlay pattern-square-grid__overlay--bottom-right" aria-hidden="true"></div>
    <div class="container grid gap-8 lg:grid-cols-[1fr_auto] lg:items-end lg:gap-16">

        <div class="section-header-left max-w-3xl">
            <p class="section-eyebrow"><?php esc_html_e('Manuals', 'standard'); ?></p>
            <div class="section-divider"></div>
            <h1 class="font-mono font-medium text-blue-900 leading-tight"
                style="font-size: var(--text-heading); line-height: var(--leading-heading);">
                <?php esc_html_e('Every Operator and Setup Manual NTM Publishes', 'standard'); ?>
            </h1>
            <p class="font-sans text-blue-600 max-w-2xl"
               style="font-size: var(--text-body); line-height: 1.6;">
                <?php esc_html_e('PDF manuals for every NTM gutter machine, panel machine, and controller. Filter by machine to find yours fast.', 'standard'); ?>
            </p>
        </div>

        <nav class="grid gap-2 lg:w-72" aria-label="<?php esc_attr_e('Jump to manual type', 'standard'); ?>">
            <?php foreach ($hero_nav as $item) : ?>
                <a href="<?php echo esc_attr($item['href']); ?>"
                   class="group flex items-center justify-between gap-3 px-4 py-3 bg-white border border-blue-200 no-underline transition-colors duration-200 hover:border-blue-500 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2">
                    <span class="font-mono font-medium uppercase tracking-widest text-caption text-blue-900 group-hover:text-blue-500 transition-colors truncate">
                        <?php echo esc_html($item['label']); ?>
                    </span>
                    <span class="text-blue-400 group-hover:text-blue-500 group-hover:translate-x-0.5 transition-all shrink-0" aria-hidden="true">
                        <?php icon('arrow-right', ['class' => 'w-4 h-4']); ?>
                    </span>
                </a>
            <?php endforeach; ?>
        </nav>

    </div>
</section>
