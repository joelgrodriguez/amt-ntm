<?php
/**
 * Footprints — Page hero.
 *
 * Mirrors the Profiles landing hero (single-column on bg-blue-50 with
 * the structural dot-grid). Different copy, same rhythm so the two
 * landings read as siblings under /machines/.
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}
?>

<section class="pattern-dot-grid pattern-dot-grid--surface border-b border-blue-200 bg-blue-50 pt-6 pb-6 lg:pt-12 lg:pb-12">
    <div class="container">
        <div class="section-header-left max-w-3xl">
            <p class="section-eyebrow"><?php esc_html_e('Footprints', 'standard'); ?></p>
            <div class="section-divider"></div>
            <h1 class="font-semibold text-heading-lg lg:text-display text-blue-900 leading-tight tracking-tight">
                <?php esc_html_e('Plan the Job Around the Machine', 'standard'); ?>
            </h1>
            <p class="font-sans text-blue-600 max-w-2xl" style="font-size: var(--text-body); line-height: var(--leading-body);">
                <?php esc_html_e('Plan-view footprints and shipping dimensions for every NTM portable rollforming and gutter machine. Use them to lay out your shop, plan delivery, or scope a job site.', 'standard'); ?>
            </p>
        </div>
    </div>
</section>
