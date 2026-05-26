<?php
/**
 * Shared Template Part — Configurator CTA
 *
 * Dedicated "Build · Quote · Finance" section for machine product pages.
 * Three numbered steps in the page's hairline-ledger vocabulary, single
 * red CTA. Pricing pulled from machine.finance.price_range when present.
 *
 * Replaces the earlier configurator-finance.php (flagship) and the
 * generic cta/closer wrapper used for this purpose on the default
 * machine template. Final-CTA / closer beats live elsewhere; this part
 * has one job: route the buyer into /configurator/<slug>/ with the
 * three-step framing of build → quote → finance.
 *
 * @package Standard
 *
 * @param array $args {
 *     @type \WC_Product $product   Required. Used for slug + display name.
 *     @type array       $machine   Optional. machine.finance keys drive
 *                                  the pricing chip; missing data falls
 *                                  back to the generic "starting at" line.
 *     @type string      $section_id Default "machine-configurator".
 * }
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$product    = $args['product'] ?? null;
$machine    = $args['machine'] ?? [];
$section_id = $args['section_id'] ?? 'machine-configurator';

if (!$product instanceof \WC_Product) {
    return;
}

$slug             = $product->get_slug();
$product_name     = $product->get_name();
$configurator_url = \Standard\Url\internal('/configurator/' . $slug . '/');
$contact_url      = \Standard\Url\internal('/contact/');
$finance          = is_array($machine) ? ($machine['finance'] ?? []) : [];
$price_range      = $finance['price_range'] ?? '';
$monthly_price    = $finance['monthly_price'] ?? '';
$finance_note     = $finance['note'] ?? '';
$apr              = $finance['apr'] ?? '';
$months           = $finance['months'] ?? '';

$steps = [
    [
        'kicker' => __('Build', 'standard'),
        'title'  => __('Pick your configuration.', 'standard'),
        'copy'   => __('Profile, power pack, control system, accessories. Every option a specialist would walk you through, surfaced as a guided picker.', 'standard'),
    ],
    [
        'kicker' => __('Quote', 'standard'),
        'title'  => __('See real pricing instantly.', 'standard'),
        'copy'   => __('No "contact us for pricing" stall. Your build returns a transparent, itemized quote you can save, print, or send to a partner.', 'standard'),
    ],
    [
        'kicker' => __('Finance', 'standard'),
        'title'  => __('Apply online when you\'re ready.', 'standard'),
        'copy'   => __('Lease-to-own and seasonal payment plans, applied for in the same flow. Most contractors pay the machine off inside the first year.', 'standard'),
    ],
];
?>

<section
    id="<?php echo esc_attr($section_id); ?>"
    class="configurator-cta bg-blue-900 text-white border-y border-blue-800"
    aria-labelledby="<?php echo esc_attr($section_id); ?>-title"
>
    <div class="container section-content">

        <div class="configurator-cta__header">
            <p class="configurator-cta__eyebrow">
                <span aria-hidden="true" class="configurator-cta__eyebrow-dot"></span>
                <?php esc_html_e('Build · Quote · Finance', 'standard'); ?>
            </p>
            <h2 id="<?php echo esc_attr($section_id); ?>-title" class="configurator-cta__title">
                <?php
                printf(
                    /* translators: %s: product name, e.g. "MACH II 5/6 Combo" */
                    esc_html__('Configure your %s online.', 'standard'),
                    esc_html($product_name)
                );
                ?>
            </h2>
            <p class="configurator-cta__lede">
                <?php esc_html_e('Build a machine to spec, get a real quote, and apply for financing without picking up the phone. The whole path lives in the configurator.', 'standard'); ?>
            </p>
        </div>

        <ol class="configurator-cta__steps" role="list">
            <?php foreach ($steps as $index => $step) : ?>
                <li class="configurator-cta__step">
                    <span class="configurator-cta__step-index" aria-hidden="true">
                        <?php echo esc_html(str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT)); ?>
                    </span>
                    <div class="configurator-cta__step-body">
                        <p class="configurator-cta__step-kicker">
                            <?php echo esc_html($step['kicker']); ?>
                        </p>
                        <h3 class="configurator-cta__step-title">
                            <?php echo esc_html($step['title']); ?>
                        </h3>
                        <p class="configurator-cta__step-copy">
                            <?php echo esc_html($step['copy']); ?>
                        </p>
                    </div>
                </li>
            <?php endforeach; ?>
        </ol>

        <div class="configurator-cta__footer">
            <?php if ($monthly_price || $price_range) : ?>
                <dl class="configurator-cta__price">
                    <?php if ($monthly_price) : ?>
                        <dt class="configurator-cta__price-label">
                            <?php esc_html_e('Payments from', 'standard'); ?>
                        </dt>
                        <dd class="configurator-cta__price-value">
                            <?php echo esc_html($monthly_price); ?><span class="configurator-cta__price-unit">/mo</span>
                        </dd>
                    <?php elseif ($price_range) : ?>
                        <dt class="configurator-cta__price-label">
                            <?php esc_html_e('Starting at', 'standard'); ?>
                        </dt>
                        <dd class="configurator-cta__price-value">
                            <?php echo esc_html($price_range); ?>
                        </dd>
                    <?php endif; ?>
                    <?php if ($apr && $months) : ?>
                        <dd class="configurator-cta__price-terms">
                            <?php
                            printf(
                                /* translators: 1: APR percentage (e.g. 4.99%), 2: term in months (e.g. 84) */
                                esc_html__('%1$s APR · up to %2$s mo', 'standard'),
                                esc_html($apr),
                                esc_html($months)
                            );
                            ?>
                        </dd>
                    <?php endif; ?>
                    <?php if ($finance_note) : ?>
                        <dd class="configurator-cta__price-note">
                            <?php echo esc_html($finance_note); ?>
                        </dd>
                    <?php endif; ?>
                </dl>
            <?php endif; ?>

            <div class="configurator-cta__actions">
                <a href="<?php echo esc_url($configurator_url); ?>" class="btn btn-primary">
                    <?php esc_html_e('Open Configurator', 'standard'); ?>
                    <?php icon('arrow-right', ['class' => 'w-5 h-5']); ?>
                </a>
                <a href="<?php echo esc_url($contact_url); ?>" class="configurator-cta__alt-link">
                    <?php esc_html_e('Or talk to a specialist', 'standard'); ?>
                    <span aria-hidden="true">&rarr;</span>
                </a>
            </div>
        </div>

    </div>
</section>
