<?php
/**
 * Machine Product — Sticky CTA Bar
 *
 * Fixed bottom bar with machine name, price, and primary CTA.
 * Hidden initially, appears after scrolling past hero.
 * Hides when final CTA section is in view.
 * Controlled by StickyProductCTA.js.
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

// TODO: Build UI
