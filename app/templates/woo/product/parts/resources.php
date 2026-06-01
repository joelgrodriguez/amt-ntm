<?php
/**
 * Machine Product — Resources & Support
 *
 * Hairline ledger of downloadable resources, matching the configurator-cta
 * and machine-fit vocabulary. Numbered rows, mono kicker per row, single
 * red accent dot on the section eyebrow. Replaces the earlier identical-
 * card grid (which leaned on a generic heroicon placeholder per cell).
 *
 * @package Standard
 * @var array{machine: array} $args
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$machine   = $args['machine'] ?? [];
$resources = $machine['resources'] ?? null;

if (!$resources) {
    return;
}

$rows = [];
if (!empty($resources['manual'])) {
    $rows[] = [
        'kicker' => __('Manual', 'standard'),
        'title'  => __('Operator Manual', 'standard'),
        'copy'   => __('Setup, daily operation, maintenance schedule, and troubleshooting.', 'standard'),
        'cta'    => __('Open Manual', 'standard'),
        'url'    => \Standard\Url\internal($resources['manual']),
    ];
}
if (!empty($resources['brochure'])) {
    $rows[] = [
        'kicker' => __('Brochure', 'standard'),
        'title'  => __('Product Brochure', 'standard'),
        'copy'   => __('Full spec sheet, options, configurations, and pricing reference.', 'standard'),
        'cta'    => __('Open Brochure', 'standard'),
        'url'    => \Standard\Url\internal($resources['brochure']),
    ];
}
if (!empty($resources['service_training_url'])) {
    $rows[] = [
        'kicker' => __('Service', 'standard'),
        'title'  => __('Service & Training', 'standard'),
        'copy'   => __('Hands-on training, technical support, and replacement-parts pipeline.', 'standard'),
        'cta'    => __('Learn More', 'standard'),
        'url'    => \Standard\Url\internal($resources['service_training_url']),
    ];
}

if (empty($rows)) {
    return;
}
?>

<section id="machine-resources" class="resources-ledger section bg-white" aria-labelledby="resources-title" data-reveal="fade">
    <div class="container">

        <div class="section-header-left">
            <p class="section-eyebrow"><?php esc_html_e('Resources', 'standard'); ?></p>
            <div class="section-divider"></div>
            <h2 id="resources-title" class="section-title"><?php esc_html_e('Downloads & Support', 'standard'); ?></h2>
        </div>

        <ol class="resources-ledger__rows" role="list">
            <?php foreach ($rows as $index => $row) : ?>
                <li class="resources-ledger__row">
                    <a
                        href="<?php echo esc_url($row['url']); ?>"
                        class="resources-ledger__link"
                        target="_blank"
                        rel="noopener"
                    >
                        <span class="resources-ledger__index" aria-hidden="true">
                            <?php echo esc_html(str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT)); ?>
                        </span>
                        <span class="resources-ledger__body">
                            <span class="resources-ledger__kicker">
                                <?php echo esc_html($row['kicker']); ?>
                            </span>
                            <span class="resources-ledger__title">
                                <?php echo esc_html($row['title']); ?>
                            </span>
                            <span class="resources-ledger__copy">
                                <?php echo esc_html($row['copy']); ?>
                            </span>
                        </span>
                        <span class="resources-ledger__cta">
                            <span><?php echo esc_html($row['cta']); ?></span>
                            <span aria-hidden="true" class="resources-ledger__arrow">&rarr;</span>
                        </span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ol>

    </div>
</section>
