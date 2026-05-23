<?php
/**
 * UNIQ Page — How It Works
 *
 * Dark two-column block. Image left (touchscreen in operation),
 * explanatory copy right with a small mono "control loop" rail
 * underneath. Replaces the legacy section that used a blue background
 * image — DESIGN.md §6 calls for clean shop-floor photography rather
 * than decorative pattern washes.
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
    'eyebrow'   => __('How It Works', 'standard'),
    'title'     => __('Brain of the Machine', 'standard'),
    'lede'      => __('UNIQ tracks the exact length and quantity of every panel coming through the machine. It drops the shear at the right moment, runs the drive, and handles notching — so the operator runs the job, not the math.', 'standard'),
    'body'      => __('Build a cutlist on the touchscreen, or write it on a computer and upload via USB. The controller stores up to 600 panel lengths and exports the finished spec back to the office for your records.', 'standard'),
    'image'     => content_url('/uploads/2021/10/SSQ-II-Training-General-Overview-Featured-Image-2048x1152.png.webp'),
    'image_alt' => __('UNIQ Automatic Control System in operation on an SSQ II MultiPro rollformer', 'standard'),
    'cta_text'  => __('Talk to a Specialist', 'standard'),
    'cta_url'   => '/contact/',
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
                    <img
                        src="<?php echo esc_url($content['image']); ?>"
                        alt="<?php echo esc_attr($content['image_alt']); ?>"
                        class="w-full h-auto block"
                        loading="lazy"
                    >
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

                <dl class="grid grid-cols-2 sm:grid-cols-4 border-t border-blue-700 pt-6 gap-y-4">
                    <?php foreach ($loop as $item) : ?>
                        <div class="grid gap-1 pr-4">
                            <span class="font-mono text-[11px] tracking-[0.18em] text-blue-500">
                                <?php echo esc_html($item['step']); ?>
                            </span>
                            <dt class="font-mono text-[11px] uppercase tracking-[0.15em] text-blue-300">
                                <?php echo esc_html($item['label']); ?>
                            </dt>
                            <dd class="font-mono text-sm text-white">
                                <?php echo esc_html($item['value']); ?>
                            </dd>
                        </div>
                    <?php endforeach; ?>
                </dl>

                <div>
                    <a href="<?php echo esc_url(\Standard\Url\internal($content['cta_url'])); ?>" class="btn btn-outline-light">
                        <?php echo esc_html($content['cta_text']); ?>
                        <?php icon('arrow-right', ['class' => 'w-5 h-5']); ?>
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>
