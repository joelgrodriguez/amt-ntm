<?php
/**
 * Finance Center — Three Paths
 *
 * The router. A buyer arrives wanting "how do I pay for this" answered fast,
 * so the page leads with three honest routes before any long-form detail:
 *
 *   1. Corbel (preferred, fastest to get a machine moving)
 *   2. Finance through an institution (compare equipment lenders)
 *   3. Bring your own bank (use the lender relationship you already trust)
 *
 * Each card jumps to its detail section further down the page. Built from the
 * start-here/which-path cell grammar:
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
        'badge'   => __('Preferred · Fastest', 'standard'),
        'icon'    => 'clock',
        'title'   => __('Corbel', 'standard'),
        'fit'     => __('The fastest preferred route. Build your machine, apply once, and let Corbel match your application with top equipment lenders.', 'standard'),
        'points'  => [
            __('Build the machine and apply for financing in one flow', 'standard'),
            __('Soft inquiry with approval details in 4 to 8 business hours', 'standard'),
        ],
        'cta'     => __('Start with Corbel', 'standard'),
        'cta_url' => '#corbel',
        'external' => false,
    ],
    [
        'index'   => '02',
        'eyebrow' => __('Institution', 'standard'),
        'icon'    => 'file-text',
        'title'   => __('Finance through an institution', 'standard'),
        'fit'     => __('You’d rather compare lenders directly. NTM keeps a directory of proven equipment finance institutions contractors already use.', 'standard'),
        'points'  => [
            __('First National Bank, Apex, American Bank, Crest, and ACG', 'standard'),
            __('Compare terms and pick the one that fits your business', 'standard'),
        ],
        'cta'     => __('See the lender directory', 'standard'),
        'cta_url' => '#lenders',
        'external' => false,
    ],
    [
        'index'   => '03',
        'eyebrow' => __('Your bank', 'standard'),
        'icon'    => 'dollar-sign',
        'title'   => __('Bring your own bank', 'standard'),
        'fit'     => __('You already have a bank or credit union you trust. NTM works with whatever lender you bring. You don’t have to use ours.', 'standard'),
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
                <?php esc_html_e('Start with Corbel when speed matters, compare equipment lenders when you want options, or bring the bank relationship you already have. Section 179 can still apply whichever path funds the machine.', 'standard'); ?>
            </p>
        </div>

        <div class="grid gap-px border border-blue-200 bg-blue-200 md:grid-cols-3">
            <?php foreach ($paths as $path) : ?>
                <?php
                $is_preferred = !empty($path['badge']);
                $card_classes = 'relative flex flex-col gap-5 bg-white p-6 md:p-8 lg:p-10';
                $icon_classes = 'finance-path__icon';

                if ($is_preferred) {
                    $card_classes .= ' overflow-hidden';
                    $icon_classes .= ' border-blue-900 bg-blue-900 text-white';
                }
                ?>
                <div class="<?php echo esc_attr($card_classes); ?>">
                    <?php if ($is_preferred) : ?>
                        <span class="absolute inset-x-0 top-0 h-1 bg-red" aria-hidden="true"></span>
                    <?php endif; ?>

                    <div class="flex items-center justify-between gap-4">
                        <span class="<?php echo esc_attr($icon_classes); ?>" aria-hidden="true">
                            <?php icon($path['icon'], ['class' => 'w-5 h-5']); ?>
                        </span>
                        <span class="font-mono text-xs font-medium tabular-nums text-blue-400" aria-hidden="true">
                            <?php echo esc_html($path['index']); ?>
                        </span>
                    </div>

                    <?php if ($is_preferred) : ?>
                        <p class="inline-flex w-fit items-center border border-blue-200 bg-blue-50 px-2.5 py-1 font-mono text-[11px] font-medium uppercase tracking-mono-label text-red">
                            <?php echo esc_html($path['badge']); ?>
                        </p>
                    <?php else : ?>
                        <p class="font-mono text-xs uppercase tracking-mono-label text-blue-500">
                            <?php echo esc_html($path['eyebrow']); ?>
                        </p>
                    <?php endif; ?>

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
