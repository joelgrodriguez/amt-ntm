<?php
/**
 * Machine Product — Gallery / Product Rotator
 *
 * v1: Multi-angle static gallery with thumbnail switcher.
 * v2: Drag-to-rotate image sequence (ProductRotator.js).
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
