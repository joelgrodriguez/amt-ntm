<?php
/**
 * Machine Product — Blueprint / Footprint
 *
 * @package Standard
 * @var array{machine: array} $args
 */

declare(strict_types=1);
?>

<section class="bg-slate-950 section" aria-labelledby="blueprint-title">
    <div class="container section-content">

        <div class="section-header">
            <p class="text-sm font-semibold uppercase tracking-wider text-emerald-400">[Engineering Specs]</p>
            <h2 id="blueprint-title" class="text-3xl font-bold text-white md:text-4xl">[Machine Footprint]</h2>
        </div>

        <div class="border border-slate-700 aspect-[16/7] flex items-center justify-center mx-auto max-w-4xl">
            <span class="text-slate-500 text-sm font-mono">[Blueprint SVG — monoline engineering drawing]</span>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6 max-w-4xl mx-auto">
            <?php foreach (['Length' => "14'4\"", 'Width' => "5'2\"", 'Height' => "4'3\"", 'Weight' => '2,830 lbs', 'w/ Slitter' => "15'4\"", 'No Rack' => "2'6\""] as $label => $value) : ?>
                <div class="text-center">
                    <span class="block text-lg font-bold text-white font-mono"><?php echo esc_html($value); ?></span>
                    <span class="block text-xs text-slate-500 uppercase tracking-wider mt-1"><?php echo esc_html($label); ?></span>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="border-t border-slate-800 pt-8">
            <p class="text-sm font-semibold text-slate-400 uppercase tracking-wider text-center mb-6">[On Trailer]</p>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 max-w-3xl mx-auto">
                <?php foreach (['Length' => "18'11\"", 'Width' => "7'2½\"", 'Height' => "6'3\"", 'Weight' => '5,090 lbs'] as $label => $value) : ?>
                    <div class="text-center">
                        <span class="block text-lg font-bold text-white font-mono"><?php echo esc_html($value); ?></span>
                        <span class="block text-xs text-slate-500 uppercase tracking-wider mt-1"><?php echo esc_html($label); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

    </div>
</section>
