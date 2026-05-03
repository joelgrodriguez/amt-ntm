<?php
/**
 * Machine Product — CTA Strip: Configurator
 *
 * Slim CTA bar after detail sections. Catches engaged buyers.
 *
 * @package Standard
 * @var array{product: \WC_Product, machine: array} $args
 */

declare(strict_types=1);
if (!defined('ABSPATH')) {
    exit;
}

?>

<div class="bg-blue-500 py-6">
    <div class="container flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <p class="text-white font-medium text-lg">[Build Your Machine]</p>
            <p class="text-white/80 text-sm hidden md:block">[Choose profiles, power, controls & accessories — get an instant quote]</p>
        </div>
        <a href="#" class="btn btn-sm bg-white text-blue-500 hover:bg-blue-100 shrink-0">[Open Configurator]</a>
    </div>
</div>
