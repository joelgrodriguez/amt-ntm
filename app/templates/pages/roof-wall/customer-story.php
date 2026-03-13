<?php
/**
 * Roof & Wall Panel Machines — Customer Story
 *
 * Category-specific customer story with image on the left,
 * content on the right. Different layout and story from
 * the machines page version.
 *
 * @package Standard
 *
 * @usage Roof & Wall Panel Machines (page-roof-wall-panel-machines.php)
 */

declare(strict_types=1);

$content = [
    'eyebrow'    => __('Customer Story', 'standard'),
    'quote'      => __("With NTM equipment, we produce panels faster, cut costs, and reduced our lead times by about 75%. We're winning more metal roofing jobs because we can offer faster delivery than anyone relying on a factory.", 'standard'),
    'name'       => 'Riley Hays',
    'company'    => 'Riley Hays Roofing & Construction',
    'machine'    => 'SSQ II MultiPro',
    'image'      => 'https://newtechmachinery.com/wp-content/uploads/2025/04/Nate-training-East-Kentucky-Metal-9-scaled.jpg',
    'cta_text'   => __('Watch the Full Story', 'standard'),
    'cta_url'    => '/learning-center/video/how-riley-hays-cut-lead-times-by-75-percent-with-ntm-video/',
];

$stats = [
    [
        'stat'  => '75%',
        'label' => __('Shorter Lead Times', 'standard'),
    ],
    [
        'stat'  => '2x',
        'label' => __('More Jobs Won', 'standard'),
    ],
    [
        'stat'  => 'Year 1',
        'label' => __('Machine Paid Off', 'standard'),
    ],
];
?>

<section class="section bg-slate-50" aria-labelledby="roof-wall-customer-story-title">
    <div class="container">
        <div class="grid gap-12 md:grid-cols-2 md:gap-12 lg:gap-16 md:items-center">

            <!-- Image — LEFT -->
            <div>
                <img
                    src="<?php echo esc_url($content['image']); ?>"
                    alt="<?php echo esc_attr($content['name'] . ' — ' . $content['company']); ?>"
                    class="w-full h-[300px] md:h-[400px] lg:h-[500px] object-cover"
                    loading="lazy"
                >
            </div>

            <!-- Content — RIGHT -->
            <div class="grid gap-8 content-start">
                <div class="section-header-left">
                    <p id="roof-wall-customer-story-title" class="section-eyebrow">
                        <?php echo esc_html($content['eyebrow']); ?>
                    </p>
                    <div class="section-divider"></div>
                </div>

                <blockquote class="text-xl font-serif text-slate-800 leading-relaxed lg:text-2xl">
                    <span class="text-secondary text-3xl leading-none" aria-hidden="true">&ldquo;</span>
                    <?php echo esc_html($content['quote']); ?>
                    <span class="text-secondary text-3xl leading-none" aria-hidden="true">&rdquo;</span>
                </blockquote>

                <div>
                    <p class="font-semibold text-slate-900">
                        <?php echo esc_html($content['name']); ?>
                    </p>
                    <p class="text-sm text-slate-500">
                        <?php echo esc_html($content['company']); ?> &middot; <?php echo esc_html($content['machine']); ?>
                    </p>
                </div>

                <div class="grid grid-cols-3 gap-6 border-t border-slate-200 pt-8">
                    <?php foreach ($stats as $stat) : ?>
                        <div class="grid gap-1">
                            <span class="text-2xl font-bold text-slate-900 lg:text-3xl">
                                <?php echo esc_html($stat['stat']); ?>
                            </span>
                            <span class="text-xs text-slate-500 uppercase tracking-wider">
                                <?php echo esc_html($stat['label']); ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div>
                    <a href="<?php echo esc_url($content['cta_url']); ?>" class="btn btn-outline-dark">
                        <?php icon('play', ['class' => 'w-5 h-5']); ?>
                        <?php echo esc_html($content['cta_text']); ?>
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>
