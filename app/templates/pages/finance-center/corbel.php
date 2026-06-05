<?php
/**
 * Finance Center — Corbel detail
 *
 * The "Apply online" path, expanded. Left column: what Corbel is and the
 * four-step how-it-works. Right column: a hairline terms ledger (term
 * lengths, APR range, structures) and the primary Apply CTA. The fine print
 * (final approval subject to underwriting) sits quietly beneath.
 *
 * Copy carried over from the legacy Gutenberg page, tightened: Corbel is a
 * technology layer over top-tier equipment lenders, not a lender itself.
 *
 * @package Standard
 *
 * @usage Finance Center (page-finance-center.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$apply_url = 'https://app.corbelpay.com/reception/newtechmachinery/applications/spot?p=';

$steps = [
    __('Submit your credit application, even if your quote isn’t finalized.', 'standard'),
    __('Get approval details back within 4–8 business hours.', 'standard'),
    __('Finalize your equipment purchase with your NTM sales rep.', 'standard'),
    __('Pick the payment plan that fits your season and cash flow.', 'standard'),
];

$terms = [
    [
        'label' => __('Terms', 'standard'),
        'value' => __('36 / 48 / 60 mo', 'standard'),
    ],
    [
        'label' => __('APR range', 'standard'),
        'value' => __('8–36%', 'standard'),
    ],
    [
        'label' => __('Structures', 'standard'),
        'value' => __('EFA · FMV lease', 'standard'),
    ],
    [
        'label' => __('Credit pull', 'standard'),
        'value' => __('Soft inquiry', 'standard'),
    ],
];
?>

<section id="corbel" class="section bg-blue-50 scroll-mt-24" aria-labelledby="corbel-title">
    <div class="container">
        <div class="grid gap-12 lg:grid-cols-[minmax(0,1fr)_minmax(20rem,24rem)] lg:gap-16 lg:items-start">

            <div class="grid gap-8 content-start min-w-0">
                <div class="section-header-left max-w-2xl">
                    <p class="section-eyebrow"><?php esc_html_e('Apply online with Corbel', 'standard'); ?></p>
                    <div class="section-divider"></div>
                    <h2 id="corbel-title" class="section-title">
                        <?php esc_html_e('Configure and finance your NTM machine with Corbel.', 'standard'); ?>
                    </h2>
                    <p class="section-subtitle text-pretty">
                        <?php esc_html_e('Corbel isn’t a lender. It’s a technology company that runs your application against some of the industry’s best equipment finance providers, so you get the best rate you qualify for from one application.', 'standard'); ?>
                    </p>
                </div>

                <ol class="finance-steps" role="list">
                    <?php foreach ($steps as $index => $step) : ?>
                        <li class="finance-steps__item">
                            <span class="finance-steps__index" aria-hidden="true">
                                <?php echo esc_html(str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT)); ?>
                            </span>
                            <span class="finance-steps__copy"><?php echo esc_html($step); ?></span>
                        </li>
                    <?php endforeach; ?>
                </ol>
            </div>

            <aside class="finance-terms" aria-label="<?php esc_attr_e('Corbel financing terms', 'standard'); ?>">
                <p class="finance-terms__eyebrow"><?php esc_html_e('At a glance', 'standard'); ?></p>
                <dl class="finance-terms__list">
                    <?php foreach ($terms as $term) : ?>
                        <div class="finance-terms__row">
                            <dt><?php echo esc_html($term['label']); ?></dt>
                            <dd><?php echo esc_html($term['value']); ?></dd>
                        </div>
                    <?php endforeach; ?>
                </dl>

                <a
                    href="<?php echo esc_url($apply_url); ?>"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="btn btn-emphasis btn--commit w-full justify-center"
                    aria-label="<?php esc_attr_e('Apply for financing with Corbel (opens in a new tab)', 'standard'); ?>"
                >
                    <?php esc_html_e('Apply for financing', 'standard'); ?>
                    <?php icon('arrow-right', ['class' => 'w-5 h-5']); ?>
                </a>

                <p class="finance-terms__fine">
                    <?php esc_html_e('Final approval is subject to credit assessment, underwriting, and equipment and vendor approval. Amounts, terms, and rates vary with creditworthiness. Terms and conditions apply.', 'standard'); ?>
                </p>
            </aside>

        </div>
    </div>
</section>
