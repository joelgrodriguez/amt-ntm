<?php
/**
 * Profiles — Page hero.
 *
 * Single-column hero on bg-blue-50 with the structural dot-grid. Hairline
 * structural border seals the top.
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
            <p class="section-eyebrow"><?php esc_html_e('Profiles', 'standard'); ?></p>
            <div class="section-divider"></div>
            <h1 class="font-semibold text-heading-lg lg:text-display text-blue-900 leading-tight tracking-tight">
                <?php esc_html_e('Every Profile NTM Rolls', 'standard'); ?>
            </h1>
        </div>
    </div>
</section>
