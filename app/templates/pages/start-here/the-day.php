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
        'title' => __('Bring the coil to the job', 'standard'),
        'text'  => __('You buy flat metal coil by the pound, far cheaper than finished panels, and haul it to the site on a trailer. No factory, no warehouse, no waiting on a supplier’s lead time.', 'standard'),
        'image' => content_url('/uploads/2026/01/20260106_NTM_Problems-With-Metal-Coil-Checking-For-Defects_Thumbnail-V2.jpg'),
        'alt'   => __('A contractor inspecting a roll of flat metal coil before feeding it into the machine', 'standard'),
    ],
    [
        'title' => __('Roll the exact panels you need', 'standard'),
        'text'  => __('The machine forms each panel to length on the spot, only what the job calls for. Training comes with the machine, so you are running real panels within days, not months.', 'standard'),
        'image' => content_url('/uploads/2026/02/Machine-on-rooftop.jpg'),
        'alt'   => __('An NTM portable rollforming machine producing metal panels on a rooftop jobsite', 'standard'),
    ],
    [
        'title' => __('Install it and get paid', 'standard'),
        'text'  => __('You install the panels or gutters you just made and bill for the finished work. The margin that used to go to a panel supplier stays in your pocket on every job.', 'standard'),
        'image' => content_url('/uploads/2026/05/ntm-mach2-gutter-install-abel-001.jpg'),
        'alt'   => __('A crew installing a freshly formed seamless gutter run on a home', 'standard'),
    ],
];
?>

<section class="section bg-blue-50" aria-labelledby="start-here-day-title">
    <div class="container section-content">

        <div class="section-header-left max-w-2xl">
            <p class="section-eyebrow"><?php esc_html_e('What the work looks like', 'standard'); ?></p>
            <div class="section-divider"></div>
            <h2 id="start-here-day-title" class="section-title">
                <?php esc_html_e('From Coil to Cash, on the Jobsite', 'standard'); ?>
            </h2>
            <p class="section-subtitle text-pretty">
                <?php esc_html_e('Three steps, start to finish. This is the whole job, from raw metal to a paid invoice.', 'standard'); ?>
            </p>
        </div>

        <ol class="grid gap-px border border-blue-200 bg-blue-200 md:grid-cols-3" role="list">
            <?php foreach ($steps as $idx => $step) : ?>
                <li class="flex flex-col bg-blue-50">
                    <div class="relative aspect-[4/3] overflow-hidden bg-blue-100">
                        <?php
                        \Standard\Images\responsive_image(
                            $step['image'],
                            $step['alt'],
                            'large',
                            ['class' => 'h-full w-full object-cover']
                        );
                        ?>
                        <span class="absolute left-0 top-0 flex h-10 w-10 items-center justify-center bg-blue-900 font-mono text-sm font-medium text-white" aria-hidden="true">
                            <?php echo esc_html(sprintf('%02d', $idx + 1)); ?>
                        </span>
                    </div>
                    <div class="grid gap-3 p-6 lg:p-8">
                        <h3 class="font-sans text-xl font-medium tracking-tight text-blue-900">
                            <?php echo esc_html($step['title']); ?>
                        </h3>
                        <p class="text-base text-blue-600 text-pretty">
                            <?php echo esc_html($step['text']); ?>
                        </p>
                    </div>
                </li>
            <?php endforeach; ?>
        </ol>

    </div>
</section>
