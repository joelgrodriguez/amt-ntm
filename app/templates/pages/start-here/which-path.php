<?php
/**
 * Start Here — Which Path Is Me
 *
 * The router, and the reason this page exists. A hover-flyout can list
 * the four lanes; only a page can ask "which of these is you?" and give
 * enough context to answer. Four lanes in a 2x2 field: three route deeper
 * into the funnel (learn, qualify, choose); the fourth routes OUT to the
 * money mechanics on How-to-buy, and is intentionally de-emphasized so it
 * reads as the exit, not a fourth identical card.
 *
 * Built from the vs/the-fork cell grammar, generalized to a 2x2: each
 * cell adds a top hairline when it is not in the first row, and a left
 * hairline (md+) when it is not in the first column, so the blueprint
 * single-fence look holds without doubling borders.
 *
 * @package Standard
 *
 * @usage Start Here (page-start-here.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

// 'tone' => 'in' routes deeper into the funnel (white cell, primary CTA);
// 'out' is the money lane (tinted cell, outline CTA) that hands off to
// How-to-buy. links[] are optional secondary text links under the CTA.
$lanes = [
    [
        'tone'    => 'in',
        'eyebrow' => __('“I don’t know the trade yet”', 'standard'),
        'title'   => __('Learn the trade', 'standard'),
        'fit'     => __('You like the idea but you are not sure what these machines actually make, or which side of the work fits you.', 'standard'),
        'points'  => [
            __('Roofing panels vs. seamless gutters, in plain terms', 'standard'),
            __('What a portable rollformer is and how it runs', 'standard'),
        ],
        'cta'     => __('Roof panel vs gutter', 'standard'),
        'cta_url' => '/roof-panel-vs-gutter/',
        'links'   => [
            ['label' => __('What is an NTM machine?', 'standard'), 'url' => '/learning-center/portable-rollforming-machine-equipment-types-uses/'],
        ],
    ],
    [
        'tone'    => 'in',
        'eyebrow' => __('“Could I actually do this?”', 'standard'),
        'title'   => __('See if it fits you', 'standard'),
        'fit'     => __('You get the concept and want an honest gut-check on whether you are ready to manufacture and sell your own product.', 'standard'),
        'points'  => [
            __('A short readiness quiz, no signup', 'standard'),
            __('Profit math you can run on your own numbers', 'standard'),
        ],
        'cta'     => __('Take the readiness quiz', 'standard'),
        'cta_url' => '/portable-rollforming-machine-readiness-assessment/',
        'links'   => [
            ['label' => __('Profit calculator', 'standard'), 'url' => '/learning-center/download/portable-rollforming-profit-calculator/'],
        ],
    ],
    [
        'tone'    => 'in',
        'eyebrow' => __('“Just show me the iron”', 'standard'),
        'title'   => __('Choose a machine', 'standard'),
        'fit'     => __('You know the trade and you are ready to look at the lineup and find the machine that matches your work.', 'standard'),
        'points'  => [
            __('Browse every NTM machine by what it makes', 'standard'),
            __('A guided chooser if you want a shortcut', 'standard'),
        ],
        'cta'     => __('Find your machine', 'standard'),
        'cta_url' => '/choose-your-machine/',
        'links'   => [
            ['label' => __('See all machines', 'standard'), 'url' => '/machines/'],
        ],
    ],
    [
        'tone'    => 'out',
        'eyebrow' => __('“What will it cost me?”', 'standard'),
        'title'   => __('Handle the money', 'standard'),
        'fit'     => __('You are close, and the open question is price, financing, and how buying from NTM actually works.', 'standard'),
        'points'  => [
            __('How the buying process runs, start to finish', 'standard'),
            __('Financing and lease-to-own options', 'standard'),
        ],
        'cta'     => __('What to expect when purchasing', 'standard'),
        'cta_url' => '/learning-center/what-to-expect-purchasing-portable-rollforming-machine/',
        'links'   => [
            ['label' => __('Financing &amp; leasing', 'standard'), 'url' => '/leasing-financing/'],
        ],
    ],
];
?>

<section id="which-path" class="section scroll-mt-24" aria-labelledby="which-path-title">
    <div class="container section-content">

        <div class="section-header-left max-w-2xl">
            <p class="section-eyebrow"><?php esc_html_e('Pick a direction', 'standard'); ?></p>
            <div class="section-divider"></div>
            <h2 id="which-path-title" class="section-title">
                <?php esc_html_e('Where Are You Right Now?', 'standard'); ?>
            </h2>
            <p class="section-subtitle text-pretty">
                <?php esc_html_e('Four honest starting points. Find the one that sounds like you and take the next step from there. There is no wrong door.', 'standard'); ?>
            </p>
        </div>

        <div class="grid gap-px border border-blue-200 bg-blue-200 md:grid-cols-2">
            <?php foreach ($lanes as $i => $lane) :
                // gap-px + a hairline grid background fences all four cells
                // crisply; each cell just paints its own surface over it.
                $is_out  = $lane['tone'] === 'out';
                $surface = $is_out ? 'bg-blue-50' : 'bg-white';
            ?>
                <div class="flex flex-col gap-5 p-6 md:p-8 lg:p-10 <?php echo esc_attr($surface); ?>">

                    <div class="grid gap-2">
                        <p class="font-mono text-xs uppercase tracking-mono-label text-blue-500">
                            <?php echo esc_html($lane['eyebrow']); ?>
                        </p>
                        <h3 class="font-sans text-xl font-medium tracking-tight text-balance text-blue-900 lg:text-2xl">
                            <?php echo esc_html($lane['title']); ?>
                        </h3>
                    </div>

                    <p class="text-sm text-blue-600 text-pretty">
                        <?php echo esc_html($lane['fit']); ?>
                    </p>

                    <ul class="grid gap-2 border-t border-blue-200 pt-4">
                        <?php foreach ($lane['points'] as $point) : ?>
                            <li class="flex items-start gap-3 text-sm text-blue-700">
                                <span class="mt-2 h-px w-3 shrink-0 bg-blue-400" aria-hidden="true"></span>
                                <span><?php echo wp_kses_post($point); ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>

                    <div class="mt-auto grid gap-3 pt-2">
                        <a
                            href="<?php echo esc_url(\Standard\Url\internal($lane['cta_url'])); ?>"
                            class="btn <?php echo $is_out ? 'btn-outline-dark' : 'btn-primary'; ?> w-full justify-center sm:w-auto"
                        >
                            <?php echo esc_html($lane['cta']); ?>
                            <?php icon('arrow-right', ['class' => 'w-5 h-5']); ?>
                        </a>
                        <?php
                        // Only the route-out (money) lane keeps a secondary
                        // link; the three in-funnel lanes read as four clean
                        // choices, with the primary CTA carrying the intent.
                        if ($is_out) :
                            foreach ($lane['links'] as $link) : ?>
                                <a
                                    href="<?php echo esc_url(\Standard\Url\internal($link['url'])); ?>"
                                    class="inline-flex min-h-11 items-center gap-1.5 font-mono text-[11px] uppercase tracking-mono-label text-blue-500 transition-colors hover:text-blue-700"
                                >
                                    <?php echo wp_kses_post($link['label']); ?>
                                    <?php icon('arrow-right', ['class' => 'w-3.5 h-3.5']); ?>
                                </a>
                            <?php endforeach;
                        endif; ?>
                    </div>

                </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>
