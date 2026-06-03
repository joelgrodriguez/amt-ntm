<?php
/**
 * Choose Your Machine — Fit Ledger (reusable)
 *
 * One family's machines as a ranked ledger, not a card grid. Each row is a
 * single full-row link: model name (mono), a one-line "best when" qualifier,
 * two real spec chips, and the starting price. Rows are fenced by hairlines
 * and ordered flagship → entry, so a buyer self-selects by where their work
 * and budget land. This is the full-page sibling of the per-product
 * "Built for / Reconsider if" block (woo/product/parts/machine-fit.php).
 *
 * Whole-row links keep the tap target generous (the whole row, well over
 * 44px) for the phone-in-a-truck reader. Rows whose product is absent
 * locally render without the link wrapper, so nothing 404s.
 *
 * Under the ledger sit de-emphasised secondary links (a guided quiz, the
 * spec comparison) so this page complements those lanes instead of
 * competing with them.
 *
 * @package Standard
 *
 * @param string $section_id  Anchor id (e.g. 'roof-ledger').
 * @param string $eyebrow     Mono kicker.
 * @param string $title       Section title.
 * @param string $subtitle    Intro line.
 * @param array  $rows        Hydrated rows from choose/data.php.
 * @param array  $secondary   {label,url}[] de-emphasised follow-on links.
 * @param string $surface     Optional section bg utility (e.g. 'bg-blue-50').
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$section_id = $args['section_id'] ?? 'fit-ledger';
$eyebrow    = $args['eyebrow'] ?? '';
$title      = $args['title'] ?? '';
$subtitle   = $args['subtitle'] ?? '';
$rows       = $args['rows'] ?? [];
$secondary  = $args['secondary'] ?? [];
$surface    = $args['surface'] ?? '';

if (empty($rows)) {
    return;
}

// On a tinted section the ledger rows hover to white for contrast; on the
// default white section they hover to blue-50. The row surface comes from
// the section bg, so the hover tint is the inverse.
$row_hover  = $surface !== '' ? 'hover:bg-white focus-visible:bg-white' : 'hover:bg-blue-50 focus-visible:bg-blue-50';
?>

<section id="<?php echo esc_attr($section_id); ?>" class="section scroll-mt-24 <?php echo esc_attr($surface); ?>" aria-labelledby="<?php echo esc_attr($section_id); ?>-title">
    <div class="container section-content">

        <div class="section-header-left max-w-2xl">
            <?php if ($eyebrow !== '') : ?>
                <p class="section-eyebrow"><?php echo esc_html($eyebrow); ?></p>
                <div class="section-divider"></div>
            <?php endif; ?>
            <h2 id="<?php echo esc_attr($section_id); ?>-title" class="section-title text-balance">
                <?php echo esc_html($title); ?>
            </h2>
            <?php if ($subtitle !== '') : ?>
                <p class="section-subtitle text-pretty"><?php echo esc_html($subtitle); ?></p>
            <?php endif; ?>
        </div>

        <div class="border border-blue-200">
            <?php foreach ($rows as $i => $row) :
                $has_link  = !empty($row['url']);
                $tag       = $has_link ? 'a' : 'div';
                // Clean accessible name for the whole-row link, so screen
                // readers announce "View the SSQ3 MultiPro" rather than the
                // full row of copy, chips, and price concatenated.
                $aria      = $has_link
                    ? ' aria-label="' . esc_attr(sprintf(
                        /* translators: %s: machine model name. */
                        __('View the %s', 'standard'),
                        wp_strip_all_tags($row['name'])
                    )) . '"'
                    : '';
                $href      = $has_link ? ' href="' . esc_url($row['url']) . '"' . $aria : '';
                // Every row after the first carries the top hairline, so the
                // fence reads as one continuous ledger, never doubled.
                $rule      = $i === 0 ? '' : 'border-t border-blue-200';
                // Whole-row link: tint + ink shift on hover/focus, nudge arrow.
                $interact  = $has_link
                    ? 'group transition-colors duration-200 ' . $row_hover . ' focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-blue-500'
                    : '';
            ?>
                <<?php echo $tag; ?><?php echo $href; ?> class="block <?php echo esc_attr(trim($rule . ' ' . $interact)); ?>">
                    <div class="grid items-baseline gap-x-8 gap-y-4 p-6 md:p-8 lg:grid-cols-12 lg:gap-y-2">

                        <div class="lg:col-span-4">
                            <h3 class="font-mono text-lg font-medium text-blue-900 <?php echo $has_link ? 'group-hover:text-blue-500' : ''; ?>">
                                <?php echo esc_html($row['name']); ?>
                            </h3>
                        </div>

                        <div class="lg:col-span-5">
                            <p class="text-base text-blue-600 text-pretty">
                                <span class="font-mono text-[10px] uppercase tracking-mono-meta text-blue-600">
                                    <?php esc_html_e('Best when', 'standard'); ?>
                                </span>
                                <span class="mt-1 block"><?php echo esc_html($row['best_when']); ?></span>
                            </p>

                            <?php if (!empty($row['chips'])) : ?>
                                <dl class="mt-4 flex flex-wrap gap-x-6 gap-y-2">
                                    <?php foreach ($row['chips'] as $chip) : ?>
                                        <div class="flex items-baseline gap-2">
                                            <dt class="font-mono text-[10px] uppercase tracking-mono-meta text-blue-600">
                                                <?php echo esc_html($chip['label']); ?><span class="sr-only">:</span>
                                            </dt>
                                            <dd class="font-mono text-sm text-blue-700"><?php echo esc_html($chip['value']); ?></dd>
                                        </div>
                                    <?php endforeach; ?>
                                </dl>
                            <?php endif; ?>
                        </div>

                        <div class="flex items-center justify-between gap-4 lg:col-span-3 lg:justify-end">
                            <?php if (!empty($row['price'])) : ?>
                                <div class="grid gap-0.5 lg:text-right">
                                    <span class="font-mono text-[10px] uppercase tracking-mono-meta text-blue-600">
                                        <?php esc_html_e('From', 'standard'); ?>
                                    </span>
                                    <span class="font-mono text-base text-blue-900"><?php echo esc_html($row['price']); ?></span>
                                </div>
                            <?php endif; ?>
                            <?php if ($has_link) : ?>
                                <span class="shrink-0 text-blue-500 transition-transform duration-200 group-hover:translate-x-1" aria-hidden="true">
                                    <?php icon('arrow-right', ['class' => 'w-5 h-5']); ?>
                                </span>
                            <?php endif; ?>
                        </div>

                    </div>
                </<?php echo $tag; ?>>
            <?php endforeach; ?>
        </div>

        <?php if (!empty($secondary)) : ?>
            <div class="flex flex-wrap items-center gap-x-8 gap-y-3">
                <p class="font-mono text-[10px] uppercase tracking-mono-meta text-blue-600">
                    <?php esc_html_e('Not sure yet?', 'standard'); ?>
                </p>
                <?php foreach ($secondary as $link) : ?>
                    <a
                        href="<?php echo esc_url(\Standard\Url\internal($link['url'])); ?>"
                        class="inline-flex min-h-11 items-center gap-1.5 font-mono text-xs uppercase tracking-mono-label text-blue-500 transition-colors hover:text-blue-700"
                    >
                        <?php echo esc_html($link['label']); ?>
                        <?php icon('arrow-right', ['class' => 'w-3.5 h-3.5']); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</section>
