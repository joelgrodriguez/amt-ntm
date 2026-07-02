<?php
/**
 * Finance Center — Three Paths
 *
 * The router. A buyer arrives wanting "how do I pay for this" answered fast,
 * so the page leads with three honest routes before any long-form detail:
 *
 *   1. Apply online with Corbel (fastest, soft pull, in-browser)
 *   2. Save with Section 179 (tax mechanics, deduct the purchase)
 *   3. Work with a lender (FNB preferred + the third-party directory)
 *
 * Each card jumps to its detail section further down the page (or out to the
 * Corbel application). Built from the start-here/which-path cell grammar:
 * gap-px hairline-fenced cells, mono eyebrow + sans title, a short "who this
 * is for" line, two proof points, then the route link.
 *
 * @package Standard
 *
 * @usage Finance Center (page-finance-center.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$paths = [
    [
        'index'   => '01',
        'eyebrow' => __('Fastest', 'standard'),
        'icon'    => 'clock',
        'title'   => __('Apply online with Corbel', 'standard'),
        'fit'     => __('You want an answer now. Corbel runs your application against top-tier equipment lenders and comes back fast.', 'standard'),
        'points'  => [
            __('Soft inquiry, so applying won’t touch your credit score', 'standard'),
            __('Approval details in 4–8 business hours', 'standard'),
        ],
        'cta'     => __('How Corbel works', 'standard'),
        'cta_url' => '#corbel',
        'external' => false,
    ],
    [
        'index'   => '02',
        'eyebrow' => __('Tax savings', 'standard'),
        'icon'    => 'trending-up',
        'title'   => __('Deduct it with Section 179', 'standard'),
        'fit'     => __('You’re buying before year-end. Section 179 lets you write off the equipment now instead of depreciating it for years.', 'standard'),
        'points'  => [
            sprintf(
                /* translators: %s: Section 179 deduction cap, e.g. $1,220,000 */
                __('Deduct up to %s of qualifying equipment', 'standard'),
                \Standard\Finance\section_179_cap()
            ),
            __('Applies to purchased, financed, or leased machines', 'standard'),
        ],
        'cta'     => __('See the Section 179 math', 'standard'),
        'cta_url' => '#section-179',
        'external' => false,
    ],
    [
        'index'   => '03',
        'eyebrow' => __('Work with a lender', 'standard'),
        'icon'    => 'file-text',
        'title'   => __('Finance through a bank', 'standard'),
        'fit'     => __('You’d rather go through a bank. First National Bank is NTM’s preferred lender, with proven third-party options behind it.', 'standard'),
        'points'  => [
            __('FNB equipment financing, NTM’s recommended partner', 'standard'),
            __('Apex, American Bank, Crest, and ACG on call too', 'standard'),
        ],
        'cta'     => __('See the lender directory', 'standard'),
        'cta_url' => '#lenders',
        'external' => false,
    ],
    [
        'index'   => '04',
        'eyebrow' => __('Your own bank', 'standard'),
        'icon'    => 'dollar-sign',
        'title'   => __('Bring your own lender', 'standard'),
        'fit'     => __('You already have a bank or credit union you trust. NTM works with whatever lender you bring — you don’t have to use ours.', 'standard'),
        'points'  => [
            __('Use any bank, credit union, or finance partner you choose', 'standard'),
            __('We’ll supply quotes, invoices, and specs your lender needs', 'standard'),
        ],
        'cta'     => __('See the lender directory', 'standard'),
        'cta_url' => '#lenders',
        'external' => false,
    ],
];
?>

<section id="finance-paths" class="section scroll-mt-24" aria-labelledby="finance-paths-title">
    <div class="container section-content">

        <div class="section-header-left max-w-2xl">
            <p class="section-eyebrow"><?php esc_html_e('Three ways to pay', 'standard'); ?></p>
            <div class="section-divider"></div>
            <h2 id="finance-paths-title" class="section-title">
                <?php esc_html_e('Pick the path that fits your deal.', 'standard'); ?>
            </h2>
            <p class="section-subtitle text-pretty">
                <?php esc_html_e('Most contractors mix two of these: apply with Corbel for speed, then claim Section 179 at tax time. Start wherever you are.', 'standard'); ?>
            </p>
        </div>

        <div class="grid gap-px border border-blue-200 bg-blue-200 md:grid-cols-2 lg:grid-cols-4">
            <?php foreach ($paths as $path) : ?>
                <div class="flex flex-col gap-5 bg-white p-6 md:p-8 lg:p-10">

                    <div class="flex items-center justify-between gap-4">
                        <span class="finance-path__icon" aria-hidden="true">
                            <?php icon($path['icon'], ['class' => 'w-5 h-5']); ?>
                        </span>
                        <span class="font-mono text-xs font-medium tabular-nums text-blue-400" aria-hidden="true">
                            <?php echo esc_html($path['index']); ?>
                        </span>
                    </div>

                    <p class="font-mono text-xs uppercase tracking-mono-label text-blue-500">
                        <?php echo esc_html($path['eyebrow']); ?>
                    </p>

                    <h3 class="font-sans text-xl font-medium tracking-tight text-balance text-blue-900 lg:text-2xl">
                        <?php echo esc_html($path['title']); ?>
                    </h3>

                    <p class="text-sm text-blue-600 text-pretty">
                        <?php echo esc_html($path['fit']); ?>
                    </p>

                    <ul class="grid gap-2 border-t border-blue-200 pt-4">
                        <?php foreach ($path['points'] as $point) : ?>
                            <li class="flex items-start gap-3 text-sm text-blue-700">
                                <span class="mt-2 h-px w-3 shrink-0 bg-blue-400" aria-hidden="true"></span>
                                <span><?php echo esc_html($point); ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>

                    <div class="mt-auto pt-2">
                        <a
                            href="<?php echo esc_url($path['cta_url']); ?>"
                            class="inline-flex min-h-11 items-center gap-1.5 font-mono text-xs uppercase tracking-mono-label text-blue-500 transition-colors hover:text-blue-700"
                        >
                            <?php echo esc_html($path['cta']); ?>
                            <?php icon('arrow-right', ['class' => 'w-3.5 h-3.5']); ?>
                        </a>
                    </div>

                </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>
