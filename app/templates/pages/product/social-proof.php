<?php
/**
 * Machine Product — Social Proof
 *
 * Customer testimonials and ROI stats. Dark background section.
 *
 * @package Standard
 *
 * @var array{machine: array} $args
 */

declare(strict_types=1);

$machine      = $args['machine'] ?? null;
$testimonials = $machine['testimonials'] ?? [];

if (!$testimonials) {
    return;
}

// TODO: Build UI
