<?php
/**
 * Trailer Page — NTM Trailer vs. Traditional
 *
 * The decisive section and the reason this page exists (Adam, 2026-06-17:
 * "I want to do NTM trailer versus traditional"). A blueprint comparison
 * grid: each row is a real transport concern, with what a generic flatbed
 * gives you versus what the NTM trailer was engineered to do. The contrast
 * is the argument; it justifies the price the machine pages now disclose.
 *
 * Built bespoke rather than via the shared comparison-table part, which is
 * machine-spec shaped (columns = machines). Here the two columns are a
 * value judgment (generic vs. purpose-built), not a spec sheet.
 *
 * @package Standard
 *
 * @usage page-trailer.php
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

// Each row: the concern, the generic-trailer reality, the NTM answer.
$rows = [
    [
        'concern'     => __('Load support', 'standard'),
        'generic'     => __('A flat deck rated for tonnage, with no idea where the machine\'s weight actually lands.', 'standard'),
        'ntm'         => __('Reinforcement points engineered to carry and balance the rollformer at the exact spots it loads the frame.', 'standard'),
    ],
    [
        'concern'     => __('Getting to the roof line', 'standard'),
        'generic'     => __('Unload at ground level, then figure out how to lift a long machine up to the work.', 'standard'),
        'ntm'         => __('Crane lifting eyes at each corner hoist the machine straight to the roof line.', 'standard'),
    ],
    [
        'concern'     => __('Stopping in transit', 'standard'),
        'generic'     => __('Whatever brakes came on the trailer, if any.', 'standard'),
        'ntm'         => __('Electronic brakes with a breakaway kit, so a six-figure machine stays put.', 'standard'),
    ],
    [
        'concern'     => __('Road stability', 'standard'),
        'generic'     => __('Tongue weight is your problem to balance load by load.', 'standard'),
        'ntm'         => __('Balanced by design for a light 750 lb loaded tongue weight that tracks straight.', 'standard'),
    ],
    [
        'concern'     => __('Jobsite cleanup', 'standard'),
        'generic'     => __('Shearing scrap drops wherever the machine sits.', 'standard'),
        'ntm'         => __('Metal scrap trays at the exit ends catch the offcuts and keep the site clean.', 'standard'),
    ],
    [
        'concern'     => __('Loading and unloading', 'standard'),
        'generic'     => __('Chock it and hope it doesn\'t shift.', 'standard'),
        'ntm'         => __('Four drop-foot stabilizing jacks, one per corner, steady the trailer for the load.', 'standard'),
    ],
    [
        'concern'     => __('Compliance', 'standard'),
        'generic'     => __('Varies by builder; often unstated.', 'standard'),
        'ntm'         => __('Built in compliance with National Association of Trailer Manufacturers (NATM) standards.', 'standard'),
    ],
];
?>

<section id="trailer-vs" class="section bg-blue-50 border-y border-blue-200 scroll-mt-24" aria-labelledby="trailer-vs-title">
    <div class="container section-content">

        <div class="section-header-left max-w-2xl">
            <p class="section-eyebrow"><?php esc_html_e('NTM trailer vs. traditional', 'standard'); ?></p>
            <div class="section-divider"></div>
            <h2 id="trailer-vs-title" class="section-title text-balance">
                <?php esc_html_e('The Difference Is in What It Was Built to Carry', 'standard'); ?>
            </h2>
            <p class="section-subtitle text-pretty">
                <?php esc_html_e('A generic flatbed hauls weight. The NTM trailer hauls your machine. Same job on paper, different engineering at every point that matters on the road and on the site.', 'standard'); ?>
            </p>
        </div>

        <div class="border border-blue-200 bg-white" data-reveal="fade">

            <!-- Column headers: blueprint masthead. Mono labels, the NTM column
                 carries the red accent dot as the page's pinpoint emphasis. -->
            <div class="grid grid-cols-[1fr] md:grid-cols-[minmax(0,9rem)_1fr_1fr]" role="row">
                <span class="hidden md:block border-b border-blue-200 px-6 py-4 font-mono text-[11px] uppercase tracking-mono-label text-blue-400">
                    <?php esc_html_e('Concern', 'standard'); ?>
                </span>
                <span class="border-b border-blue-200 px-6 py-4 font-mono text-[11px] uppercase tracking-mono-label text-blue-400 md:border-l">
                    <?php esc_html_e('A traditional trailer', 'standard'); ?>
                </span>
                <span class="flex items-center gap-2 border-b border-blue-200 bg-blue-900 px-6 py-4 font-mono text-[11px] uppercase tracking-mono-label text-white md:border-l md:border-blue-700">
                    <span class="inline-block h-1.5 w-1.5 bg-red" aria-hidden="true"></span>
                    <?php esc_html_e('The NTM trailer', 'standard'); ?>
                </span>
            </div>

            <?php foreach ($rows as $i => $row) :
                $last = $i === count($rows) - 1;
                $row_border = $last ? '' : 'border-b border-blue-200';
            ?>
                <div class="grid grid-cols-[1fr] md:grid-cols-[minmax(0,9rem)_1fr_1fr] <?php echo esc_attr($row_border); ?>" role="row">

                    <!-- Concern label -->
                    <div class="px-6 pt-6 pb-2 md:py-6 md:pb-6">
                        <span class="font-mono text-[11px] uppercase tracking-mono-label text-blue-500">
                            <?php echo esc_html($row['concern']); ?>
                        </span>
                    </div>

                    <!-- Generic trailer: muted, x mark -->
                    <div class="flex items-start gap-3 px-6 pb-4 md:py-6 md:border-l md:border-blue-200">
                        <?php icon('x', ['class' => 'w-4 h-4 mt-0.5 shrink-0 text-blue-300']); ?>
                        <p class="font-sans text-[15px] leading-relaxed text-blue-600 max-w-prose">
                            <?php echo esc_html($row['generic']); ?>
                        </p>
                    </div>

                    <!-- NTM trailer: emphasized, check mark, blue-tinted cell -->
                    <div class="flex items-start gap-3 bg-blue-50 px-6 pb-6 pt-1 md:py-6 md:border-l md:border-blue-200">
                        <?php icon('check', ['class' => 'w-4 h-4 mt-0.5 shrink-0 text-blue-500']); ?>
                        <p class="font-sans text-[15px] leading-relaxed text-blue-900 max-w-prose">
                            <?php echo esc_html($row['ntm']); ?>
                        </p>
                    </div>

                </div>
            <?php endforeach; ?>

        </div>

    </div>
</section>
