<?php
/**
 * About — Built it. Still building it.
 *
 * Merged origin + timeline. One section, two beats: a short legacy lede
 * that names 1991 and the company's ownership, then the release
 * timeline of category firsts as proof we're still shipping.
 *
 * NOTE: NTM has split off from Mazzella Companies. The ownership copy below
 * is a neutral placeholder pending final wording from the team (see the
 * TODO(copy) markers). Do not re-add the "part of Mazzella" claim.
 *
 * Replaces the previous origin.php and the now-deleted timeline.php.
 * Filename stays "origin" so existing callers keep working.
 *
 * @package Standard
 * @usage About Page (page-about.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$content = [
    'eyebrow' => __('Built it. Still building it.', 'standard'),
    'title'   => __('NTM shipped the SSP in 1991 and started a category. We\'re still the ones defining it.', 'standard'),
    'p1'      => __('Portability has been our bet since 1991. The SSP put a working rollformer on the jobsite and started a category, because metal made where the roof is gives the contractor control of the whole process. NTM was the first to commercially produce a polyurethane drive-roller machine with separate forming rollers, and today almost every portable rollformer on the market uses that approach. The patents have aged out. The lead hasn\'t.', 'standard'),
    // TODO(copy): NTM split off from Mazzella Companies — confirm final
    // ownership wording with the team. Neutral placeholder below avoids the
    // now-false "part of Mazzella" claim while staying true.
    'p2'      => __('NTM is an independent American manufacturer that designs, engineers, and builds its machines in-house. That control is what keeps the engineering moving and the next category-defining machine on the bench.', 'standard'),
];

$callouts = [
    [
        'k' => __('Founded',     'standard'),
        'v' => __('Denver, 1991', 'standard'),
    ],
    // One discreet link to the parent company (Adam, stakeholder review
    // 2026-06-17): "make them travel to get there." No AMT/Sheffield branding
    // on the NTM site — just this quiet outbound link. A 'href' on a callout
    // turns its value into an external anchor (see the renderer below).
    [
        'k'    => __('Parent company', 'standard'),
        'v'    => __('AMT', 'standard'),
        'href' => 'https://archmettech.com/',
    ],
];

// Release list confirmed by Joel (2026-07-10).
$milestones = [
    [
        'year'  => '1991',
        'model' => 'SSP',
        'name'  => __('Roof Panel Machine', 'standard'),
    ],
    [
        'year'  => '1994',
        'model' => 'MACH II',
        'name'  => __('Seamless Gutter Machine', 'standard'),
    ],
    [
        'year'  => '2001',
        'model' => 'SSR MultiPro Jr.',
        'name'  => __('Roof Panel Machine', 'standard'),
    ],
    [
        'year'  => '2004',
        'model' => 'SSH',
        'name'  => __('Roof Panel Machine', 'standard'),
    ],
    [
        'year'  => '2005',
        'model' => 'BG7',
        'name'  => __('Box Gutter Machine', 'standard'),
    ],
    [
        'year'  => '2006',
        'model' => '5V Crimp',
        'name'  => __('Roof Panel Machine', 'standard'),
    ],
    [
        'year'  => '2008',
        'model' => 'SSQ',
        'name'  => __('Quick Change Roof Panel Machine', 'standard'),
    ],
    [
        'year'  => '2017',
        'model' => 'WAV',
        'name'  => __('Wall Panel Machine', 'standard'),
    ],
    [
        'year'  => '2018',
        'model' => 'SSQ II',
        'name'  => __('Roof Panel Machine', 'standard'),
    ],
    [
        'year'  => '2021',
        'model' => 'UNIQ',
        'name'  => __('Control System', 'standard'),
    ],
    [
        'year'  => '2025',
        'model' => 'SSQ3 MultiPro',
        'name'  => __('Roof & Wall Panel Machine', 'standard'),
    ],
];
?>

<section class="bg-white py-16 lg:py-24 border-t border-blue-200" aria-labelledby="about-origin-title">
    <div class="container">

        <div class="max-w-4xl mb-12 lg:mb-16">
            <p class="font-mono uppercase tracking-wider text-xs text-blue-500 mb-5">
                <?php echo esc_html($content['eyebrow']); ?>
            </p>
            <h2 id="about-origin-title" class="font-sans font-medium text-blue-900 text-2xl md:text-3xl lg:text-[2.5rem] leading-tight tracking-tight">
                <?php echo esc_html($content['title']); ?>
            </h2>
        </div>

        <div class="grid lg:grid-cols-12 gap-10 lg:gap-16 mb-16 lg:mb-20">
            <div class="lg:col-span-8">
                <div class="grid gap-6 font-sans text-blue-700 text-base lg:text-lg leading-relaxed max-w-2xl">
                    <p><?php echo esc_html($content['p1']); ?></p>
                    <p><?php echo esc_html($content['p2']); ?></p>
                </div>
            </div>

            <aside class="lg:col-span-4 grid gap-6 content-start" aria-label="<?php esc_attr_e('Company data', 'standard'); ?>">
                <?php foreach ($callouts as $callout) : ?>
                    <div class="grid gap-1 border-t border-blue-200 pt-4">
                        <span class="font-mono uppercase tracking-wider text-xs text-blue-500">
                            <?php echo esc_html($callout['k']); ?>
                        </span>
                        <?php if (!empty($callout['href'])) : ?>
                            <a
                                href="<?php echo esc_url($callout['href']); ?>"
                                target="_blank"
                                rel="noopener"
                                class="font-sans font-medium text-blue-900 text-base lg:text-lg leading-snug underline decoration-blue-300 underline-offset-4 hover:decoration-blue-900 transition-colors inline-flex items-center gap-1.5 w-fit"
                            >
                                <?php echo esc_html($callout['v']); ?>
                                <?php icon('arrow-right', ['class' => 'w-3.5 h-3.5 -rotate-45']); ?>
                            </a>
                        <?php else : ?>
                            <span class="font-sans font-medium text-blue-900 text-base lg:text-lg leading-snug">
                                <?php echo esc_html($callout['v']); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </aside>
        </div>

        <div class="mb-8 lg:mb-10">
            <p class="font-mono uppercase tracking-wider text-xs text-blue-500">
                <?php esc_html_e('The releases that defined the category', 'standard'); ?>
            </p>
        </div>
        <!-- Every cell carries its own top rule, so the grid rewraps
             cleanly at any column count without first-in-row border
             bookkeeping: 1-col timeline at base, 2 at sm, 4 at lg
             (11 items split 4+4+3; 5 columns would strand a lone cell). -->
        <ol class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-x-7">
            <?php foreach ($milestones as $m) : ?>
                <li class="border-t border-blue-200 py-6 lg:py-8">
                    <div class="grid gap-3">
                        <div class="flex items-center gap-2 font-mono">
                            <span class="w-2 h-2 bg-red" aria-hidden="true"></span>
                            <span class="text-sm text-red uppercase tracking-wider"><?php echo esc_html($m['year']); ?></span>
                        </div>
                        <h3 class="font-mono font-medium text-blue-900 text-lg leading-tight">
                            <?php echo esc_html($m['model']); ?>
                        </h3>
                        <p class="font-mono uppercase tracking-wider text-xs text-blue-500 leading-snug -mt-1">
                            <?php echo esc_html($m['name']); ?>
                        </p>
                    </div>
                </li>
            <?php endforeach; ?>
        </ol>

    </div>
</section>
