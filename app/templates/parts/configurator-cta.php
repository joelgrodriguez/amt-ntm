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
$configurator_url = \Standard\Woo\Catalog\get_configurator_url($slug);
$contact_url      = \Standard\Url\internal('/contact/');
$finance          = is_array($machine) ? ($machine['finance'] ?? []) : [];
$price_range      = $finance['price_range'] ?? '';
$monthly_price    = $finance['monthly_price'] ?? '';
$finance_note     = $finance['note'] ?? '';
$apr              = $finance['apr'] ?? '';
$months           = $finance['months'] ?? '';

if ($configurator_url === '') {
    return;
}

$machine_short = $product_name;
if (
    function_exists('Standard\\MachinesData\\get_all_machines')
    && function_exists('Standard\\MachineProductData\\get_slug_aliases')
) {
    $aliases   = \Standard\MachineProductData\get_slug_aliases();
    $data_slug = $aliases[$slug] ?? $slug;
    foreach (\Standard\MachinesData\get_all_machines(true) as $m) {
        if (($m['slug'] ?? '') === $data_slug) {
            $machine_short = $m['name'] ?? $product_name;
            break;
        }
    }
}

$quote_doc_id = strtoupper('Q-' . substr($slug, 0, 4) . '-' . str_pad((string) ($product->get_id() % 10000), 4, '0', STR_PAD_LEFT));
$quote_total  = $price_range !== '' ? $price_range : ($monthly_price !== '' ? $monthly_price . '/mo' : '');
$quote_lines  = is_array($machine) ? array_slice($machine['specs']['standard_features'] ?? [], 0, 3) : [];

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
                    /* translators: %s: short product name, e.g. "MACH II Combo" */
                    esc_html__('Configure your %s online.', 'standard'),
                    esc_html($machine_short)
                );
                ?>
            </h2>
            <p class="configurator-cta__lede">
                <?php esc_html_e('No phone call. No sales gatekeeper. The whole path runs in the browser.', 'standard'); ?>
            </p>
        </div>

        <div class="configurator-cta__body">
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

            <?php if ($quote_total !== '' || !empty($quote_lines)) : ?>
                <aside class="configurator-cta__quote" aria-label="<?php esc_attr_e('Sample quote preview', 'standard'); ?>">
                    <header class="configurator-cta__quote-header">
                        <p class="configurator-cta__quote-meta">
                            <span><?php esc_html_e('Quote', 'standard'); ?></span>
                            <span class="configurator-cta__quote-doc"><?php echo esc_html($quote_doc_id); ?></span>
                        </p>
                        <p class="configurator-cta__quote-title">
                            <?php echo esc_html($machine_short); ?>
                        </p>
                    </header>

                    <?php if (!empty($quote_lines)) : ?>
                        <dl class="configurator-cta__quote-lines">
                            <?php foreach ($quote_lines as $line) : ?>
                                <div class="configurator-cta__quote-line">
                                    <dt><?php echo esc_html($line); ?></dt>
                                    <dd aria-hidden="true">&mdash;</dd>
                                </div>
                            <?php endforeach; ?>
                            <div class="configurator-cta__quote-line configurator-cta__quote-line--more">
                                <dt><?php esc_html_e('Configured options + accessories', 'standard'); ?></dt>
                                <dd aria-hidden="true">&mdash;</dd>
                            </div>
                        </dl>
                    <?php endif; ?>

                    <?php if ($quote_total !== '') : ?>
                        <footer class="configurator-cta__quote-total">
                            <span class="configurator-cta__quote-total-label">
                                <?php esc_html_e('Your build', 'standard'); ?>
                            </span>
                            <span class="configurator-cta__quote-total-value">
                                <?php echo esc_html($quote_total); ?>
                            </span>
                        </footer>
                    <?php endif; ?>

                    <p class="configurator-cta__quote-stamp" aria-hidden="true">
                        <?php esc_html_e('Sample. Your real quote runs in the configurator.', 'standard'); ?>
                    </p>
                </aside>
            <?php endif; ?>
        </div>

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
                <a href="<?php echo esc_url($configurator_url); ?>" class="btn btn-primary btn--commit" target="_blank" rel="noopener">
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
