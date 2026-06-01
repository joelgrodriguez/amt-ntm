<?php
/**
 * Machine Product — Machine Fit (asymmetric ledger)
 *
 * Two stacked blocks, not two cards. Top: "Built for" — large
 * left-aligned qualifier lines stacked vertically with hairline rules.
 * Bottom: "Reconsider if" — smaller, subdued, two-column ledger rows
 * pairing disqualifier text with an inline link to the alternative
 * machine. Borrows the hairline vocabulary already in stats-band and
 * the SSQ3 ledger.
 *
 * @package Standard
 * @var array{machine: array} $args
 *
 * @usage Single Machine Product (single-machine.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\MachineProductData\get_machine_product_link;

$machine = $args['machine'] ?? [];
$fit     = $machine['fit'] ?? null;

if (!$fit || (empty($fit['is_for']) && empty($fit['is_not_for']))) {
    return;
}

$is_for     = $fit['is_for'] ?? [];
$is_not_for = $fit['is_not_for'] ?? [];
?>

<section id="machine-fit" class="machine-fit bg-blue-50 py-12 md:py-16 lg:py-20" aria-labelledby="machine-fit-title" data-reveal="fade">
    <div class="container">

        <div class="section-header-left">
            <p class="section-eyebrow"><?php esc_html_e('Machine Fit', 'standard'); ?></p>
            <div class="section-divider"></div>
            <h2 id="machine-fit-title" class="section-title">
                <?php esc_html_e('Is this the right machine for the work?', 'standard'); ?>
            </h2>
        </div>

        <div class="machine-fit__body">

            <?php if (!empty($is_for)) : ?>
                <div class="machine-fit__block machine-fit__block--for">
                    <p class="machine-fit__kicker">
                        <span aria-hidden="true" class="machine-fit__kicker-dot"></span>
                        <?php esc_html_e('Built for', 'standard'); ?>
                    </p>
                    <ol class="machine-fit__qualifiers" role="list">
                        <?php foreach ($is_for as $index => $item) : ?>
                            <li class="machine-fit__qualifier">
                                <span class="machine-fit__qualifier-index" aria-hidden="true">
                                    <?php echo esc_html(str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT)); ?>
                                </span>
                                <span class="machine-fit__qualifier-text"><?php echo esc_html($item); ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ol>
                </div>
            <?php endif; ?>

            <?php if (!empty($is_not_for)) : ?>
                <div class="machine-fit__block machine-fit__block--reconsider">
                    <p class="machine-fit__kicker machine-fit__kicker--muted">
                        <?php esc_html_e('Reconsider if', 'standard'); ?>
                    </p>
                    <dl class="machine-fit__ledger">
                        <?php foreach ($is_not_for as $item) :
                            $text        = is_array($item) ? ($item['text'] ?? '') : $item;
                            $machine_key = is_array($item) ? ($item['machine'] ?? null) : null;
                            $link        = $machine_key ? get_machine_product_link($machine_key) : null;
                            $row_class   = 'machine-fit__ledger-row' . ($link ? '' : ' machine-fit__ledger-row--solo');
                        ?>
                            <div class="<?php echo esc_attr($row_class); ?>">
                                <dt class="machine-fit__ledger-text"><?php echo esc_html($text); ?></dt>
                                <?php if ($link) : ?>
                                    <dd class="machine-fit__ledger-alt">
                                        <a href="<?php echo esc_url(\Standard\Url\internal($link['url'])); ?>" class="machine-fit__ledger-link">
                                            <?php echo esc_html($link['name']); ?>
                                            <span aria-hidden="true" class="machine-fit__ledger-arrow">&rarr;</span>
                                        </a>
                                    </dd>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </dl>
                </div>
            <?php endif; ?>

        </div>

    </div>
</section>
