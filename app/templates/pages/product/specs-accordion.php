<?php
/**
 * Machine Product — Specifications Accordion
 *
 * Collapsible sections for full technical specs.
 * Content stays in DOM for SEO (hidden via CSS, not removed).
 * Uses native <details>/<summary> elements.
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
