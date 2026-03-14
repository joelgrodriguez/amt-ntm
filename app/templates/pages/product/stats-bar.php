<?php
/**
 * Machine Product — Stats Bar
 *
 * Horizontal strip with 4 key stats (profiles, speed, changeover, savings).
 * Dark background, large numbers, small labels.
 *
 * @package Standard
 *
 * @var array{machine: array} $args
 */

declare(strict_types=1);

$machine = $args['machine'] ?? null;
$stats   = $machine['stats'] ?? [];

if (!$stats) {
    return;
}

// TODO: Build UI
