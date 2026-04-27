<?php
/**
 * Machine Product — Case Study
 *
 * Detailed case study with challenge/solution/results format.
 * Provides deeper social proof than the testimonial carousel.
 *
 * @package Standard
 * @var array{machine: array} $args
 *
 * @usage Single Machine Product (single-machine.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$machine    = $args['machine'] ?? [];
$case_study = $machine['case_study'] ?? [];

// Placeholder case study when machine data is empty
if (empty($case_study)) {
    $case_study = [
        'eyebrow'   => __('Case Study', 'standard'),
        'title'     => __('From Subcontracting to Self-Sufficient in 12 Months', 'standard'),
        'company'   => 'Rocky Mountain Metal Works',
        'location'  => 'Boise, ID',
        'image'     => 'https://newtechmachinery.com/wp-content/uploads/2025/04/Nate-training-East-Kentucky-Metal-9-scaled.jpg',
        'challenge' => [
            'heading' => __('The Challenge', 'standard'),
            'text'    => __('Rocky Mountain Metal Works was losing 30% of their margin to outsourced panel production. Lead times of 2–3 weeks meant missed deadlines, unhappy clients, and jobs going to competitors who could deliver faster.', 'standard'),
        ],
        'solution' => [
            'heading' => __('The Solution', 'standard'),
            'text'    => __('After investing in an NTM rollformer, the team went from relying on third-party suppliers to producing panels on-site the same day. NTM\'s training program had their crew fully operational within a week.', 'standard'),
            'points'  => [
                __('On-site panel production — no more waiting on deliveries', 'standard'),
                __('Full crew training completed in 5 days', 'standard'),
                __('Portable setup for both shop and jobsite work', 'standard'),
            ],
        ],
        'results' => [
            'heading' => __('The Results', 'standard'),
            'text'    => __('Within 12 months, Rocky Mountain Metal Works had paid off their machine investment and tripled their project capacity.', 'standard'),
        ],
        'stats' => [
            [
                'stat'  => '30%',
                'label' => __('Margin Recovered', 'standard'),
                'icon'  => 'trending-up',
            ],
            [
                'stat'  => '12 mo',
                'label' => __('Full ROI Payback', 'standard'),
                'icon'  => 'calendar',
            ],
            [
                'stat'  => '3×',
                'label' => __('Project Capacity', 'standard'),
                'icon'  => 'trending-up',
            ],
            [
                'stat'  => '$150K+',
                'label' => __('Annual Savings', 'standard'),
                'icon'  => 'dollar-sign',
            ],
        ],
        'quote' => [
            'text' => __('The machine paid for itself before we even finished our first year. Now we\'re taking on bigger projects and keeping every dollar of margin in-house.', 'standard'),
            'name' => 'Brian Kowalski',
            'role' => __('Owner, Rocky Mountain Metal Works', 'standard'),
        ],
        'cta_text' => __('Read the Full Case Study', 'standard'),
        'cta_url'  => '#',
    ];
}
?>

<section class="section bg-white" aria-labelledby="case-study-title">
    <div class="container">
        <div class="section-content">

            <!-- Header -->
            <div class="section-header">
                <p class="section-eyebrow">
                    <?php echo esc_html($case_study['eyebrow']); ?>
                </p>
                <div class="section-divider-center"></div>
                <h2 id="case-study-title" class="section-title">
                    <?php echo esc_html($case_study['title']); ?>
                </h2>
                <p class="section-subtitle-centered">
                    <?php echo esc_html($case_study['company']); ?> &middot; <?php echo esc_html($case_study['location']); ?>
                </p>
            </div>

            <!-- Two-column: Image + Challenge/Solution -->
            <div class="grid gap-12 md:grid-cols-2 md:gap-12 lg:gap-16 md:items-start">

                <!-- Image -->
                <div class="reveal">
                    <img
                        src="<?php echo esc_url($case_study['image']); ?>"
                        alt="<?php echo esc_attr($case_study['company']); ?>"
                        class="w-full h-[300px] md:h-[400px] lg:h-[500px] object-cover"
                        loading="lazy"
                    >
                </div>

                <!-- Challenge + Solution -->
                <div class="grid gap-10 content-start reveal">

                    <!-- Challenge -->
                    <div class="grid gap-3">
                        <h3 class="text-lg font-bold text-slate-900 uppercase tracking-wider font-mono">
                            <?php echo esc_html($case_study['challenge']['heading']); ?>
                        </h3>
                        <p class="text-slate-600 leading-relaxed">
                            <?php echo esc_html($case_study['challenge']['text']); ?>
                        </p>
                    </div>

                    <!-- Solution -->
                    <div class="grid gap-4">
                        <h3 class="text-lg font-bold text-slate-900 uppercase tracking-wider font-mono">
                            <?php echo esc_html($case_study['solution']['heading']); ?>
                        </h3>
                        <p class="text-slate-600 leading-relaxed">
                            <?php echo esc_html($case_study['solution']['text']); ?>
                        </p>
                        <?php if (!empty($case_study['solution']['points'])) : ?>
                            <ul class="grid gap-3 mt-2">
                                <?php foreach ($case_study['solution']['points'] as $point) : ?>
                                    <li class="flex items-start gap-3">
                                        <?php icon('check', ['class' => 'w-5 h-5 text-green-600 shrink-0 mt-0.5']); ?>
                                        <span class="text-slate-700"><?php echo esc_html($point); ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>

                </div>
            </div>

            <!-- Results -->
            <div class="reveal">
                <div class="grid gap-8">
                    <div class="text-center">
                        <h3 class="text-lg font-bold text-slate-900 uppercase tracking-wider font-mono">
                            <?php echo esc_html($case_study['results']['heading']); ?>
                        </h3>
                        <p class="text-slate-600 mt-3 max-w-2xl mx-auto">
                            <?php echo esc_html($case_study['results']['text']); ?>
                        </p>
                    </div>

                    <!-- Stats Grid -->
                    <?php if (!empty($case_study['stats'])) : ?>
                        <div class="grid grid-cols-2 gap-6 md:grid-cols-4 lg:gap-8">
                            <?php foreach ($case_study['stats'] as $stat) : ?>
                                <div class="text-center grid gap-2 p-6 border border-slate-200 bg-slate-50">
                                    <div class="flex justify-center">
                                        <?php icon($stat['icon'], ['class' => 'w-6 h-6 text-secondary']); ?>
                                    </div>
                                    <span class="text-3xl font-bold text-slate-900 lg:text-4xl">
                                        <?php echo esc_html($stat['stat']); ?>
                                    </span>
                                    <span class="text-xs text-slate-500 uppercase tracking-wider">
                                        <?php echo esc_html($stat['label']); ?>
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Pull Quote -->
            <?php if (!empty($case_study['quote'])) : ?>
                <div class="reveal">
                    <div class="bg-slate-900 p-8 md:p-12 lg:p-16 text-center grid gap-6">
                        <?php icon('quote', ['class' => 'w-10 h-10 text-secondary mx-auto']); ?>
                        <blockquote class="text-xl text-white font-mono leading-relaxed max-w-3xl mx-auto md:text-2xl">
                            &ldquo;<?php echo esc_html($case_study['quote']['text']); ?>&rdquo;
                        </blockquote>
                        <footer>
                            <cite class="not-italic">
                                <span class="block text-white font-semibold">
                                    <?php echo esc_html($case_study['quote']['name']); ?>
                                </span>
                                <span class="text-sm text-slate-400">
                                    <?php echo esc_html($case_study['quote']['role']); ?>
                                </span>
                            </cite>
                        </footer>
                    </div>
                </div>
            <?php endif; ?>

            <!-- CTA -->
            <?php if (!empty($case_study['cta_url'])) : ?>
                <div class="text-center">
                    <a href="<?php echo esc_url($case_study['cta_url']); ?>" class="btn btn-primary">
                        <?php echo esc_html($case_study['cta_text'] ?? __('Read the Full Case Study', 'standard')); ?>
                        <?php icon('arrow-right', ['class' => 'w-5 h-5']); ?>
                    </a>
                </div>
            <?php endif; ?>

        </div>
    </div>
</section>
