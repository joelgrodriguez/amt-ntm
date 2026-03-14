<?php
/**
 * Machine Product — Final CTA
 *
 * Three-card bottom CTA: Build & Finance, Request a Quote, See It In Action.
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
