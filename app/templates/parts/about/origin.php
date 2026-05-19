<?php
/**
 * About — Origin & Longevity
 *
 * Two-column blueprint frame: left = founding narrative, sans body copy.
 * Right = stacked spec ledger (founded, HQ, second plant, parent, reach)
 * in mono. Chrome bars top and bottom. Light surface to relieve the dark
 * manifesto above and the dark timeline below.
 *
 * @package Standard
 * @usage About Page (page-about.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$content = [
    'channel_left'  => __('Origin', 'standard'),
    'channel_right' => __('Denver, 1991 / Aurora, 2025', 'standard'),
    'eyebrow'       => __('How NTM got here', 'standard'),
    'title'         => __('Founded by Larry Coben in Denver, 1991. Built for contractors, ever since.', 'standard'),
    'p1'            => __('Larry Coben started New Tech Machinery in 1991 with a single conviction: the rollforming machine belonged on the jobsite, not bolted to a factory floor. The SSP Roof Panel Machine shipped that year and the category followed. Three years later, the MACH II Seamless Gutter Machine did the same for gutters.', 'standard'),
    'p2'            => __('NTM was the first portable rollforming company to commercially produce a polyurethane drive roller machine with separate forming rollers. Today almost every portable rollformer on the market uses that approach. The patents have aged out; the lead hasn\'t.', 'standard'),
    'p3'            => __('Headquarters moved to Aurora, Colorado. A second manufacturing facility opened in Hermosillo, Mexico in 2004. In 2015 Mazzella Companies acquired NTM, and the backing has let us hold the lead in the category we created. Same engineering team. Same buyers. Same machines, refined.', 'standard'),
    'footer_left'   => __('Read', 'standard'),
    'footer_right'  => __('Three decades, one focus', 'standard'),
];

$facts = [
    ['k' => __('Founded',      'standard'), 'v' => '1991',                              'meta' => __('Denver, CO', 'standard')],
    ['k' => __('Headquarters', 'standard'), 'v' => 'Aurora, Colorado',                  'meta' => __('USA', 'standard')],
    ['k' => __('2nd plant',    'standard'), 'v' => 'Hermosillo, Mexico',                'meta' => __('2004', 'standard')],
    ['k' => __('Parent',       'standard'), 'v' => 'Mazzella Companies',                'meta' => __('Acquired 2015', 'standard')],
    ['k' => __('Reach',        'standard'), 'v' => __('40+ countries', 'standard'),     'meta' => __('Six continents', 'standard')],
    ['k' => __('Memberships',  'standard'), 'v' => 'MCA / NRCA / CRA',                  'meta' => __('Industry assoc.', 'standard')],
];
?>

<section class="bg-white text-blue-600 border-y border-blue-200" aria-labelledby="about-origin-title">

    <!-- Top chrome bar -->
    <div class="border-b border-blue-200">
        <div class="border-x border-blue-200 container">
            <div class="flex items-center justify-between py-3 text-xs font-mono uppercase tracking-wider">
                <div class="flex items-center gap-3 pl-3">
                    <span class="w-2 h-2 bg-red" aria-hidden="true"></span>
                    <span><?php echo esc_html($content['channel_left']); ?></span>
                </div>
                <div class="flex items-center gap-3 pr-3">
                    <span><?php echo esc_html($content['channel_right']); ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Body: two columns inside the frame -->
    <div class="border-x border-blue-200 container">
        <div class="grid lg:grid-cols-12">

            <!-- Narrative column -->
            <div class="lg:col-span-7 lg:border-r border-blue-200 px-6 lg:px-10 py-12 lg:py-16">
                <div class="grid gap-6 max-w-2xl">

                    <div class="flex items-baseline gap-2 font-mono uppercase tracking-wider text-xs text-blue-500">
                        <span>01</span>
                        <span class="w-8 h-px bg-blue-300" aria-hidden="true"></span>
                        <span><?php echo esc_html($content['eyebrow']); ?></span>
                    </div>

                    <h2 id="about-origin-title" class="font-sans font-medium text-blue-900 text-2xl md:text-3xl lg:text-4xl leading-tight tracking-tight">
                        <?php echo esc_html($content['title']); ?>
                    </h2>

                    <div class="grid gap-5 font-sans text-blue-700 text-base lg:text-lg leading-relaxed">
                        <p><?php echo esc_html($content['p1']); ?></p>
                        <p><?php echo esc_html($content['p2']); ?></p>
                        <p><?php echo esc_html($content['p3']); ?></p>
                    </div>

                </div>
            </div>

            <!-- Ledger column -->
            <div class="lg:col-span-5 border-t lg:border-t-0 border-blue-200">
                <dl class="grid">
                    <?php foreach ($facts as $i => $fact) : ?>
                        <div class="grid gap-1 px-6 lg:px-10 py-5 lg:py-6 <?php echo $i > 0 ? 'border-t border-blue-200' : ''; ?>">
                            <dt class="font-mono uppercase tracking-wider text-[0.625rem] md:text-xs text-blue-500">
                                <?php echo esc_html($fact['k']); ?>
                            </dt>
                            <dd class="font-sans font-medium text-blue-900 text-lg md:text-xl leading-tight">
                                <?php echo esc_html($fact['v']); ?>
                            </dd>
                            <p class="font-mono uppercase tracking-wider text-[0.625rem] text-blue-400">
                                <?php echo esc_html($fact['meta']); ?>
                            </p>
                        </div>
                    <?php endforeach; ?>
                </dl>
            </div>

        </div>
    </div>

    <!-- Bottom chrome bar -->
    <div class="border-t border-blue-200">
        <div class="border-x border-blue-200 container">
            <div class="flex items-center justify-between py-3 font-mono uppercase tracking-wider text-[0.625rem] md:text-xs">
                <div class="flex items-center gap-2 pl-3">
                    <?php icon('file-text', ['class' => 'w-3 h-3 text-red']); ?>
                    <span class="text-blue-900"><?php echo esc_html($content['footer_left']); ?></span>
                </div>
                <div class="flex items-center gap-4 pr-3">
                    <span class="text-blue-900"><?php echo esc_html($content['footer_right']); ?></span>
                    <div class="hidden md:flex gap-1" aria-hidden="true">
                        <span class="w-1 h-3 bg-blue-300"></span>
                        <span class="w-1 h-3 bg-blue-300"></span>
                        <span class="w-1 h-3 bg-blue-300"></span>
                        <span class="w-1 h-3 bg-red"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>
