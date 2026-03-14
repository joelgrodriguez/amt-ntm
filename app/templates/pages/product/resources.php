<?php
/**
 * Machine Product — Resources & Support
 *
 * Downloads (manual, brochure), video links, service & training.
 *
 * @package Standard
 *
 * @var array{machine: array} $args
 */

declare(strict_types=1);

$machine   = $args['machine'] ?? null;
$resources = $machine['resources'] ?? [];

if (!$resources) {
    return;
}

// TODO: Build UI
