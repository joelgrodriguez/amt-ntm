<?php
/**
 * Roof & Wall Panel Machines — SSQ3 Featured Marquee
 *
 * Bespoke flagship band for the SSQ3 MultiPro. Not the generic
 * lineup-flagship reuse: a dedicated dark marquee with hero-scale
 * product image, hairline spec ledger from data/machines/ssq3-multipro
 * stats, and a red Build & Quote CTA. This is the page's single red
 * moment.
 *
 * Sits at position 3 in page-roof-wall-panel-machines.php between the
 * brand-statement band (light, image-right) and the value-prop cards
 * (light, centered). Rhythm: light, light, DARK, light, ...
 *
 * @package Standard
 *
 * @usage Roof & Wall Panel Machines (page-roof-wall-panel-machines.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\MachineProductData\get_machine_product_data;
use function Standard\MachinesData\get_roof_wall_machines;

$detail = get_machine_product_data('ssq3-multipro');

if (!$detail) {
    return;
}

// Pull the SSQ3 lineup entry for url, price, badge — the data the
// roof-wall page already knows how to drive (slug, url, image, price).
$lineup = null;
foreach (get_roof_wall_machines(true) as $machine) {
    if (($machine['slug'] ?? '') === 'ssq3-multipro') {
        $lineup = $machine;
        break;
    }
}

if (!$lineup) {
    return;
}

$product_url  = \Standard\Url\internal($lineup['url']);
$quote_url    = \Standard\Url\with_query('/build-finance/', ['machine' => 'ssq3-multipro']);
$image_url    = $detail['hero']['image'] ?? $lineup['image'] ?? '';
$slogan       = $detail['slogan'] ?? '';
$stats        = $detail['stats'] ?? [];
$name         = $lineup['name'] ?? 'SSQ3 MultiPro';
?>

<section
    class="ssq3-marquee bg-blue-900 text-white reveal"
    aria-labelledby="roof-wall-featured-title"
>
    <div class="ssq3-marquee__grid">

        <div class="ssq3-marquee__image-frame">
            <?php if ($image_url !== '') : ?>
                <?php \Standard\Images\responsive_image($image_url, sprintf(__('%s flagship roof panel machine', 'standard'), $name), 'large', [
                    'class'   => 'ssq3-marquee__image',
                    'loading' => 'lazy',
                ]); ?>
            <?php endif; ?>
        </div>

        <div class="ssq3-marquee__content">

            <div class="ssq3-marquee__chiprow">
                <p class="section-eyebrow ssq3-marquee__chip ssq3-marquee__chip--flag">
                    <span aria-hidden="true" class="ssq3-marquee__dot"></span>
                    <?php esc_html_e('Flagship', 'standard'); ?>
                </p>
                <span aria-hidden="true" class="ssq3-marquee__chip-rule"></span>
                <p class="ssq3-marquee__chip ssq3-marquee__chip--year">
                    <?php esc_html_e('Class of 2026', 'standard'); ?>
                </p>
            </div>

            <h2
                id="roof-wall-featured-title"
                class="ssq3-marquee__title"
            >
                <a href="<?php echo esc_url($product_url); ?>" class="ssq3-marquee__title-link">
                    <?php esc_html_e('SSQ3', 'standard'); ?><span class="ssq3-marquee__tm" aria-hidden="true">&trade;</span>
                    <span class="ssq3-marquee__sub-title"><?php esc_html_e('MultiPro', 'standard'); ?></span>
                </a>
            </h2>

            <?php if ($slogan !== '') : ?>
                <p class="ssq3-marquee__slogan">
                    <?php echo esc_html($slogan); ?>
                </p>
            <?php endif; ?>

            <?php if (!empty($stats)) : ?>
                <dl class="ssq3-marquee__ledger">
                    <?php foreach ($stats as $stat) : ?>
                        <div class="ssq3-marquee__ledger-row">
                            <dt class="ssq3-marquee__ledger-label">
                                <?php echo esc_html($stat['label']); ?>
                            </dt>
                            <dd class="ssq3-marquee__ledger-value">
                                <?php echo esc_html($stat['value']); ?>
                            </dd>
                        </div>
                    <?php endforeach; ?>
                </dl>
            <?php endif; ?>

            <div class="ssq3-marquee__actions">
                <a href="<?php echo esc_url($quote_url); ?>" class="btn btn-emphasis">
                    <?php esc_html_e('Build & Quote', 'standard'); ?>
                    <?php icon('arrow-right', ['class' => 'w-5 h-5']); ?>
                </a>
                <a href="<?php echo esc_url($product_url); ?>" class="btn btn-outline-light">
                    <?php esc_html_e('Explore the SSQ3', 'standard'); ?>
                </a>
            </div>

        </div>

    </div>
</section>
