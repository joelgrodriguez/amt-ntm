<?php
/**
 * Machine Product — Case Study
 *
 * Single-fold magazine spread. Fills 100dvh - header on 14–16"
 * laptops so the full narrative reads without scrolling.
 *
 *   - left column: full-bleed photo (full height, ~55% width on lg+)
 *   - right column: 3-act narrative (01/02/03), pull-quote, stats strip
 *
 * Mobile / tablet stacks naturally: photo, narrative, quote, stats.
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
            'text'    => __('Losing 30% of their margin to outsourced panel production. Two to three week lead times meant missed deadlines and jobs going to faster competitors.', 'standard'),
        ],
        'solution' => [
            'heading' => __('Solution', 'standard'),
            'text'    => __('An NTM rollformer on-site. Same-day panel production, full crew trained inside a week, portable setup for shop or jobsite.', 'standard'),
        ],
        'results' => [
            'heading' => __('Results', 'standard'),
            'text'    => __('Machine paid off in 12 months, project capacity tripled, margin recovered, schedule controlled, work kept in-house.', 'standard'),
        ],
        'stats' => [
            ['stat' => '30%',    'label' => __('Margin recovered', 'standard')],
            ['stat' => '12 mo',  'label' => __('ROI payback', 'standard')],
            ['stat' => '3×',     'label' => __('Project capacity', 'standard')],
            ['stat' => '$150K+', 'label' => __('Annual savings', 'standard')],
        ],
        'quote' => [
            'text' => __('The machine paid for itself before we finished our first year. Now we take on bigger projects and keep every dollar of margin in-house.', 'standard'),
            'name' => 'Brian Kowalski',
            'role' => __('Owner', 'standard'),
        ],
        'cta_text' => __('Read the full case study', 'standard'),
        'cta_url'  => '#',
    ];
}
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

$stats_count = !empty($case_study['stats']) ? count($case_study['stats']) : 0;
?>

<section id="machine-case-study" class="case-study bg-white text-blue-600 border-y border-blue-200" aria-labelledby="case-study-title">

    <div class="case-study__shell">
        <?php if (!empty($case_study['image'])) : ?>
            <div class="case-study__photo">
                <?php \Standard\Images\responsive_image($case_study['image'], $case_study['image_alt'] ?? $case_study['company'], 'large', [
                    'class' => 'block w-full h-full object-cover',
                ]); ?>
            </div>
        <?php endif; ?>
        <div class="case-study__story">
            <p class="case-study__kicker">
                <span class="case-study__kicker-dot" aria-hidden="true"></span>
                <span><?php echo esc_html($case_study['eyebrow'] ?? __('Case Study', 'standard')); ?></span>
                <span class="case-study__kicker-sep" aria-hidden="true">/</span>
                <span><?php echo esc_html($case_study['company']); ?></span>
                <?php if (!empty($case_study['location'])) : ?>
                    <span class="case-study__kicker-sep" aria-hidden="true">/</span>
                    <span><?php echo esc_html($case_study['location']); ?></span>
                <?php endif; ?>
            </p>
            <h2 id="case-study-title" class="case-study__title">
                <?php echo esc_html($case_study['title']); ?>
            </h2>
            <?php if (!empty($narrative)) : ?>
                <div class="case-study__acts">
                    <?php foreach ($narrative as $act) : ?>
                        <div class="case-study__act">
                            <div class="case-study__act-label">
                                <span><?php echo esc_html($act['index']); ?></span>
                                <span class="case-study__act-rule" aria-hidden="true"></span>
                                <span><?php echo esc_html($act['heading']); ?></span>
                            </div>
                            <p class="case-study__act-text"><?php echo esc_html($act['text']); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <?php if (!empty($case_study['quote']['text'])) : ?>
                <blockquote class="case-study__quote">
                    <p class="case-study__quote-text">
                        <span aria-hidden="true" class="case-study__quote-mark">&ldquo;</span><?php echo esc_html($case_study['quote']['text']); ?><span aria-hidden="true" class="case-study__quote-mark">&rdquo;</span>
                    </p>
                    <?php if (!empty($case_study['quote']['name'])) : ?>
                        <footer class="case-study__quote-cite">
                            <cite class="not-italic">
                                <?php echo esc_html($case_study['quote']['name']); ?><?php if (!empty($case_study['quote']['role'])) : ?>, <?php echo esc_html($case_study['quote']['role']); ?><?php endif; ?>
                            </cite>
                        </footer>
                    <?php endif; ?>
                </blockquote>
            <?php endif; ?>
            <?php if ($stats_count > 0) : ?>
                <dl class="case-study__stats">
                    <?php foreach ($case_study['stats'] as $stat) : ?>
                        <div class="case-study__stat">
                            <dd class="case-study__stat-value"><?php echo esc_html($stat['stat']); ?></dd>
                            <dt class="case-study__stat-label"><?php echo esc_html($stat['label']); ?></dt>
                        </div>
                    <?php endforeach; ?>
                </dl>
            <?php endif; ?>
            <?php if (!empty($case_study['cta_url'])) : ?>
                <a href="<?php echo esc_url($case_study['cta_url']); ?>" class="case-study__cta">
                    <span><?php echo esc_html($case_study['cta_text'] ?? __('Read the full case study', 'standard')); ?></span>
                    <?php icon('arrow-right', ['class' => 'case-study__cta-icon']); ?>
                </a>
            <?php endif; ?>

        </div>

    </div>

</section>
