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

use function Standard\MachinesData\get_differentiators;

$differentiators = get_differentiators();
?>

<section class="section bg-slate-50 pattern-dot-grid gradient-fade-bottom-sm" aria-labelledby="differentiators-title">
    <div class="container section-content">

        <div class="section-header">
            <p class="section-eyebrow">
                <?php esc_html_e('Why NTM', 'standard'); ?>
            </p>
            <div class="section-divider-center"></div>
            <h2 id="differentiators-title" class="section-title">
                <?php esc_html_e('The NTM Difference', 'standard'); ?>
            </h2>
        </div>

        <div class="grid gap-8 sm:grid-cols-2 md:grid-cols-3">
            <?php foreach ($differentiators as $item) : ?>
                <div class="grid gap-4 content-start text-center p-8 bg-white border border-slate-200">
                    <div class="flex justify-center">
                        <?php icon($item['icon'], ['class' => 'w-8 h-8 text-primary']); ?>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900">
                        <?php echo esc_html($item['title']); ?>
                    </h3>
                    <p class="text-slate-600">
                        <?php echo esc_html($item['text']); ?>
                    </p>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>
