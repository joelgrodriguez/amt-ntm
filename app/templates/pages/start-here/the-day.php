<?php
/**
 * Start Here — What the Work Looks Like
 *
 * Reassurance for the nervous first-timer: the job is a short, learnable
 * sequence, not a mystery. This is the page's highest-value visual
 * section, so each step carries a real jobsite photo (coil, machine,
 * install) instead of being a text-only list. The 01/02/03 numbering is
 * honest here because this genuinely is an ordered sequence.
 *
 * Was a thin value-prop-cards delegate; rebuilt as a bespoke photo-step
 * layout so a beginner can see the work, not just read about it. Images
 * come from the live media library via responsive_image (srcset + lazy).
 *
 * @package Standard
 *
 * @usage Start Here (page-start-here.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

// One real photo per step. Order is the actual sequence of a job:
// bring coil, roll the panel, install and bill.
$steps = [
    [
        'label' => __('Source', 'standard'),
        'title' => __('Bring the coil to the job', 'standard'),
        'text'  => __('You buy flat metal coil by the pound, far cheaper than finished panels, and haul it to the site on a trailer. No factory, no warehouse, no waiting on a supplier’s lead time.', 'standard'),
        'image' => content_url('/uploads/2024/10/lifting-coil.jpg'),
        'alt'   => __('Coils of metal being lifted off a trailer at a jobsite', 'standard'),
    ],
    [
        'label' => __('Roll', 'standard'),
        'title' => __('Roll the exact panels or gutters you need', 'standard'),
        'text'  => __('The machine forms each panel or gutter to length on the spot, only what the job calls for. Training comes with the machine, so you are running real product within days, not months.', 'standard'),
        'image' => content_url('/uploads/2022/05/Person-shearing-gutter-on-MACH-II-machine.jpg'),
        'alt'   => __('A crew running a seamless gutter off an NTM MACH II machine in a trailer', 'standard'),
    ],
    [
        'label' => __('Install &amp; bill', 'standard'),
        'title' => __('Install it and get paid', 'standard'),
        'text'  => __('You install the panels or gutters you just made and bill for the finished work. The margin that used to go to a panel supplier stays in your pocket on every job.', 'standard'),
        'image' => content_url('/uploads/2023/04/Gutter-installation.jpg'),
        'alt'   => __('A contractor installing a seamless gutter along a metal roof edge', 'standard'),
    ],
];
?>

<section class="section bg-blue-900 text-white" aria-labelledby="start-here-day-title">
    <div class="container section-content">

        <div class="section-header-left max-w-2xl">
            <h2 id="start-here-day-title" class="section-title text-white">
                <?php esc_html_e('From Coil to Cash, on the Jobsite', 'standard'); ?>
            </h2>
            <p class="section-subtitle text-blue-200 text-pretty">
                <?php esc_html_e('Three steps, start to finish. This is the whole job, from raw metal to a paid invoice.', 'standard'); ?>
            </p>
        </div>

        <ol class="sh-reveal-row grid gap-8 md:grid-cols-3 lg:gap-10" role="list">
            <?php foreach ($steps as $idx => $step) : ?>
                <li class="sh-reveal flex flex-col border border-blue-800 bg-blue-800" data-reveal>
                    <div class="aspect-video overflow-hidden bg-blue-900">
                        <?php
                        \Standard\Images\responsive_image(
                            $step['image'],
                            $step['alt'],
                            'large',
                            ['class' => 'h-full w-full object-cover']
                        );
                        ?>
                    </div>
                    <div class="grid gap-3 p-6 lg:p-8">
                        <div class="flex items-center gap-3 font-mono text-xs uppercase tracking-wider text-blue-300">
                            <span><?php echo esc_html(sprintf('%02d', $idx + 1)); ?></span>
                            <span class="h-px w-6 bg-blue-600" aria-hidden="true"></span>
                            <span><?php echo wp_kses_post($step['label']); ?></span>
                        </div>
                        <h3 class="font-sans text-xl font-medium tracking-tight text-white">
                            <?php echo esc_html($step['title']); ?>
                        </h3>
                        <p class="text-base text-blue-200 text-pretty">
                            <?php echo esc_html($step['text']); ?>
                        </p>
                    </div>
                </li>
            <?php endforeach; ?>
        </ol>

    </div>
</section>
