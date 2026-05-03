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

if (!defined('ABSPATH')) {
    exit;
}

$product = $args['product'] ?? null;
$machine = $args['machine'] ?? [];
$finance = $machine['finance'] ?? [];

$configurator_url = $product ? '/configurator/' . $product->get_slug() . '/' : '#';

$has_monthly = !empty($finance['monthly_price']);
$has_range   = !empty($finance['price_range']);
?>

<section class="section bg-blue-900 border-b border-blue-700" aria-labelledby="config-finance-title">
    <div class="container section-content">

        <div class="section-header">
            <p class="section-eyebrow">Get Started</p>
            <h2 id="config-finance-title" class="section-title text-white">Build It. Finance It. Own It.</h2>
            <p class="text-blue-400 max-w-2xl mx-auto">Configure your machine with the exact options you need, then explore flexible financing to make it happen.</p>
        </div>

        <div class="grid lg:grid-cols-2 gap-8 max-w-5xl mx-auto">

            <!-- Configurator -->
            <div class="border border-blue-700 bg-blue-800 p-8 grid gap-6">
                <div class="bg-blue-700 aspect-video flex items-center justify-center">
                    <span class="text-blue-400 text-sm font-mono">Configurator preview</span>
                </div>
                <div class="grid gap-3">
                    <h3 class="text-xl font-medium text-white">Build Your Machine</h3>
                    <p class="text-sm text-blue-400">Choose your profiles, power pack, control system, and accessories. Get an instant quote or send your build to a specialist.</p>
                    <ul class="spec-list text-blue-300">
                        <li>Select profiles &amp; tooling</li>
                        <li>Choose power pack &amp; controls</li>
                        <li>Add accessories &amp; trailer</li>
                    </ul>
                </div>
                <a href="<?php echo esc_url($configurator_url); ?>" class="btn btn-primary w-full">Open Configurator</a>
            </div>

            <!-- Financing -->
            <div class="border border-blue-700 bg-blue-800 p-8 grid gap-6">
                <div class="grid gap-2 text-center py-6">
                    <?php if ($has_monthly) : ?>
                        <p class="text-sm text-blue-400 uppercase tracking-wider">Payments as low as</p>
                        <p class="text-5xl font-medium text-white font-mono"><?php echo esc_html($finance['monthly_price']); ?><span class="text-lg text-blue-400 font-sans">/mo</span></p>
                    <?php elseif ($has_range) : ?>
                        <p class="text-sm text-blue-400 uppercase tracking-wider">Starting at</p>
                        <p class="text-4xl font-medium text-white font-mono"><?php echo esc_html($finance['price_range']); ?></p>
                    <?php endif; ?>
                    <?php if (!empty($finance['note'])) : ?>
                        <p class="text-sm text-blue-500"><?php echo esc_html($finance['note']); ?></p>
                    <?php endif; ?>
                </div>
                <div class="grid gap-3">
                    <h3 class="text-xl font-medium text-white">Flexible Financing</h3>
                    <p class="text-sm text-blue-400">Most contractors pay off their machine within the first year from increased revenue. We make it easy to get started.</p>
                    <ul class="spec-list spec-list--green text-blue-300">
                        <li>Lease-to-own options</li>
                        <li>Seasonal payment plans</li>
                        <li>No-commitment quote in 24 hrs</li>
                    </ul>
                </div>
                <a href="<?php echo esc_url('/machines/leasing-financing/'); ?>" class="btn btn-outline-light w-full">Explore Financing</a>
            </div>

        </div>

        <p class="text-center text-sm text-blue-500">Or <a href="<?php echo esc_url('/contact/'); ?>" class="text-white underline">talk to a specialist</a> to discuss your specific needs.</p>

    </div>
</section>
