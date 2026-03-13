<?php
/**
 * Machines Page — Customer Story
 *
 * Real customer case study with pull quote and key stats.
 * Data sourced from NTM's published ROI article.
 *
 * @package Standard
 *
 * @usage Machines Page (page-machines.php)
 */

declare(strict_types=1);

$content = [
    'eyebrow'    => __('Customer Story', 'standard'),
    'quote'      => __("Once I got the SSR, things really excelled. It's basically a printing press — you put coil on top, turn it on, and every foot that comes out, you're making money.", 'standard'),
    'name'       => 'Jim Averill',
    'company'    => 'Gunnison Sheet Metal',
    'machine'    => 'SSR MultiPro Jr.',
    'image'      => 'https://newtechmachinery.com/wp-content/uploads/2025/04/Nate-training-East-Kentucky-Metal-9-scaled.jpg',
    'cta_text'   => __('Read the Full Story', 'standard'),
    'cta_url'    => '/learning-center/ntm-customers-roi-behind-portable-standing-seam-panel-production/',
];

$stats = [
    [
        'stat'  => '100+',
        'label' => __('Jobs in 3 Years', 'standard'),
    ],
    [
        'stat'  => '$200K+',
        'label' => __('Estimated Savings', 'standard'),
    ],
    [
        'stat'  => '1,000%',
        'label' => __('Business Growth', 'standard'),
    ],
];
?>

<section class="section bg-slate-50" aria-labelledby="customer-story-title">
    <div class="container">
        <div class="grid gap-12 md:grid-cols-2 md:gap-12 lg:gap-16 md:items-center">

            <div class="grid gap-8 content-start">
                <div class="section-header-left">
                    <p id="customer-story-title" class="section-eyebrow">
                        <?php echo esc_html($content['eyebrow']); ?>
                    </p>
                    <div class="section-divider"></div>
                </div>

                <blockquote class="text-xl font-serif text-slate-800 leading-relaxed lg:text-2xl">
                    <span class="text-secondary text-3xl leading-none" aria-hidden="true">&ldquo;</span>
                    <?php echo esc_html($content['quote']); ?>
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
                        <?php echo esc_html($content['cta_text']); ?>
                        <?php icon('arrow-right', ['class' => 'w-5 h-5']); ?>
                    </a>
                </div>
            </div>

            <div>
                <img
                    src="<?php echo esc_url($content['image']); ?>"
                    alt="<?php echo esc_attr($content['name'] . ' — ' . $content['company']); ?>"
                    class="w-full h-[300px] md:h-[400px] lg:h-[500px] object-cover"
                    loading="lazy"
                >
            </div>

        </div>
    </div>
</section>
