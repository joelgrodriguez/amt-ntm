<?php
/**
 * Machines Page — Key Differentiators
 *
 * Three-card grid highlighting NTM's key selling points.
 *
 * @package Standard
 *
 * @usage Machines Page (page-machines.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\MachinesData\get_differentiators;

$differentiators = get_differentiators();
?>

<section class="section" aria-labelledby="differentiators-title">
    <div class="container section-content">

        <div class="grid gap-4 max-w-3xl">
            <p class="font-mono text-xs uppercase tracking-[0.18em] text-blue-500">
                <?php esc_html_e('07 / WHY NTM', 'standard'); ?>
            </p>
            <h2 id="differentiators-title" class="text-4xl font-medium tracking-tight text-blue-900 md:text-5xl">
                <?php esc_html_e('The NTM Difference', 'standard'); ?>
            </h2>
        </div>

        <ol class="grid border-t border-blue-200" role="list">
            <?php foreach ($differentiators as $idx => $item) : ?>
                <li class="grid gap-4 py-8 border-b border-blue-200 md:grid-cols-[auto_1fr_2fr] md:items-baseline md:gap-10 lg:gap-16">
                    <span class="font-mono text-sm font-medium text-blue-500 uppercase tracking-wider" aria-hidden="true">
                        <?php echo esc_html(sprintf('%02d', $idx + 1)); ?>
                    </span>
                    <h3 class="text-xl font-medium text-blue-900 md:text-2xl">
                        <?php echo esc_html($item['title']); ?>
                    </h3>
                    <p class="text-blue-600 max-w-prose">
                        <?php echo esc_html($item['text']); ?>
                    </p>
                </li>
            <?php endforeach; ?>
        </ol>

    </div>
</section>
