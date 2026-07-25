<?php
/**
 * Careers — Why NTM
 *
 * Fact-only employer narrative drawn from the original careers Gutenberg
 * copy and current company location facts. No invented benefits.
 *
 * @package Standard
 * @usage Careers (templates/template-careers.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$content = [
    'eyebrow' => __('Why NTM', 'standard'),
    'title'   => __('A team-oriented workplace built around the machines we make.', 'standard'),
    'lede'    => __('New Tech Machinery is a team-oriented environment that promotes employee learning and professional development. We look for people with a strong work ethic who are team- and customer-service oriented.', 'standard'),
];

$points = [
    [
        'num'   => '01',
        'label' => __('Learning is part of the job', 'standard'),
        'body'  => __('NTM promotes employee learning and professional development. Portable rollforming equipment is specialized work, so people keep building product knowledge on the job.', 'standard'),
    ],
    [
        'num'   => '02',
        'label' => __('Team and customer focus', 'standard'),
        'body'  => __('We hire for a strong work ethic and an orientation to the team and to customer service. Most roles connect to the product owners and crews use every day.', 'standard'),
    ],
    [
        'num'   => '03',
        'label' => __('Aurora headquarters', 'standard'),
        'body'  => __('NTM is headquartered in Aurora, Colorado, with manufacturing there and a second manufacturing facility in Hermosillo, Mexico. Machines ship to customers in 40+ countries.', 'standard'),
    ],
];
?>

<section class="bg-white py-16 lg:py-24" aria-labelledby="careers-why-title">
    <div class="container">
        <div class="grid gap-10 lg:grid-cols-12 lg:gap-16 mb-12 lg:mb-16">
            <div class="lg:col-span-7 grid gap-6 content-start">
                <p class="font-mono uppercase tracking-wider text-xs text-blue-500">
                    <?php echo esc_html($content['eyebrow']); ?>
                </p>
                <h2 id="careers-why-title" class="font-sans font-medium text-blue-900 text-2xl md:text-3xl lg:text-[2.5rem] leading-tight tracking-tight text-balance">
                    <?php echo esc_html($content['title']); ?>
                </h2>
            </div>
            <div class="lg:col-span-5 flex lg:items-end">
                <p class="font-sans text-blue-700 text-base lg:text-lg leading-relaxed max-w-xl">
                    <?php echo esc_html($content['lede']); ?>
                </p>
            </div>
        </div>

        <ol class="grid grid-cols-1 md:grid-cols-3 border-t border-blue-200
            [&>li]:border-t [&>li]:border-blue-200 [&>li:first-child]:border-t-0
            md:[&>li]:border-l md:[&>li]:border-blue-200
            md:[&>li:first-child]:border-l-0 md:[&>li]:border-t-0"
            role="list">
            <?php foreach ($points as $point) : ?>
                <li class="px-0 md:px-8 py-10 lg:py-12">
                    <div class="grid gap-4 md:gap-5">
                        <span class="font-mono text-sm text-red-600 tracking-wider">
                            <?php echo esc_html($point['num']); ?>
                        </span>
                        <h3 class="font-sans font-medium text-blue-900 text-xl md:text-2xl leading-tight tracking-tight">
                            <?php echo esc_html($point['label']); ?>
                        </h3>
                        <p class="font-sans text-blue-700 text-base leading-relaxed">
                            <?php echo esc_html($point['body']); ?>
                        </p>
                    </div>
                </li>
            <?php endforeach; ?>
        </ol>
    </div>
</section>
