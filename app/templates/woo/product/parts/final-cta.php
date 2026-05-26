<?php
/**
 * Machine Product — Final CTA
 *
 * Single committed action panel. One primary CTA (Build), one alt link
 * (talk to a specialist). The page already carries the floating CTA,
 * the subnav Build, the hero CTA, and the full configurator-cta
 * section; stacking a 3-card grid on top reads as conversion fatigue.
 *
 * Vocabulary borrowed from configurator-cta and machine-fit: hairline
 * rules, mono editorial label, single red kicker dot.
 *
 * @package Standard
 *
 * @var array{product: \WC_Product, machine: array} $args
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$product = $args['product'] ?? null;
$machine = $args['machine'] ?? null;

if (!$product) {
    return;
}

$configurator_url = \Standard\Woo\Catalog\get_configurator_url($product->get_slug());
$contact_url      = \Standard\Url\internal('/contact/');
$product_name     = $product->get_name();
?>

<section id="machine-final-cta" class="final-cta bg-blue-900 text-white" aria-labelledby="final-cta-title">
    <div class="container section-content">

        <div class="final-cta__header">
            <p class="final-cta__eyebrow">
                <span aria-hidden="true" class="final-cta__eyebrow-dot"></span>
                <?php esc_html_e('Next step', 'standard'); ?>
            </p>
            <h2 id="final-cta-title" class="final-cta__title">
                <?php
                printf(
                    /* translators: %s: product name */
                    esc_html__('Build your %s.', 'standard'),
                    esc_html($product_name)
                );
                ?>
            </h2>
            <p class="final-cta__lede">
                <?php esc_html_e('Configure, price, and finance the machine in one flow. No phone call required to see real numbers.', 'standard'); ?>
            </p>
        </div>

        <div class="final-cta__actions">
            <?php if ($configurator_url !== '') : ?>
                <a href="<?php echo esc_url($configurator_url); ?>" class="btn btn-primary">
                    <?php esc_html_e('Open Configurator', 'standard'); ?>
                    <?php icon('arrow-right', ['class' => 'w-5 h-5']); ?>
                </a>
            <?php endif; ?>
            <a href="<?php echo esc_url($contact_url); ?>" class="<?php echo $configurator_url !== '' ? 'final-cta__alt-link' : 'btn btn-primary'; ?>">
                <?php
                if ($configurator_url !== '') {
                    esc_html_e('Or talk to a specialist', 'standard');
                    ?><span aria-hidden="true">&rarr;</span><?php
                } else {
                    esc_html_e('Talk to a Specialist', 'standard');
                    icon('arrow-right', ['class' => 'w-5 h-5']);
                }
                ?>
            </a>
        </div>

    </div>
</section>
