<?php
/**
 * Machine Product — Profile Selector
 *
 * Interactive grid of panel profiles available for this machine.
 * Filterable by category (mechanical seam, snap-lock, etc.).
 *
 * @package Standard
 *
 * @var array{product: \WC_Product, machine: array} $args
 */

declare(strict_types=1);

$product = $args['product'] ?? null;
$machine = $args['machine'] ?? null;

if (!$machine) {
    return;
}

// TODO: Build UI
