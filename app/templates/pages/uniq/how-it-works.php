<?php
/**
 * UNIQ Page — How It Works
 *
 * Dark two-column block. Image left (touchscreen in operation),
 * explanatory copy right with a small mono "control loop" rail
 * underneath. Replaces the legacy section that used a blue background
 * image (DESIGN.md §6 calls for clean shop-floor photography rather
 * than decorative pattern washes).
 *
 * The loop renders as an ordered list (semantic process, not term/
 * definition). Step numbers are decorative and aria-hidden so screen
 * readers announce label + value as the meaningful unit.
 *
 * @package Standard
 *
 * @usage page-uniq-control-system.php
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$content = [
    'eyebrow'        => __('How It Works', 'standard'),
    'title'          => __('Brain of the Machine', 'standard'),
    'lede'           => __('UNIQ tracks the exact length and quantity of every panel coming through the machine. It drops the shear at the right moment, runs the drive, and handles notching, so the operator runs the job, not the math.', 'standard'),
    'body'           => __('Build a cutlist on the touchscreen, or write it on a computer and upload via USB. The controller stores up to 600 panel lengths and exports the finished spec back to the office for your records.', 'standard'),
    'image'          => content_url('/uploads/2021/10/SSQ-II-Training-General-Overview-Featured-Image.png'),
    'image_alt'      => __('UNIQ Automatic Control System in operation on an SSQ II MultiPro rollformer', 'standard'),
    'cta_text'       => __('Talk to a Specialist', 'standard'),
    'cta_url'        => '/contact/',
    'cta_link_text'  => __('Or download the supplement manual', 'standard'),
    'cta_link_url'   => '/learning-center/manual/ssq2-supplement-uniq-v1-1-9/',
];

$loop = [
    ['step' => '01', 'label' => __('Input', 'standard'),  'value' => __('Touchscreen or USB cutlist', 'standard')],
    ['step' => '02', 'label' => __('Drive', 'standard'),  'value' => __('Auto length & quantity', 'standard')],
    ['step' => '03', 'label' => __('Shear', 'standard'),  'value' => __('Timed cut at length', 'standard')],
    ['step' => '04', 'label' => __('Export', 'standard'), 'value' => __('Project spec to USB', 'standard')],
];
?>

<section class="section bg-blue-900 text-white" aria-labelledby="uniq-how-title">
    <div class="container">
        <div class="grid gap-12 lg:grid-cols-12 lg:gap-16 lg:items-center">

            <div class="lg:col-span-6">
                <div class="border border-blue-700">
                    <?php \Standard\Images\responsive_image($content['image'], $content['image_alt'], 'full', [
                        'class'    => 'w-full h-auto block',
                        'loading'  => 'lazy',
                        'decoding' => 'async',
                    ]); ?>
                </div>
            </div>

            <div class="grid gap-8 lg:col-span-6">
                <div class="grid gap-4">
                    <p class="font-mono text-[11px] uppercase tracking-[0.18em] text-blue-300">
                        <?php echo esc_html($content['eyebrow']); ?>
                    </p>
                    <div class="w-12 h-1 bg-blue-500"></div>
                    <h2 id="uniq-how-title" class="font-sans font-medium text-3xl md:text-4xl lg:text-5xl text-white tracking-tight leading-[1.1]">
                        <?php echo esc_html($content['title']); ?>
                    </h2>
                </div>

                <div class="grid gap-4 max-w-xl">
                    <p class="font-sans text-base lg:text-lg text-blue-200 leading-relaxed">
                        <?php echo esc_html($content['lede']); ?>
                    </p>
                    <p class="font-sans text-base text-blue-200 leading-relaxed">
                        <?php echo esc_html($content['body']); ?>
                    </p>
                </div>

                <ol class="grid grid-cols-2 sm:grid-cols-4 border-t border-blue-700 pt-6 gap-y-4" aria-label="<?php esc_attr_e('UNIQ control loop', 'standard'); ?>">
                    <?php foreach ($loop as $item) : ?>
                        <li class="grid gap-1 pr-4">
                            <span class="font-mono text-[11px] tracking-[0.18em] text-blue-400" aria-hidden="true">
                                <?php echo esc_html($item['step']); ?>
                            </span>
                            <span class="font-mono text-[11px] uppercase tracking-[0.15em] text-blue-300">
                                <?php echo esc_html($item['label']); ?><span class="sr-only">:</span>
                            </span>
                            <span class="font-mono text-sm text-white">
                                <?php echo esc_html($item['value']); ?>
                            </span>
                        </li>
                    <?php endforeach; ?>
                </ol>

                <div class="grid gap-3">
                    <div>
                        <a href="<?php echo esc_url(\Standard\Url\internal($content['cta_url'])); ?>" class="btn btn-outline-light">
                            <?php echo esc_html($content['cta_text']); ?>
                            <?php icon('arrow-right', ['class' => 'w-5 h-5']); ?>
                        </a>
                    </div>
                    <a
                        href="<?php echo esc_url(\Standard\Url\internal($content['cta_link_url'])); ?>"
                        class="font-mono text-[11px] uppercase tracking-[0.15em] text-blue-300 hover:text-white transition-colors duration-150 inline-flex items-center gap-2 self-start"
                    >
                        <?php echo esc_html($content['cta_link_text']); ?>
                        <?php icon('arrow-right', ['class' => 'w-3 h-3']); ?>
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>
