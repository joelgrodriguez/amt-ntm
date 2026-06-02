<?php
/**
 * Start Here — Learn From People Who Did It
 *
 * Topical-authority link rail to the live business-case articles, grouped
 * by where the reader is: starting, growing, or learning from owners.
 * Same curated-rail pattern as vs/keep-reading. Owner proof lives here as
 * named article links, deliberately not a testimonial slider (the home
 * page owns that mechanism). All slugs verified live under /learning-center/.
 *
 * @package Standard
 *
 * @usage Start Here (page-start-here.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$groups = [
    [
        'heading' => __('Starting out', 'standard'),
        'links'   => [
            ['title' => __('What it costs to start a gutter business', 'standard'), 'url' => '/learning-center/cost-and-what-to-know-starting-a-business-with-a-portable-gutter-machine/'],
            ['title' => __('Best roof panel machines for starting a business', 'standard'), 'url' => '/learning-center/best-residential-roof-panel-machines-for-starting-a-business/'],
            ['title' => __('Best K-style gutter machines for starting a business', 'standard'), 'url' => '/learning-center/best-k-style-seamless-gutter-machines-for-starting-a-business/'],
        ],
    ],
    [
        'heading' => __('Growing the business', 'standard'),
        'links'   => [
            ['title' => __('3 ways to expand your metal roofing business', 'standard'), 'url' => '/learning-center/3-ways-to-expand-your-metal-roofing-business/'],
            ['title' => __('How to market your rollforming business', 'standard'), 'url' => '/learning-center/ways-to-market-your-rollforming-business/'],
            ['title' => __('Board &amp; batten siding: a contractor’s golden opportunity', 'standard'), 'url' => '/learning-center/metal-board-and-batten-siding-a-contractors-golden-opportunity/'],
        ],
    ],
    [
        'heading' => __('From owners', 'standard'),
        'links'   => [
            ['title' => __('Classic Metals: how to build a metals business', 'standard'), 'url' => '/learning-center/classic-metals-inc-how-to-build-a-metals-business/'],
            ['title' => __('Pro advice to start or boost your gutter business', 'standard'), 'url' => '/learning-center/pro-advise-to-start-or-boost-your-gutter-business/'],
            ['title' => __('Calculate your ROI on a standing seam machine', 'standard'), 'url' => '/learning-center/how-to-calculate-your-roi-on-a-portable-standing-seam-machine/'],
        ],
    ],
];
?>

<section class="section bg-blue-50 border-t border-blue-200" aria-labelledby="start-here-proof-title">
    <div class="container section-content">

        <div class="section-header-left max-w-2xl">
            <p class="section-eyebrow text-blue-600"><?php esc_html_e('Learn from people who did it', 'standard'); ?></p>
            <div class="section-divider"></div>
            <h2 id="start-here-proof-title" class="section-title">
                <?php esc_html_e('Real Owners, Real Playbooks', 'standard'); ?>
            </h2>
        </div>

        <div class="grid gap-px border border-blue-200 bg-blue-200 md:grid-cols-3">
            <?php foreach ($groups as $group) : ?>
                <div class="flex flex-col gap-5 bg-blue-50 p-6 lg:p-8">
                    <h3 class="font-mono text-xs uppercase tracking-mono-label text-blue-600">
                        <?php echo wp_kses_post($group['heading']); ?>
                    </h3>
                    <ul class="grid gap-1">
                        <?php foreach ($group['links'] as $link) : ?>
                            <li>
                                <a
                                    href="<?php echo esc_url(\Standard\Url\internal($link['url'])); ?>"
                                    class="group flex min-h-11 items-start gap-3 py-2 text-base text-blue-700 transition-colors hover:text-blue-500"
                                >
                                    <span class="mt-1 shrink-0 text-blue-400 transition-colors group-hover:text-blue-500" aria-hidden="true">
                                        <?php icon('arrow-right', ['class' => 'w-4 h-4']); ?>
                                    </span>
                                    <span class="text-pretty"><?php echo wp_kses_post($link['title']); ?></span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>
