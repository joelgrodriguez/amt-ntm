<?php
/**
 * 5-Pillar "Ironclad Support" — Shared Template Part
 *
 * NTM's post-sale promise as a reusable strip: a left-aligned header
 * band over a five-up spec grid (Quality · Parts · Service · Training ·
 * Warranty). Each pillar carries a mono index, a mono promise label,
 * a sans title, and one supporting sentence — the engineered spec-sheet
 * voice the machine pages already speak.
 *
 * Built to drop into any section (machine pillar pages, the simple
 * machine template, and the category roll-ups) so the support story
 * reads with one voice everywhere it appears. The five pillars come from
 * get_ironclad_pillars() — change the copy there, not here.
 *
 * @package Standard
 *
 * @param array  $content    Optional {eyebrow, title, subtitle, kicker}.
 *                           Sensible defaults provided; override per page.
 * @param string $section_id ID used for aria-labelledby + the scroll anchor.
 * @param string $background  Optional section background class. Defaults bg-blue-50.
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\MachinesData\get_ironclad_pillars;

$pillars = function_exists('Standard\\MachinesData\\get_ironclad_pillars')
    ? get_ironclad_pillars()
    : [];

if (empty($pillars)) {
    return;
}

$content    = $args['content'] ?? [];
$section_id = $args['section_id'] ?? 'ironclad-support';
$background = $args['background'] ?? 'bg-blue-50';

$eyebrow  = $content['eyebrow']  ?? __('Ironclad Support', 'standard');
$title    = $content['title']    ?? __('Five pillars keep you running.', 'standard');
$subtitle = $content['subtitle'] ?? __('Downtime kills margins. NTM\'s real advantage is the system behind the machine — quality, parts, service, training, and warranty working together to keep your operation moving.', 'standard');
// Closing line from the video: "You're buying more than a machine."
$kicker   = $content['kicker']   ?? __('You\'re buying more than a machine. You\'re gaining a partner.', 'standard');
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

        <ol class="grid gap-px bg-blue-200 border border-blue-200 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5" role="list">
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

        <?php if (!empty($kicker)) : ?>
            <p class="font-mono text-sm text-blue-500 max-w-2xl">
                <?php echo esc_html($kicker); ?>
            </p>
        <?php endif; ?>

    </div>
</section>
