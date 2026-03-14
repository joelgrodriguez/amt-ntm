<?php
/**
 * Machine Product — Machine Breakdown
 *
 * Subsystem sections: forming system, frame, power pack, brain.
 * Alternating image/content layout with contextual photography.
 *
 * @package Standard
 *
 * @var array{machine: array} $args
 */

declare(strict_types=1);

$machine   = $args['machine'] ?? null;
$breakdown = $machine['breakdown'] ?? [];

if (!$breakdown) {
    return;
}

// TODO: Build UI
