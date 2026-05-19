<?php
/**
 * Machine Product — Case Study
 *
 * Long-form case study composed in the chrome-bar frame language
 * shared with three-step-plan and blueprint:
 *
 *   - top chrome bar (red dot + 'Case Study', right side: company / location)
 *   - full-width photo
 *   - 01 / 02 / 03 indexed cells (Challenge, Solution, Results)
 *   - typographic pull-quote inset into the text flow
 *   - flat stats strip with hairline dividers (no card grid)
 *   - bottom chrome bar (CTA + segmented red-tipped indicator)
 *
 * No hero-metric grid, no testimonial card, no green icons. The
 * narrative is the visual.
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

// Placeholder case study when machine data is empty. Stays on-brand
// (no em-dashes, no AI-tell metric grid).
if (empty($case_study)) {
    $case_study = [
        'eyebrow'   => __('Case Study', 'standard'),
        'title'     => __('From subcontracting to self-sufficient in 12 months.', 'standard'),
        'company'   => 'Rocky Mountain Metal Works',
        'location'  => 'Boise, ID',
        'image'     => 'https://newtechmachinery.com/wp-content/uploads/2025/04/Nate-training-East-Kentucky-Metal-9-scaled.jpg',
        'image_alt' => __('Crew member training on an NTM rollformer at Rocky Mountain Metal Works.', 'standard'),
        'challenge' => [
            'heading' => __('Challenge', 'standard'),
            'text'    => __('Rocky Mountain Metal Works was losing 30% of their margin to outsourced panel production. Lead times of 2 to 3 weeks meant missed deadlines, unhappy clients, and jobs going to competitors who could deliver faster.', 'standard'),
        ],
        'solution' => [
            'heading' => __('Solution', 'standard'),
            'text'    => __('After investing in an NTM rollformer the team went from relying on third-party suppliers to producing panels on-site the same day. NTM\'s training program had their crew fully operational within a week. The setup is portable, so the same machine runs on the shop floor or at the jobsite.', 'standard'),
        ],
        'results' => [
            'heading' => __('Results', 'standard'),
            'text'    => __('Within 12 months Rocky Mountain Metal Works had paid off their machine investment and tripled their project capacity. Margin recovered, schedule controlled, work kept in-house.', 'standard'),
        ],
        'stats' => [
            ['stat' => '30%',    'label' => __('Margin recovered', 'standard')],
            ['stat' => '12 mo',  'label' => __('ROI payback', 'standard')],
            ['stat' => '3×',     'label' => __('Project capacity', 'standard')],
            ['stat' => '$150K+', 'label' => __('Annual savings', 'standard')],
        ],
        'quote' => [
            'text' => __('The machine paid for itself before we even finished our first year. Now we\'re taking on bigger projects and keeping every dollar of margin in-house.', 'standard'),
            'name' => 'Brian Kowalski',
            'role' => __('Owner, Rocky Mountain Metal Works', 'standard'),
        ],
        'cta_text' => __('Read the Full Case Study', 'standard'),
        'cta_url'  => '',
    ];
}

// Build the three indexed cells, skipping any with no copy.
$narrative = [];
foreach (['challenge', 'solution', 'results'] as $key) {
    if (!empty($case_study[$key]['text'])) {
        $narrative[] = [
            'index'   => sprintf('%02d', count($narrative) + 1),
            'heading' => $case_study[$key]['heading'] ?? ucfirst($key),
            'text'    => $case_study[$key]['text'],
        ];
    }
}
?>

<section id="machine-case-study" class="bg-white text-blue-600 border-y border-blue-200" aria-labelledby="case-study-title">

    <!-- Top chrome bar -->
    <div class="border-b border-blue-200">
        <div class="border-x border-blue-200 container">
            <div class="flex items-center justify-between py-3 text-xs font-mono uppercase tracking-wider">
                <div class="flex items-center gap-3 pl-3">
                    <span class="w-2 h-2 bg-red" aria-hidden="true"></span>
                    <span><?php echo esc_html($case_study['eyebrow'] ?? __('Case Study', 'standard')); ?></span>
                </div>
                <div class="flex items-center gap-3 pr-3">
                    <span><?php echo esc_html($case_study['company']); ?></span>
                    <?php if (!empty($case_study['location'])) : ?>
                        <span class="text-blue-400" aria-hidden="true">/</span>
                        <span><?php echo esc_html($case_study['location']); ?></span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Body: image, title, narrative, pull quote -->
    <div class="border-x border-blue-200 container">

        <!-- Image — full-bleed within the frame, no card -->
        <?php if (!empty($case_study['image'])) : ?>
            <div class="border-b border-blue-200">
                <?php \Standard\Images\responsive_image($case_study['image'], $case_study['image_alt'] ?? $case_study['company'], 'large', [
                    'class' => 'block w-full h-[300px] md:h-[420px] lg:h-[520px] object-cover',
                ]); ?>
            </div>
        <?php endif; ?>

        <!-- Title block: hard-left, on its own air -->
        <div class="px-6 lg:px-8 pt-10 lg:pt-16 max-w-4xl">
            <h2 id="case-study-title" class="font-sans font-medium text-blue-900 text-2xl md:text-3xl lg:text-4xl leading-tight tracking-tight">
                <?php echo esc_html($case_study['title']); ?>
            </h2>
        </div>

        <!-- Narrative: indexed cells -->
        <?php if (!empty($narrative)) : ?>
            <div class="px-6 lg:px-8 pt-8 lg:pt-12 pb-4 lg:pb-6 max-w-4xl">
                <div class="grid gap-10 lg:gap-12">
                    <?php foreach ($narrative as $act) : ?>
                        <div class="grid gap-3">
                            <div class="flex items-baseline gap-2 font-mono uppercase tracking-wider text-xs text-blue-500">
                                <span><?php echo esc_html($act['index']); ?></span>
                                <span class="w-8 h-px bg-blue-300" aria-hidden="true"></span>
                                <span><?php echo esc_html($act['heading']); ?></span>
                            </div>
                            <p class="font-sans text-blue-700 text-base lg:text-lg leading-relaxed max-w-prose">
                                <?php echo esc_html($act['text']); ?>
                            </p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Pull quote: hard-left sans display, mono caption, hairline above + below.
             Inset into the text flow, not a card. -->
        <?php if (!empty($case_study['quote']['text'])) : ?>
            <div class="border-y border-blue-200 mx-6 lg:mx-8 my-10 lg:my-12">
                <blockquote class="py-10 lg:py-14 grid gap-6 max-w-4xl">
                    <p class="font-sans font-medium text-blue-900 text-2xl md:text-3xl lg:text-4xl leading-snug tracking-tight">
                        <span aria-hidden="true" class="text-red">&ldquo;</span><?php echo esc_html($case_study['quote']['text']); ?><span aria-hidden="true" class="text-red">&rdquo;</span>
                    </p>
                    <?php if (!empty($case_study['quote']['name'])) : ?>
                        <footer class="flex items-center gap-2 font-mono uppercase tracking-wider text-xs text-blue-500">
                            <span class="w-8 h-px bg-blue-300" aria-hidden="true"></span>
                            <cite class="not-italic text-blue-900"><?php echo esc_html($case_study['quote']['name']); ?></cite>
                            <?php if (!empty($case_study['quote']['role'])) : ?>
                                <span class="text-blue-400" aria-hidden="true">/</span>
                                <span><?php echo esc_html($case_study['quote']['role']); ?></span>
                            <?php endif; ?>
                        </footer>
                    <?php endif; ?>
                </blockquote>
            </div>
        <?php endif; ?>

        <!-- Stats strip: flat horizontal row, hairline dividers between cells.
             No cards, no icons. Hairlines come from a uniform border-l + the
             :first-child carve-out, plus a horizontal divider between the
             two mobile rows. -->
        <?php if (!empty($case_study['stats'])) : ?>
            <dl class="grid grid-cols-2 md:grid-cols-4 border-t border-blue-200 [&>div]:border-l [&>div]:border-blue-200 [&>div:first-child]:border-l-0">
                <?php foreach ($case_study['stats'] as $i => $stat) : ?>
                    <div class="grid gap-1 p-6 lg:p-8 <?php echo $i >= 2 ? 'border-t md:border-t-0 border-blue-200' : ''; ?>">
                        <dd class="font-sans font-medium text-blue-900 text-3xl md:text-4xl lg:text-5xl leading-none tracking-tight">
                            <?php echo esc_html($stat['stat']); ?>
                        </dd>
                        <dt class="font-mono uppercase tracking-wider text-xs text-blue-500">
                            <?php echo esc_html($stat['label']); ?>
                        </dt>
                    </div>
                <?php endforeach; ?>
            </dl>
        <?php endif; ?>

    </div>

    <!-- Bottom chrome bar -->
    <div class="border-t border-blue-200">
        <div class="border-x border-blue-200 container">
            <div class="flex items-center justify-between py-3 font-mono uppercase tracking-wider text-[0.625rem] md:text-xs">
                <div class="flex items-center gap-2 pl-3">
                    <?php icon('file-text', ['class' => 'w-3 h-3 text-red']); ?>
                    <span class="text-blue-900"><?php esc_html_e('Customer Story', 'standard'); ?></span>
                </div>
                <div class="flex items-center gap-4 pr-3">
                    <?php if (!empty($case_study['cta_url'])) : ?>
                        <span class="hidden md:inline"><?php esc_html_e('Read', 'standard'); ?></span>
                        <a
                            href="<?php echo esc_url($case_study['cta_url']); ?>"
                            class="text-blue-900 hover:text-blue-500"
                        >
                            <?php echo esc_html($case_study['cta_text'] ?? __('Full Case Study', 'standard')); ?>
                        </a>
                    <?php else : ?>
                        <span class="text-blue-900"><?php echo esc_html($case_study['company']); ?></span>
                    <?php endif; ?>
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
