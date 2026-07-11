<?php
/**
 * Trailer Page — The Lineup
 *
 * The conversion beat: the five sellable trailers. Two 12,000 lb tandem-axle
 * decks (TR12-D, TR12L) plus the single-reel-rack TR12XL, and the 23,000 lb
 * TR23 / TR23G for the triple overhead reel rack. Real prices (live from
 * WooCommerce), real product renders, real specs — all read from the trailer
 * data module so this grid never drifts from the compatibility matrix or the
 * machine-page strip.
 *
 * Industrial spec-sheet cards in a responsive grid: single column on mobile,
 * scaling to a three-up rack on desktop. Product renders sit in light blue-50
 * wells per DESIGN.md §6.
 *
 * @package Standard
 *
 * @usage page-trailer.php
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$trailers = function_exists('Standard\\TrailerData\\get_all_trailer_rows')
    ? \Standard\TrailerData\get_all_trailer_rows()
    : [];

if (empty($trailers)) {
    return;
}
?>

<section id="trailer-models" class="section bg-blue-900 scroll-mt-24" aria-labelledby="trailer-models-title">
    <div class="container section-content">

        <div class="section-header-left max-w-2xl">
            <p class="section-eyebrow"><?php esc_html_e('The lineup', 'standard'); ?></p>
            <div class="section-divider"></div>
            <h2 id="trailer-models-title" class="section-title text-white text-balance">
                <?php esc_html_e('Five Trailers, Matched to the Machine', 'standard'); ?>
            </h2>
            <p class="section-subtitle text-blue-300 text-pretty">
                <?php esc_html_e('From a 12,000 lb tandem-axle deck for a single machine up to the 23,000 lb triple-reel-rack trailers. The right one depends on your machine, your reel rack, and your truck.', 'standard'); ?>
            </p>
        </div>

        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            <?php foreach ($trailers as $trailer) : ?>
                <article class="flex flex-col gap-5 border border-blue-700 bg-blue-900 p-6 md:p-7">

                    <div class="flex items-baseline gap-3">
                        <span class="font-mono font-medium text-sm uppercase tracking-mono-label text-white">
                            <?php echo esc_html($trailer['model']); ?>
                        </span>
                        <span class="font-mono text-[11px] uppercase tracking-mono-label text-blue-400">
                            <?php echo esc_html($trailer['capacity']); ?>
                        </span>
                    </div>

                    <div class="aspect-[16/10] overflow-hidden border border-blue-700 bg-blue-50">
                        <?php if (!empty($trailer['image'])) : ?>
                            <?php \Standard\Images\responsive_image($trailer['image'], sprintf(__('NTM %s trailer', 'standard'), $trailer['model']), 'medium', [
                                'class'   => 'w-full h-full object-contain',
                                'loading' => 'lazy',
                                'sizes'   => '(min-width: 1024px) 33vw, (min-width: 768px) 50vw, 100vw',
                            ]); ?>
                        <?php else : ?>
                            <div class="flex h-full items-center justify-center">
                                <span class="font-mono text-[11px] uppercase tracking-mono-label text-blue-400">
                                    <?php echo esc_html($trailer['model']); ?>
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <p class="font-sans text-[15px] leading-relaxed text-blue-200 max-w-prose">
                        <?php echo esc_html($trailer['summary']); ?>
                    </p>

                    <dl class="grid grid-cols-2 gap-px bg-blue-700 border border-blue-700 mt-auto">
                        <div class="bg-blue-900 p-4">
                            <dt class="font-mono text-[11px] uppercase tracking-mono-label text-blue-400 mb-1">
                                <?php esc_html_e('Axles', 'standard'); ?>
                            </dt>
                            <dd class="font-mono font-medium text-sm text-white">
                                <?php echo esc_html($trailer['axle']); ?>
                            </dd>
                        </div>
                        <div class="bg-blue-900 p-4">
                            <dt class="font-mono text-[11px] uppercase tracking-mono-label text-blue-400 mb-1">
                                <?php esc_html_e('Built for', 'standard'); ?>
                            </dt>
                            <dd class="font-mono font-medium text-sm text-white">
                                <?php echo esc_html($trailer['rack']); ?>
                            </dd>
                        </div>
                    </dl>

                    <div class="flex flex-wrap items-baseline justify-between gap-3 border-t border-blue-700 pt-5">
                        <div class="flex items-baseline gap-2">
                            <span class="font-mono text-[11px] uppercase tracking-mono-label text-blue-400">
                                <?php esc_html_e('Starting at', 'standard'); ?>
                            </span>
                            <span class="font-sans font-medium text-xl text-white">
                                <?php echo esc_html($trailer['price']); ?>
                            </span>
                        </div>
                        <a href="<?php echo esc_url($trailer['url']); ?>" class="font-mono text-xs font-medium uppercase tracking-wider text-blue-200 hover:text-white inline-flex items-center gap-1">
                            <?php esc_html_e('View details', 'standard'); ?>
                            <?php icon('arrow-right', ['class' => 'w-3.5 h-3.5']); ?>
                        </a>
                    </div>

                </article>
            <?php endforeach; ?>
        </div>

    </div>
</section>
