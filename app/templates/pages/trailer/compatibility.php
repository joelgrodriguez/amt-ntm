<?php
/**
 * Trailer Page — Compatibility Matrix
 *
 * Answers the one question a buyer actually has: "which trailer do I need for
 * my machine?" Reads the inverted compatibility map from the trailer data
 * module (trailer → machines it runs), so it can never disagree with the
 * machine-page strip or the lineup grid.
 *
 * Mobile-first: each trailer is a stacked card with its compatible machines as
 * tappable chips (≥44px targets). At md: the same rows read as a spec-sheet
 * grid — trailer + capacity on the left, machine chips on the right.
 *
 * @package Standard
 *
 * @usage page-trailer.php
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$rows = function_exists('Standard\\TrailerData\\get_matrix_rows')
    ? \Standard\TrailerData\get_matrix_rows()
    : [];

if (empty($rows)) {
    return;
}
?>

<section id="trailer-compatibility" class="section scroll-mt-24" aria-labelledby="trailer-compatibility-title">
    <div class="container section-content">

        <div class="section-header-left max-w-2xl">
            <p class="section-eyebrow"><?php esc_html_e('Compatibility', 'standard'); ?></p>
            <div class="section-divider"></div>
            <h2 id="trailer-compatibility-title" class="section-title text-balance">
                <?php esc_html_e('Which Trailer Fits Your Machine', 'standard'); ?>
            </h2>
            <p class="section-subtitle text-pretty">
                <?php esc_html_e('Match your machine to its trailer family. Every roof, wall, and box-gutter machine has a home; tap a machine to open its page.', 'standard'); ?>
            </p>
        </div>

        <div class="grid gap-px border border-slate-200 bg-slate-200">
            <?php foreach ($rows as $row) : ?>
                <div class="grid gap-4 bg-white p-6 md:grid-cols-[minmax(0,16rem)_1fr] md:items-center md:gap-8 md:p-7">

                    <div class="grid gap-1">
                        <span class="font-mono font-medium text-sm uppercase tracking-mono-label text-blue-900">
                            <?php echo esc_html($row['model']); ?>
                        </span>
                        <span class="font-mono text-[11px] uppercase tracking-mono-label text-blue-400">
                            <?php echo esc_html($row['capacity']); ?> &middot; <?php echo esc_html($row['axle']); ?>
                        </span>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <?php foreach ($row['machines'] as $machine) : ?>
                            <?php if (!empty($machine['url'])) : ?>
                                <a href="<?php echo esc_url($machine['url']); ?>"
                                   class="inline-flex min-h-[44px] items-center gap-2 border border-slate-200 bg-slate-50 px-4 font-sans text-sm text-blue-900 hover:border-blue-300 hover:bg-blue-50">
                                    <?php echo esc_html($machine['name']); ?>
                                    <?php icon('arrow-right', ['class' => 'w-3.5 h-3.5 text-blue-500']); ?>
                                </a>
                            <?php else : ?>
                                <span class="inline-flex min-h-[44px] items-center border border-slate-200 bg-slate-50 px-4 font-sans text-sm text-blue-900">
                                    <?php echo esc_html($machine['name']); ?>
                                </span>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>

                </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>
