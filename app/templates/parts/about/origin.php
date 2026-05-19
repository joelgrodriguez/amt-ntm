<?php
/**
 * About — Origin & Longevity
 *
 * Quiet light section. No chrome bars. Two columns inside the container:
 * left = institutional narrative (the category-creation claim, the
 * design that's still imitated, the move to Aurora and Mazzella), right
 * = stacked spec ledger (founded, HQ, second plant, parent, reach,
 * memberships).
 *
 * Larry Coben demotes from headline subject to a ledger line and a
 * single mention in the second paragraph. The page is about a category,
 * not a founder.
 *
 * @package Standard
 * @usage About Page (page-about.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$content = [
    'eyebrow' => __('At the forefront', 'standard'),
    'title'   => __('Three decades at the forefront. Setting the standard since 1991.', 'standard'),
    'p1'      => __('The SSP Roof Panel Machine shipped in 1991, and the modern portable roof panel category followed. Three years later, the MACH II Seamless Gutter Machine did the same for gutters. NTM didn\'t join the category. NTM made it.', 'standard'),
    'p2'      => __('NTM was the first portable rollforming company to commercially produce a polyurethane drive roller machine with separate forming rollers. Today almost every portable rollformer on the market uses that approach. The patents have aged out. The lead hasn\'t.', 'standard'),
    'p3'      => __('Headquartered in Aurora, Colorado. A second manufacturing facility opened in Hermosillo, Mexico in 2004. Mazzella Companies acquired NTM in 2015, and the backing has let us hold the lead in the category we built. Same engineering instincts. Same machines, refined.', 'standard'),
];

$facts = [
    ['k' => __('Founded',      'standard'), 'v' => '1991',                          'meta' => __('Denver, CO / Larry Coben', 'standard')],
    ['k' => __('Headquarters', 'standard'), 'v' => 'Aurora, Colorado',              'meta' => __('USA', 'standard')],
    ['k' => __('2nd plant',    'standard'), 'v' => 'Hermosillo, Mexico',            'meta' => __('Opened 2004', 'standard')],
    ['k' => __('Parent',       'standard'), 'v' => 'Mazzella Companies',            'meta' => __('Acquired 2015', 'standard')],
    ['k' => __('Reach',        'standard'), 'v' => __('40+ countries', 'standard'), 'meta' => __('Six continents', 'standard')],
    ['k' => __('Memberships',  'standard'), 'v' => 'MCA / NRCA / CRA',              'meta' => __('Industry associations', 'standard')],
];
?>

<section class="bg-white py-16 lg:py-24 border-t border-blue-200" aria-labelledby="about-origin-title">
    <div class="container">

        <!-- Eyebrow + headline, full width above the columns -->
        <div class="max-w-4xl mb-12 lg:mb-16">
            <p class="font-mono uppercase tracking-wider text-xs text-red mb-5">
                <?php echo esc_html($content['eyebrow']); ?>
            </p>
            <h2 id="about-origin-title" class="font-sans font-medium text-blue-900 text-2xl md:text-3xl lg:text-[2.5rem] leading-tight tracking-tight">
                <?php echo esc_html($content['title']); ?>
            </h2>
        </div>

        <!-- Two columns: narrative + ledger -->
        <div class="grid lg:grid-cols-12 gap-10 lg:gap-16">

            <!-- Narrative -->
            <div class="lg:col-span-7">
                <div class="grid gap-6 font-sans text-blue-700 text-base lg:text-lg leading-relaxed max-w-2xl">
                    <p><?php echo esc_html($content['p1']); ?></p>
                    <p><?php echo esc_html($content['p2']); ?></p>
                    <p><?php echo esc_html($content['p3']); ?></p>
                </div>
            </div>

            <!-- Ledger -->
            <div class="lg:col-span-5">
                <dl class="border-t border-blue-200">
                    <?php foreach ($facts as $fact) : ?>
                        <div class="grid gap-1 py-5 border-b border-blue-200">
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
</section>
