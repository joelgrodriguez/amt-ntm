<?php
/**
 * Machine Product — Stats Bar
 *
 * @package Standard
 * @var array{machine: array} $args
 */

declare(strict_types=1);
?>

<section class="bg-slate-900 py-10" aria-label="Key specifications">
    <div class="container">
        <p class="text-xs text-slate-500 font-mono uppercase tracking-widest mb-4">[Stats Bar — 4 key numbers]</p>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 text-center">
            <?php foreach (['16 | Panel Profiles', '25 min | Tooling Changeover', '75 ft/min | Max Speed', '$2.25/sq ft | Avg. Savings'] as $stat) :
                [$value, $label] = explode(' | ', $stat);
            ?>
                <div class="grid gap-1">
                    <span class="text-3xl font-bold text-white lg:text-4xl"><?php echo esc_html($value); ?></span>
                    <span class="text-sm text-slate-400 uppercase tracking-wider"><?php echo esc_html($label); ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
