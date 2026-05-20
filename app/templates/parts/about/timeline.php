<?php
/**
 * About — Product Evolution Timeline
 *
 * Quiet light section on bg-blue-50. No chrome bars. Five signature
 * firsts that defined the portable rollforming category, rendered as
 * a hairline-rail of mono cells (1 column mobile, 5 columns desktop).
 *
 * Five entries, not ten: leadership reads through restraint. Each cell
 * has body copy explaining why the milestone mattered, not just year +
 * model name.
 *
 * @package Standard
 * @usage About Page (page-about.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$content = [
    'eyebrow' => __('Product evolution', 'standard'),
    'title'   => __('The five machines that defined the category.', 'standard'),
    'lede_a'  => __('Each one shipped before the rest of the industry had an answer.', 'standard'),
    'lede_b'  => __('Most are still imitated.', 'standard'),
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
        'year'  => 'Early 90s',
        'model' => __('Polyurethane Drive Roller', 'standard'),
        'name'  => __('Industry-First Mechanism', 'standard'),
        'note'  => __('Separate forming rollers, polyurethane drive. Today almost every portable rollformer uses the approach.', 'standard'),
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
        'note'  => __('NTM\'s digital control platform. The current standard for our machines.', 'standard'),
    ],
];
?>

<section class="bg-blue-50 py-16 lg:py-24 border-t border-blue-200" aria-labelledby="about-timeline-title">
    <div class="container">

        <!-- Eyebrow + headline + lede -->
        <div class="max-w-3xl mb-12 lg:mb-16">
            <p class="font-mono uppercase tracking-wider text-xs text-red mb-5">
                <?php echo esc_html($content['eyebrow']); ?>
            </p>
            <h2 id="about-timeline-title" class="font-sans font-medium text-blue-900 text-2xl md:text-3xl lg:text-[2.5rem] leading-tight tracking-tight mb-5">
                <?php echo esc_html($content['title']); ?>
            </h2>
            <p class="font-sans text-blue-700 text-base lg:text-lg leading-relaxed max-w-2xl">
                <?php echo esc_html($content['lede_a']); ?>
                <span class="block lg:mt-1"><?php echo esc_html($content['lede_b']); ?></span>
            </p>
        </div>

        <!-- Five roomy cells on a continuous hairline. Desktop = 5 columns,
             mobile = vertical stack. No chrome bars. -->
        <ol class="border-t border-blue-200 grid grid-cols-1 lg:grid-cols-5">
            <?php foreach ($milestones as $i => $m) : ?>
                <li class="px-6 lg:px-7 py-10 lg:py-12
                    <?php echo $i > 0 ? 'border-t lg:border-t-0 lg:border-l border-blue-200' : ''; ?>">
                    <div class="grid gap-4">
                        <!-- Year + dot -->
                        <div class="flex items-center gap-2 font-mono">
                            <span class="w-2 h-2 bg-red" aria-hidden="true"></span>
                            <span class="text-sm text-red uppercase tracking-wider"><?php echo esc_html($m['year']); ?></span>
                        </div>
                        <!-- Model -->
                        <h3 class="font-mono font-medium text-blue-900 text-lg leading-tight">
                            <?php echo esc_html($m['model']); ?>
                        </h3>
                        <!-- Subtitle in mono caps -->
                        <p class="font-mono uppercase tracking-wider text-[0.625rem] text-blue-500 leading-snug -mt-2">
                            <?php echo esc_html($m['name']); ?>
                        </p>
                        <!-- Body note in sans for readability -->
                        <p class="font-sans text-blue-700 text-sm leading-relaxed">
                            <?php echo esc_html($m['note']); ?>
                        </p>
                    </div>
                </li>
            <?php endforeach; ?>
        </ol>

    </div>
</section>
