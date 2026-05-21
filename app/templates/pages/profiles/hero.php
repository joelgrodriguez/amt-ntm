<?php
/**
 * Profiles — Page hero.
 *
 * Utility-first: H1 + one-line lede, no hero image, hairline section
 * border to seal the top. Same two-col-hero padding rhythm as the
 * other product surfaces (pt-6/12 pb-6/12).
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}
?>

<section class="border-b border-blue-200 bg-white pt-6 pb-6 lg:pt-12 lg:pb-12">
    <div class="container">
        <div class="section-header-left max-w-3xl">
            <p class="section-eyebrow"><?php esc_html_e('Catalog · Profiles', 'standard'); ?></p>
            <div class="section-divider"></div>
            <h1 class="font-mono font-medium text-blue-900 leading-tight"
                style="font-size: var(--text-heading); line-height: var(--leading-heading);">
                <?php esc_html_e('Every panel and gutter profile NTM rolls', 'standard'); ?>
            </h1>
            <p class="font-sans text-blue-600 max-w-2xl"
               style="font-size: var(--text-body); line-height: 1.6;">
                <?php esc_html_e('Roof and wall panel profiles, seamless gutter profiles, and the clip-relief and rib rollers that go with them. Each profile lists the NTM machine that rolls it.', 'standard'); ?>
            </p>
        </div>
    </div>
</section>
