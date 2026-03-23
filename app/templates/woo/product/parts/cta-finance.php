<?php
/**
 * Machine Product — CTA Strip: Financing
 *
 * Slim CTA bar after stats section. Catches early-interest buyers.
 *
 * @package Standard
 * @var array{product: \WC_Product, machine: array} $args
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$machine = $args['machine'] ?? [];
$finance = $machine['finance'] ?? null;

if (!$finance) {
    return;
}

$label = '';
if (!empty($finance['monthly_price'])) {
    $label = sprintf('As low as %s', $finance['monthly_price']);
} elseif (!empty($finance['price_range'])) {
    $label = sprintf('Starting at %s', $finance['price_range']);
} else {
    return;
}

$note = !empty($finance['note'])
    ? $finance['note']
    : 'Flexible financing — lease-to-own, seasonal plans, quick approval';
?>

<div class="bg-primary py-6">
    <div class="container flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <p class="text-white font-bold text-lg"><?php echo esc_html($label); ?></p>
            <p class="text-white/80 text-sm hidden md:block"><?php echo esc_html($note); ?></p>
        </div>
        <a href="<?php echo esc_url('/machines/leasing-financing/'); ?>" class="btn btn-sm bg-white text-primary hover:bg-slate-100 shrink-0">Explore Financing</a>
    </div>
</div>
