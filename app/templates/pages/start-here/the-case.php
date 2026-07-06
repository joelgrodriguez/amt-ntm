<?php
/**
 * Start Here — The Business Case
 *
 * Proves the opportunity is real using the three ROI figures NTM owners
 * actually report. Rendered as a left-aligned editorial ledger (mono
 * stat + label + one sentence of context, each linking to the article
 * that backs it up), NOT a centered big-number trophy grid.
 *
 * This is startup viability for someone with no business yet (payback,
 * money-to-start, growth). It is deliberately not the front page's
 * margin-recapture argument for existing contractors, and it routes the
 * money mechanics out to How-to-buy rather than restating prices.
 *
 * @package Standard
 *
 * @usage Start Here (page-start-here.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

// Pair each reported figure with the live article that proves it. Order
// mirrors get_roi_stats(): per-sq-ft saving, payback, owner growth.
$ledger = [
    [
        'stat'     => __('$2.25', 'standard'),
        'label'    => __('Saved per sq ft vs. buying factory panels', 'standard'),
        'context'  => __('On a single 30-square roof, that gap alone is most of a machine payment. Make the panel yourself and the saving is yours to keep.', 'standard'),
        'link'     => __('How the panel-cost math works', 'standard'),
        'url'      => '/learning-center/how-to-calculate-your-roi-on-a-portable-standing-seam-machine/',
    ],
    [
        'stat'     => __('1–2 yrs', 'standard'),
        'label'    => __('Typical machine payback period', 'standard'),
        'context'  => __("Most machine owners cover the cost with a few big jobs or a busy season. Then it's all ROI.", 'standard'),
        'link'     => __('What it costs to start', 'standard'),
        'url'      => '/learning-center/cost-and-what-to-know-starting-a-business-with-a-portable-gutter-machine/',
    ],
    [
        'stat'     => __('1 machine', 'standard'),
        'label'    => __('Is enough to start, then grow on', 'standard'),
        'context'  => __('Classic Metals started on a single NTM machine and built an entire metals business from it. Owners who go independent regularly report multiplying their volume once the panel work comes in-house.', 'standard'),
        'link'     => __('Read how Classic Metals did it', 'standard'),
        'url'      => '/learning-center/classic-metals-inc-how-to-build-a-metals-business/',
    ],
];
?>

<section id="the-case" class="section scroll-mt-24" aria-labelledby="start-here-case-title">
    <div class="container section-content">

        <div class="grid items-center gap-10 lg:grid-cols-2 lg:gap-16">

            <figure class="grid gap-3">
                <div class="aspect-video overflow-hidden border border-blue-200 bg-blue-100">
                    <?php
                    \Standard\Images\responsive_image(
                        content_url('/uploads/2026/01/JIm-and-family-with-SSQ.jpg'),
                        __('An NTM owner and his family standing with their SSQ roof panel machine', 'standard'),
                        'large',
                        ['class' => 'h-full w-full object-cover']
                    );
                    ?>
                </div>
                <figcaption class="font-mono text-[11px] text-blue-400">
                    <?php esc_html_e('A family business built around one NTM machine.', 'standard'); ?>
                </figcaption>
            </figure>

            <div class="section-header-left max-w-xl">
                <p class="section-eyebrow"><?php esc_html_e('The business case', 'standard'); ?></p>
                <div class="section-divider"></div>
                <h2 id="start-here-case-title" class="section-title">
                    <?php esc_html_e('The Numbers a First Machine Has to Beat', 'standard'); ?>
                </h2>
                <p class="section-subtitle text-pretty">
                    <?php esc_html_e('You are not buying a tool, you are buying a margin. These are the figures NTM owners report, and the work that backs each one up.', 'standard'); ?>
                </p>
            </div>

        </div>

        <dl class="grid gap-px border border-blue-200 bg-blue-200 md:grid-cols-3">
            <?php foreach ($ledger as $row) : ?>
                <div class="flex flex-col gap-3 bg-white p-6 lg:p-8">
                    <dt class="font-mono text-3xl font-medium tracking-tight text-blue-900 lg:text-4xl">
                        <?php echo esc_html($row['stat']); ?>
                    </dt>
                    <dd class="flex flex-1 flex-col gap-3">
                        <p class="font-mono text-[10px] uppercase tracking-mono-meta text-blue-400">
                            <?php echo esc_html($row['label']); ?>
                        </p>
                        <p class="text-base text-blue-600 text-pretty">
                            <?php echo esc_html($row['context']); ?>
                        </p>
                        <a
                            href="<?php echo esc_url(\Standard\Url\internal($row['url'])); ?>"
                            class="group mt-auto inline-flex min-h-11 items-center gap-2 font-mono text-xs uppercase tracking-mono-label text-blue-500 transition-colors hover:text-blue-700"
                        >
                            <?php echo esc_html($row['link']); ?>
                            <span class="transition-transform group-hover:translate-x-0.5" aria-hidden="true">
                                <?php icon('arrow-right', ['class' => 'w-4 h-4']); ?>
                            </span>
                        </a>
                    </dd>
                </div>
            <?php endforeach; ?>
        </dl>

    </div>
</section>
