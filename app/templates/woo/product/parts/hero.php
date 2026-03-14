<?php
/**
 * Machine Product — Hero Section
 *
 * @package Standard
 * @var array{product: \WC_Product, machine: array} $args
 */

declare(strict_types=1);

$product = $args['product'] ?? null;
$machine = $args['machine'] ?? null;

if (!$product) {
    return;
}
?>

<section id="machine-hero" class="relative min-h-[70vh] flex items-end overflow-hidden bg-slate-800" aria-labelledby="machine-hero-title">
    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-slate-950/40 to-transparent"></div>

    <div class="container relative z-10 pb-16 pt-32 grid gap-6 max-w-3xl">
        <p class="text-xs text-slate-400 font-mono uppercase tracking-widest">[Hero Section — bg video / image]</p>
        <h1 id="machine-hero-title" class="text-4xl font-bold text-white md:text-5xl lg:text-6xl">
            [Outcome Headline]
        </h1>
        <p class="text-lg text-slate-200 md:text-xl">[Subtitle / value proposition]</p>
        <p class="text-sm text-slate-300 uppercase tracking-wider">Starting at <span class="text-white font-semibold">[Price]</span></p>
        <div class="flex gap-4 mt-2">
            <a href="#" class="btn btn-primary">Build & Quote</a>
            <a href="#machine-breakdown" class="btn btn-outline-light">Explore</a>
        </div>
    </div>
</section>
