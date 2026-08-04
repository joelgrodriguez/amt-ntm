<?php
/**
 * Clip relief and rib-roller tooling listed separately from panel profiles.
 *
 * @package Standard
 * @var array{rib_rollers: array} $args
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$rib_rollers = $args['rib_rollers'] ?? [];

if (!is_array($rib_rollers) || empty($rib_rollers)) {
    return;
}
?>

<div class="mt-12 border-t border-blue-200 pt-10 lg:mt-16 lg:pt-12">
    <div class="section-header-left mb-8 max-w-2xl">
        <p class="section-eyebrow"><?php esc_html_e('Panel Finish Tooling', 'standard'); ?></p>
        <div class="section-divider"></div>
        <h3 class="font-sans font-semibold text-blue-900 leading-tight tracking-tight"
            style="font-size: var(--text-heading-sm); line-height: var(--leading-heading-sm);">
            <?php esc_html_e('Rib Rollers', 'standard'); ?>
        </h3>
        <p class="section-subtitle">
            <?php esc_html_e('Clip relief and rib rollers are tooling options for compatible panels — not additional panel profiles.', 'standard'); ?>
        </p>
    </div>

    <ul class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4 list-none p-0 m-0">
        <?php foreach ($rib_rollers as $rib_roller) : ?>
            <li>
                <?php get_template_part('templates/parts/card-profile', null, [
                    'profile' => $rib_roller,
                    'context' => 'grid',
                ]); ?>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
