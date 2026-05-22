<?php
/**
 * Resources — Page hero.
 *
 * Same asymmetric two-column rhythm as the profiles and manuals heroes.
 * Right rail is a typographic index, not a quick-nav, because resources
 * don't subdivide into useful sub-categories. Two anchors only:
 * Featured and Full Library.
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$hero_nav = [
    [
        'href'  => '#resources-featured',
        'label' => __('Featured Tools', 'standard'),
    ],
    [
        'href'  => '#resources-library',
        'label' => __('Full Library', 'standard'),
    ],
];
?>

<section class="pattern-square-grid border-b border-blue-200 bg-blue-50 pt-6 pb-6 lg:pt-12 lg:pb-12">
    <div class="pattern-square-grid__overlay" aria-hidden="true"></div>
    <div class="container grid gap-8 lg:grid-cols-[1fr_auto] lg:items-end lg:gap-16">

        <div class="section-header-left max-w-3xl">
            <p class="section-eyebrow"><?php esc_html_e('Resources', 'standard'); ?></p>
            <div class="section-divider"></div>
            <h1 class="font-mono font-medium text-heading lg:text-heading-lg text-blue-900 leading-tight tracking-tight">
                <?php esc_html_e('Tools, Training, and Reference', 'standard'); ?>
            </h1>
        </div>

        <nav class="grid gap-2 lg:w-72" aria-label="<?php esc_attr_e('Jump to section', 'standard'); ?>">
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
