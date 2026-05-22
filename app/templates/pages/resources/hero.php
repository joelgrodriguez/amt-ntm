<?php
/**
 * Resources — Page hero.
 *
 * Single-column hero on bg-blue-50.
 * No right-rail anchor nav: resources don't subdivide into useful sub-
 * categories, so jump-links to "Featured" and "Full Library" weren't
 * earning their pixels.
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}
?>

<section class="border-b border-blue-200 bg-blue-50 pt-6 pb-6 lg:pt-12 lg:pb-12">
    <div class="container">
        <div class="section-header-left max-w-3xl">
            <p class="section-eyebrow"><?php esc_html_e('Resources', 'standard'); ?></p>
            <div class="section-divider"></div>
            <h1 class="font-mono font-medium text-heading-lg lg:text-display text-blue-900 leading-tight tracking-tight">
                <?php esc_html_e('NTM Operator Tools', 'standard'); ?>
            </h1>
        </div>
    </div>
</section>
