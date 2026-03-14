<?php
/**
 * Machine Product — Accessories & Equipment
 *
 * Curated grid of recommended accessories for this machine.
 *
 * @package Standard
 *
 * @var array{product: \WC_Product, machine: array} $args
 */

declare(strict_types=1);

$product     = $args['product'] ?? null;
$machine     = $args['machine'] ?? null;
$accessories = $machine['featured_accessories'] ?? [];

if (!$accessories) {
    return;
}

// TODO: Build UI
