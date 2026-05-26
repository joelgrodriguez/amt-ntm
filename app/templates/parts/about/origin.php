<?php
/**
 * About — Built it. Still building it.
 *
 * Merged origin + timeline. One section, two beats: a short legacy lede
 * that names 1991, Larry Coben, and the Mazzella acquisition; then the
 * 5-machine timeline of category firsts as proof we're still shipping.
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
    'title'   => __('Larry Coben shipped the SSP in 1991 and started a category. We\'re still the ones defining it.', 'standard'),
    'p1'      => __('NTM was the first to commercially produce a polyurethane drive-roller machine with separate forming rollers. Today almost every portable rollformer on the market uses that approach. The patents have aged out. The lead hasn\'t.', 'standard'),
    'p2'      => __('Since 2015, NTM has been part of Mazzella Companies, a third-generation, family-owned American manufacturer. The backing means the capital to keep engineering, keep manufacturing in-house, and keep investing in the next category-defining machine.', 'standard'),
];

$callouts = [
    [
        'k' => __('Founded',     'standard'),
        'v' => __('Denver, 1991', 'standard'),
    ],
    [
        'k' => __('Parent company',                'standard'),
        'v' => __('Mazzella Companies (since 2015)', 'standard'),
    ],
];

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
];
?>

<section class="bg-white py-16 lg:py-24 border-t border-blue-200" aria-labelledby="about-origin-title">
    <div class="container">

        <div class="max-w-4xl mb-12 lg:mb-16">
            <p class="font-mono uppercase tracking-wider text-xs text-red mb-5">
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
                        <span class="font-sans font-medium text-blue-900 text-base lg:text-lg leading-snug">
                            <?php echo esc_html($callout['v']); ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </aside>
        </div>

        <div class="mb-8 lg:mb-10">
            <p class="font-mono uppercase tracking-wider text-xs text-blue-500">
                <?php esc_html_e('Five machines that defined the category', 'standard'); ?>
            </p>
        </div>
        <ol class="border-t border-blue-200 grid grid-cols-1 lg:grid-cols-5">
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

    </div>
</section>
