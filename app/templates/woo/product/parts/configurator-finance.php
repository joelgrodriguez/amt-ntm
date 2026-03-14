<?php
/**
 * Machine Product — Configurator & Financing
 *
 * Combined section: configure your machine + financing hook.
 * Split layout — configurator left, financing right.
 * Both paths lead to quote/appointment.
 *
 * @package Standard
 * @var array{product: \WC_Product, machine: array} $args
 */

declare(strict_types=1);

$product = $args['product'] ?? null;
$machine = $args['machine'] ?? [];
$finance = $machine['finance'] ?? [];

$configurator_url = $product ? '/configurator/' . $product->get_slug() . '/' : '#';

$has_monthly = !empty($finance['monthly_price']);
$has_range   = !empty($finance['price_range']);
?>

<section class="section bg-slate-900" aria-labelledby="config-finance-title">
    <div class="container section-content">

        <div class="section-header">
            <p class="text-sm font-semibold uppercase tracking-wider text-secondary">Get Started</p>
            <h2 id="config-finance-title" class="text-3xl font-bold text-white md:text-4xl">Build It. Finance It. Own It.</h2>
            <p class="text-slate-400 max-w-2xl mx-auto">Configure your machine with the exact options you need, then explore flexible financing to make it happen.</p>
        </div>

        <div class="grid lg:grid-cols-2 gap-8 max-w-5xl mx-auto">

            <!-- Configurator -->
            <div class="border border-slate-700 bg-slate-800 p-8 grid gap-6">
                <div class="bg-slate-700 aspect-video flex items-center justify-center rounded">
                    <span class="text-slate-400 text-sm font-mono">Configurator preview</span>
                </div>
                <div class="grid gap-3">
                    <h3 class="text-xl font-bold text-white">Build Your Machine</h3>
                    <p class="text-sm text-slate-400">Choose your profiles, power pack, control system, and accessories. Get an instant quote or send your build to a specialist.</p>
                    <ul class="grid gap-2 text-sm text-slate-300">
                        <li class="flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-secondary shrink-0"></span>
                            Select profiles &amp; tooling
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-secondary shrink-0"></span>
                            Choose power pack &amp; controls
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-secondary shrink-0"></span>
                            Add accessories &amp; trailer
                        </li>
                    </ul>
                </div>
                <a href="<?php echo esc_url($configurator_url); ?>" class="btn btn-primary w-full">Open Configurator</a>
            </div>

            <!-- Financing -->
            <div class="border border-slate-700 bg-slate-800 p-8 grid gap-6">
                <div class="grid gap-2 text-center py-6">
                    <?php if ($has_monthly) : ?>
                        <p class="text-sm text-slate-400 uppercase tracking-wider">Payments as low as</p>
                        <p class="text-5xl font-bold text-white font-mono"><?php echo esc_html($finance['monthly_price']); ?><span class="text-lg text-slate-400 font-sans">/mo</span></p>
                    <?php elseif ($has_range) : ?>
                        <p class="text-sm text-slate-400 uppercase tracking-wider">Starting at</p>
                        <p class="text-4xl font-bold text-white font-mono"><?php echo esc_html($finance['price_range']); ?></p>
                    <?php endif; ?>
                    <?php if (!empty($finance['note'])) : ?>
                        <p class="text-sm text-slate-500"><?php echo esc_html($finance['note']); ?></p>
                    <?php endif; ?>
                </div>
                <div class="grid gap-3">
                    <h3 class="text-xl font-bold text-white">Flexible Financing</h3>
                    <p class="text-sm text-slate-400">Most contractors pay off their machine within the first year from increased revenue. We make it easy to get started.</p>
                    <ul class="grid gap-2 text-sm text-slate-300">
                        <li class="flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 shrink-0"></span>
                            Lease-to-own options
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 shrink-0"></span>
                            Seasonal payment plans
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 shrink-0"></span>
                            No-commitment quote in 24 hrs
                        </li>
                    </ul>
                </div>
                <a href="<?php echo esc_url('/machines/leasing-financing/'); ?>" class="btn btn-outline-light w-full">Explore Financing</a>
            </div>

        </div>

        <p class="text-center text-sm text-slate-500">Or <a href="<?php echo esc_url('/contact/'); ?>" class="text-white underline">talk to a specialist</a> to discuss your specific needs.</p>

    </div>
</section>
