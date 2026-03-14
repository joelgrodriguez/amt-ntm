<?php
/**
 * Machine Product — Machine Breakdown
 *
 * @package Standard
 * @var array{machine: array} $args
 */

declare(strict_types=1);

$subsystems = [
    ['title' => 'The Forming System', 'headline' => 'Precision Forming, Panel After Panel', 'copy' => 'Roller stations with hardened tool steel shear dies.'],
    ['title' => 'The Frame', 'headline' => 'Built to Take a Beating', 'copy' => 'Welded tubular steel frame with powder-coated covers.'],
    ['title' => 'The Power Pack', 'headline' => 'Gas or Electric. Your Call.', 'copy' => 'Quick-Change Power-Pack swaps in the field.'],
    ['title' => 'The Brain', 'headline' => 'Smart Controls, Simple Operation', 'copy' => 'UNIQ Automatic Control System or manual controls.'],
];
?>

<section id="machine-breakdown" class="section" aria-labelledby="breakdown-title">
    <div class="container section-content">

        <div class="section-header">
            <p class="section-eyebrow">[Inside the Machine]</p>
            <div class="section-divider-center"></div>
            <h2 id="breakdown-title" class="section-title">[Built to Perform]</h2>
        </div>

        <?php foreach ($subsystems as $idx => $sub) :
            $is_reversed = $idx % 2 !== 0;
        ?>
            <div class="grid lg:grid-cols-2 gap-8 lg:gap-16 items-center <?php echo $is_reversed ? 'lg:[&>*:first-child]:order-2' : ''; ?>">
                <div class="bg-slate-100 aspect-video flex items-center justify-center">
                    <span class="text-slate-400 text-sm font-mono">[<?php echo esc_html($sub['title']); ?> — photo]</span>
                </div>
                <div class="grid gap-4">
                    <p class="text-sm font-semibold uppercase tracking-wider text-secondary"><?php echo esc_html($sub['title']); ?></p>
                    <h3 class="text-2xl font-bold text-slate-900 lg:text-3xl"><?php echo esc_html($sub['headline']); ?></h3>
                    <p class="text-slate-600"><?php echo esc_html($sub['copy']); ?></p>
                    <ul class="grid gap-2 mt-2">
                        <li class="flex items-start gap-2 text-sm text-slate-700">
                            <span class="w-1.5 h-1.5 rounded-full bg-secondary mt-1.5 shrink-0"></span>
                            [Spec bullet 1]
                        </li>
                        <li class="flex items-start gap-2 text-sm text-slate-700">
                            <span class="w-1.5 h-1.5 rounded-full bg-secondary mt-1.5 shrink-0"></span>
                            [Spec bullet 2]
                        </li>
                        <li class="flex items-start gap-2 text-sm text-slate-700">
                            <span class="w-1.5 h-1.5 rounded-full bg-secondary mt-1.5 shrink-0"></span>
                            [Spec bullet 3]
                        </li>
                    </ul>
                </div>
            </div>
        <?php endforeach; ?>

    </div>
</section>
