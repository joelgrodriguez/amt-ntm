<?php
/**
 * Machine Product — Hero Section
 *
 * Full-bleed hero with image (video-ready). Outcome-driven headline,
 * subtitle, price, and primary CTAs.
 *
 * @package Standard
 *
 * @var array{product: \WC_Product, machine: array} $args
 */

declare(strict_types=1);

$product = $args['product'] ?? null;
$machine = $args['machine'] ?? null;

if (!$product || !$machine) {
    return;
}

// TODO: Build UI
