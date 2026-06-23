<?php
/**
 * Portability DNA — Shared Template Part
 *
 * NTM's on-demand manufacturing story as a reusable strip: a left-aligned
 * header band over a four-up proof grid (Unlimited Length · No Seams · Fewer
 * Leak Points · Controlled Waste). Each point carries a mono index, a mono
 * promise label, a sans title, and one supporting sentence — the engineered
 * spec-sheet voice the machine pages already speak.
 *
 * This is the contractor-benefit frame of the "portability is our DNA"
 * narrative. The About page tells the same story as origin/belief prose
 * (see templates/parts/about/origin.php) and deliberately does NOT render
 * this strip, so the two surfaces share the DNA without repeating sentences.
 *
 * Built to drop into the homepage SELL block. (The category roll-ups already
 * carry a "Why Portable Rollforming" value-prop, so the strip stays off them
 * to avoid duplicating that argument; it's reusable wherever that copy is
 * absent.) The four points come from get_portability_pillars() — change the
 * copy there, not here.
 *
 * @package Standard
 *
 * @param array  $content    Optional {eyebrow, title, subtitle}. Sensible
 *                           contractor-benefit defaults; override per page.
 * @param string $section_id ID used for aria-labelledby + the scroll anchor.
 * @param string $background Optional section background class. Defaults bg-white.
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\MachinesData\get_portability_pillars;

$pillars = function_exists('Standard\\MachinesData\\get_portability_pillars')
    ? get_portability_pillars()
    : [];

if (empty($pillars)) {
    return;
}

$content    = $args['content'] ?? [];
$section_id = $args['section_id'] ?? 'portability-dna';
$background = $args['background'] ?? 'bg-white';

$eyebrow  = $content['eyebrow']  ?? __('Portability is in our DNA', 'standard');
$title    = $content['title']    ?? __('On-demand metal, made where the job is.', 'standard');
$subtitle = $content['subtitle'] ?? __('An NTM machine puts the rollformer on the jobsite, so contractors make the exact panels and gutters a job needs, on the spot. That is what portable rollforming changes.', 'standard');
?>

<section id="<?php echo esc_attr($section_id); ?>" class="section <?php echo esc_attr($background); ?> scroll-mt-24" aria-labelledby="<?php echo esc_attr($section_id); ?>-title">
    <div class="container section-content">

        <div class="section-header-left max-w-2xl">
            <p class="section-eyebrow">
                <?php echo esc_html($eyebrow); ?>
            </p>
            <div class="section-divider"></div>
            <h2 id="<?php echo esc_attr($section_id); ?>-title" class="section-title">
                <?php echo esc_html($title); ?>
            </h2>
            <p class="section-subtitle text-pretty">
                <?php echo esc_html($subtitle); ?>
            </p>
        </div>

        <ol class="grid gap-px bg-blue-200 border border-blue-200 sm:grid-cols-2 lg:grid-cols-4" role="list">
            <?php foreach ($pillars as $idx => $pillar) : ?>
                <li class="grid content-start gap-3 bg-white p-6 lg:p-7">
                    <div class="flex items-center gap-3 font-mono text-xs uppercase tracking-wider text-blue-500">
                        <span><?php echo esc_html(sprintf('%02d', $idx + 1)); ?></span>
                        <span class="w-8 h-px bg-blue-300" aria-hidden="true"></span>
                    </div>
                    <p class="font-mono text-[11px] font-medium uppercase tracking-widest text-blue-500">
                        <?php echo esc_html($pillar['label']); ?>
                    </p>
                    <h3 class="text-lg font-medium text-blue-900 lg:text-xl">
                        <?php echo esc_html($pillar['title']); ?>
                    </h3>
                    <p class="text-base text-blue-600 leading-relaxed">
                        <?php echo esc_html($pillar['body']); ?>
                    </p>
                </li>
            <?php endforeach; ?>
        </ol>

    </div>
</section>
