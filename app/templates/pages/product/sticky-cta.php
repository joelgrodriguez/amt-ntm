<?php
/**
 * Machine Product — Sticky CTA Bar
 *
 * Fixed bottom bar with machine name, price, and primary CTA.
 * Hidden initially, appears after scrolling past hero.
 * Hides when final CTA section is in view.
 * Controlled by StickyProductCTA.js (future).
 * For now: CSS-only with scroll-based visibility via Tailwind.
 *
 * @package Standard
 *
 * @var array{product: \WC_Product, machine: array} $args
 */

declare(strict_types=1);

$product = $args['product'] ?? null;
$machine = $args['machine'] ?? null;

if (!$product) {
    return;
}

$price = $product->get_price_html();
?>

<div id="sticky-cta" class="fixed bottom-0 inset-x-0 z-50 bg-slate-900/95 backdrop-blur-sm border-t border-slate-700 translate-y-full transition-transform duration-300" aria-hidden="true">
    <div class="container flex items-center justify-between py-3">
        <div class="flex items-center gap-4">
            <span class="font-bold text-white"><?php echo esc_html($product->get_name()); ?></span>
            <?php if ($price) : ?>
                <span class="text-sm text-slate-400"><?php echo wp_kses_post($price); ?></span>
            <?php endif; ?>
        </div>
        <a href="#" class="btn btn-primary btn-sm">
            <?php esc_html_e('Build & Quote', 'standard'); ?>
        </a>
    </div>
</div>
