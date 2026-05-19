<?php
/**
 * Machine Product — Stats Band
 *
 * Hairline-divided spec strip below the hero. Pairs with the hero
 * to fill the fold (100dvh - header). Mono labels read as engineering
 * spec sheet, not marketing tiles.
 *
 * @package Standard
 * @var array{machine: array} $args
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$machine = $args['machine'] ?? [];
$stats   = $machine['stats'] ?? [];

if (empty($stats)) {
    return;
}
?>

<section class="stats-band" aria-label="<?php esc_attr_e('Key specifications', 'standard'); ?>">
    <div class="container stats-band__inner">
        <p class="stats-band__eyebrow">
            <span class="stats-band__eyebrow-dot" aria-hidden="true"></span>
            <span><?php esc_html_e('Key specifications', 'standard'); ?></span>
        </p>
        <dl class="stats-band__grid">
            <?php foreach ($stats as $stat) : ?>
                <div class="stats-band__cell">
                    <dt class="stats-band__label"><?php echo esc_html($stat['label']); ?></dt>
                    <dd class="stats-band__value"><?php echo esc_html($stat['value']); ?></dd>
                </div>
            <?php endforeach; ?>
        </dl>
    </div>
</section>
