<?php
/**
 * About — Built it. Still building it.
 *
 * Merged origin + timeline. One section, two beats: a short legacy lede
 * that names 1991 and the company's ownership, then the 5-machine timeline
 * of category firsts as proof we're still shipping.
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

// Timeline hidden pending Rick's confirmation of the milestones
// (Joel, 2026-07-10). Flip to true to bring it back — markup below
// is intact.
$show_timeline = false;

$milestones = [
    [
        'year'  => '1991',
        'model' => 'SSP',
        'name'  => __('Roof Panel Machine', 'standard'),
        'note'  => __('The machine that started the modern portable roof panel category.', 'standard'),
    ],
    [
        'year'  => '1994',
        'model' => 'MACH II',
        'name'  => __('Seamless Gutter Machine', 'standard'),
        'note'  => __('Did for gutters what the SSP did for roof panels.', 'standard'),
    ],
    [
        'year'  => __('Early 90s', 'standard'),
        'model' => __('Polyurethane Drive Roller', 'standard'),
        'name'  => __('Industry-First Mechanism', 'standard'),
        'note'  => __('Separate forming rollers, polyurethane drive. Now the industry standard.', 'standard'),
    ],
    [
        'year'  => '2008',
        'model' => 'SSQ',
        'name'  => __('Quick Change Roof Panel Machine', 'standard'),
        'note'  => __('Profile changeovers in minutes, not hours. The platform that became SSQ II.', 'standard'),
    ],
    [
        'year'  => '2021',
        'model' => 'UNIQ',
        'name'  => __('Control System', 'standard'),
        'note'  => __('NTM\'s digital control platform. The current standard across the lineup.', 'standard'),
    ],
    [
        'year'  => '2025',
        'model' => 'SSQ3 MultiPro',
        'name'  => __('Roof & Wall Panel Machine', 'standard'),
        'note'  => __('Concept shown in 2024, released in 2025. Sixteen profiles, one machine — the most advanced portable roof panel machine ever built.', 'standard'),
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

        <div class="grid lg:grid-cols-12 gap-10 lg:gap-16<?php echo $show_timeline ? ' mb-16 lg:mb-20' : ''; ?>">
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

        <?php if ($show_timeline) : ?>
        <div class="mb-8 lg:mb-10">
            <p class="font-mono uppercase tracking-wider text-xs text-blue-500">
                <?php esc_html_e('Six machines that defined the category', 'standard'); ?>
            </p>
        </div>
        <ol class="border-t border-blue-200 grid grid-cols-1 lg:grid-cols-6">
            <?php foreach ($milestones as $i => $m) : ?>
                <li class="px-0 lg:px-7 py-10 lg:py-12
                    <?php echo $i > 0 ? 'border-t lg:border-t-0 lg:border-l border-blue-200' : ''; ?>">
                    <div class="grid gap-4">
                        <div class="flex items-center gap-2 font-mono">
                            <span class="w-2 h-2 bg-red" aria-hidden="true"></span>
                            <span class="text-sm text-red uppercase tracking-wider"><?php echo esc_html($m['year']); ?></span>
                        </div>
                        <h3 class="font-mono font-medium text-blue-900 text-lg leading-tight">
                            <?php echo esc_html($m['model']); ?>
                        </h3>
                        <p class="font-mono uppercase tracking-wider text-xs text-blue-500 leading-snug -mt-2">
                            <?php echo esc_html($m['name']); ?>
                        </p>
                        <p class="font-sans text-blue-700 text-base leading-relaxed">
                            <?php echo esc_html($m['note']); ?>
                        </p>
                    </div>
                </li>
            <?php endforeach; ?>
        </ol>
        <?php endif; ?>

    </div>
</section>
