<?php
/**
 * Machine Product — Stats Bar
 *
 * @package Standard
 * @var array{machine: array} $args
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$machine = $args['machine'] ?? [];
$stats   = $machine['stats'] ?? [];

if (empty($stats)) {
    return;
}
?>

<section class="bg-blue-900 py-10" aria-label="Key specifications">
    <div class="container">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 text-center">
            <?php foreach ($stats as $stat) : ?>
                <div class="grid gap-1">
                    <span class="font-mono font-medium text-white text-2xl lg:text-3xl"><?php echo esc_html($stat['value']); ?></span>
                    <span class="font-mono text-sm text-blue-400 uppercase tracking-wider"><?php echo esc_html($stat['label']); ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
