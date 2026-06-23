<?php
/**
 * Safety Page — Safety Systems Grid
 *
 * The factual operator-protection equipment NTM ships, collected in one place.
 * Data comes from get_safety_systems() — change the copy there, not here. Each
 * item states what the equipment is/does, never an outcome claim (legal gate).
 *
 * Mirrors the ironclad-support.php strip: mono index, mono label, sans title,
 * sans body, hairline grid. Existing component classes only, no new CSS.
 *
 * @package Standard
 * @usage page-safety.php
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\MachinesData\get_safety_systems;

$systems = function_exists('Standard\\MachinesData\\get_safety_systems')
    ? get_safety_systems()
    : [];

if (empty($systems)) {
    return;
}

$content = [
    'eyebrow'  => __('Built-in safety systems', 'standard'),
    'title'    => __('What is on the machine', 'standard'),
    'subtitle' => __('The protective equipment that ships as part of an NTM machine. Each is a system on the machine, not an add-on.', 'standard'),
];
?>

<section id="safety-systems" class="section bg-white border-y border-blue-200 scroll-mt-24" aria-labelledby="safety-systems-title">
    <div class="container section-content">

        <div class="section-header-left max-w-2xl">
            <p class="section-eyebrow">
                <?php echo esc_html($content['eyebrow']); ?>
            </p>
            <div class="section-divider"></div>
            <h2 id="safety-systems-title" class="section-title">
                <?php echo esc_html($content['title']); ?>
            </h2>
            <p class="section-subtitle text-pretty">
                <?php echo esc_html($content['subtitle']); ?>
            </p>
        </div>

        <ol class="grid gap-px bg-blue-200 border border-blue-200 sm:grid-cols-2 lg:grid-cols-3" role="list">
            <?php foreach ($systems as $idx => $system) : ?>
                <li class="grid content-start gap-3 bg-white p-6 lg:p-7">
                    <div class="flex items-center gap-3 font-mono text-xs uppercase tracking-wider text-blue-500">
                        <span><?php echo esc_html(sprintf('%02d', $idx + 1)); ?></span>
                        <span class="w-8 h-px bg-blue-300" aria-hidden="true"></span>
                    </div>
                    <p class="font-mono text-[11px] font-medium uppercase tracking-widest text-blue-500">
                        <?php echo esc_html($system['label']); ?>
                    </p>
                    <h3 class="text-lg font-medium text-blue-900 lg:text-xl">
                        <?php echo esc_html($system['title']); ?>
                    </h3>
                    <p class="text-base text-blue-600 leading-relaxed">
                        <?php echo esc_html($system['body']); ?>
                    </p>
                </li>
            <?php endforeach; ?>
        </ol>

    </div>
</section>
