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
?>

<div class="bg-secondary py-6">
    <div class="container flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <p class="text-white font-bold text-lg">[As low as $X,XXX/mo]</p>
            <p class="text-white/80 text-sm hidden md:block">[Flexible financing — lease-to-own, seasonal plans, quick approval]</p>
        </div>
        <a href="#" class="btn btn-sm bg-white text-secondary hover:bg-slate-100 shrink-0">[Explore Financing]</a>
    </div>
</div>
