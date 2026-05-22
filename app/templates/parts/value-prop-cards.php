<?php
/**
 * Value Proposition — Shared Template Part
 *
 * Numbered editorial list highlighting key benefits. Mono numerals,
 * hairline dividers, three-column md+ grid (numeral / title / body).
 * Matches the differentiators section on /machines so the pillar
 * reads with a single voice across parent and child pages.
 *
 * The legacy `value-prop-cards` name is preserved to keep call sites
 * stable; the rendering is no longer a three-card grid.
 *
 * @package Standard
 *
 * @param array  $content    {eyebrow, title}
 * @param array  $cards      Array of {title, text}. Legacy `icon` key
 *                           is accepted and ignored.
 * @param string $section_id ID used for aria-labelledby.
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$content    = $args['content'] ?? [];
$cards      = $args['cards'] ?? [];
$section_id = $args['section_id'] ?? 'value-prop';
?>

<section class="section" aria-labelledby="<?php echo esc_attr($section_id); ?>-title">
    <div class="container section-content">

        <div class="section-header-left">
            <p class="section-eyebrow">
                <?php echo esc_html($content['eyebrow']); ?>
            </p>
            <div class="section-divider"></div>
            <h2 id="<?php echo esc_attr($section_id); ?>-title" class="section-title">
                <?php echo esc_html($content['title']); ?>
            </h2>
        </div>

        <ol class="grid border-t border-blue-200" role="list">
            <?php foreach ($cards as $idx => $card) : ?>
                <li class="grid gap-4 py-8 border-b border-blue-200 md:grid-cols-[auto_1fr_2fr] md:items-baseline md:gap-10 lg:gap-16">
                    <span class="font-mono text-sm font-medium text-blue-500 uppercase tracking-wider" aria-hidden="true">
                        <?php echo esc_html(sprintf('%02d', $idx + 1)); ?>
                    </span>
                    <h3 class="text-xl font-medium text-blue-900 md:text-2xl">
                        <?php echo esc_html($card['title']); ?>
                    </h3>
                    <p class="text-blue-600 max-w-prose">
                        <?php echo esc_html($card['text']); ?>
                    </p>
                </li>
            <?php endforeach; ?>
        </ol>

    </div>
</section>
