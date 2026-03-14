<?php
/**
 * Machine Product — Machine Comparison
 *
 * Side-by-side comparison with related machines in the same category.
 * Highlights current machine with "You're viewing" badge.
 *
 * @package Standard
 *
 * @var array{product: \WC_Product, machine: array} $args
 */

declare(strict_types=1);

$product     = $args['product'] ?? null;
$machine     = $args['machine'] ?? null;
$compare_with = $machine['compare_with'] ?? [];

if (!$compare_with) {
    return;
}

// TODO: Build UI
