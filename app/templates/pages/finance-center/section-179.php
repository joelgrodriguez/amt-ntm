<?php
/**
 * Finance Center — Section 179
 *
 * The tax-savings path, framed as a value block instead of the legacy wall
 * of legal text. Three benefit points (reduce taxable income, invest in
 * equipment, protect cash flow), the headline deduction figure as a mono
 * spec, then the Apex calculator CTA and the learning-center deep-dive. The
 * "consult a tax professional" qualifier sits quietly at the foot.
 *
 * @package Standard
 *
 * @usage Finance Center (page-finance-center.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$calculator_url = 'https://financewithapex.com/tax-savings/';
$learn_url      = \Standard\Url\internal('/learning-center/section-179-tax-deduction-maximize-savings-on-a-portable-rollformer/');

$benefits = [
    [
        'title' => __('Reduce your taxable income', 'standard'),
        'copy'  => __('Write off the equipment purchase against this year’s income instead of depreciating it over time.', 'standard'),
    ],
    [
        'title' => __('Invest while you save', 'standard'),
        'copy'  => __('Put a capital machine to work now and let the deduction offset a real chunk of the cost.', 'standard'),
    ],
    [
        'title' => __('Protect your cash flow', 'standard'),
        'copy'  => __('Pair Section 179 with financing: low monthly payments now, the full tax benefit this year.', 'standard'),
    ],
];
?>

<section id="section-179" class="section scroll-mt-24" aria-labelledby="section-179-title">
    <div class="container section-content">

        <div class="grid gap-10 lg:grid-cols-[minmax(0,1fr)_auto] lg:items-end lg:gap-16">
            <div class="section-header-left max-w-2xl">
                <p class="section-eyebrow"><?php esc_html_e('Tax savings — Section 179', 'standard'); ?></p>
                <div class="section-divider"></div>
                <h2 id="section-179-title" class="section-title">
                    <?php esc_html_e('Pay for your machine over time, deduct it this year.', 'standard'); ?>
                </h2>
                <p class="section-subtitle text-pretty">
                    <?php esc_html_e('Section 179 lets businesses deduct qualifying equipment from taxable income in the year it’s put into service, whether you buy, finance, or lease it.', 'standard'); ?>
                </p>
            </div>

            <dl class="finance-figure">
                <dt class="finance-figure__label"><?php esc_html_e('Deduct up to', 'standard'); ?></dt>
                <dd class="finance-figure__value">$1,220,000</dd>
                <dd class="finance-figure__note"><?php esc_html_e('of qualifying equipment', 'standard'); ?></dd>
            </dl>
        </div>

        <div class="grid gap-px border border-blue-200 bg-blue-200 md:grid-cols-3">
            <?php foreach ($benefits as $benefit) : ?>
                <div class="grid gap-2 bg-white p-6 md:p-8">
                    <h3 class="font-sans text-lg font-medium tracking-tight text-blue-900">
                        <?php echo esc_html($benefit['title']); ?>
                    </h3>
                    <p class="text-sm text-blue-600 text-pretty">
                        <?php echo esc_html($benefit['copy']); ?>
                    </p>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
            <a
                href="<?php echo esc_url($calculator_url); ?>"
                target="_blank"
                rel="noopener noreferrer"
                class="btn btn-primary w-full justify-center sm:w-auto"
                aria-label="<?php esc_attr_e('Open the Section 179 savings calculator (opens in a new tab)', 'standard'); ?>"
            >
                <?php esc_html_e('Open the savings calculator', 'standard'); ?>
                <?php icon('arrow-right', ['class' => 'w-5 h-5']); ?>
            </a>
            <a
                href="<?php echo esc_url($learn_url); ?>"
                class="inline-flex min-h-11 items-center gap-1.5 font-mono text-xs uppercase tracking-mono-label text-blue-500 transition-colors hover:text-blue-700"
            >
                <?php esc_html_e('Read the Section 179 guide', 'standard'); ?>
                <?php icon('arrow-right', ['class' => 'w-3.5 h-3.5']); ?>
            </a>
        </div>

        <p class="text-sm text-blue-400 max-w-3xl text-pretty">
            <?php esc_html_e('Not all equipment qualifies, and deduction limits vary with your total purchases and business income. Consult a tax professional to confirm eligibility and how Section 179 applies to your business.', 'standard'); ?>
        </p>

    </div>
</section>
