<?php
/**
 * Machines Page — The NTM Journey
 *
 * Social proof section with company history stats and
 * a brief narrative about NTM's rollforming legacy.
 * Uses the dark bg style matching front-page social proof.
 *
 * @package Standard
 *
 * @usage Machines Page (page-machines.php)
 */

declare(strict_types=1);

use function Standard\MachinesData\get_journey_stats;

$content = [
    'eyebrow' => __('Our Story', 'standard'),
    'title'   => __('The Journey Into Portable Rollforming', 'standard'),
    'text'    => __('What started in 1991 as a mission to bring panel fabrication to the jobsite has grown into the industry standard. Today, NTM machines are trusted by contractors in over 40 countries across all seven continents — from residential roofers to large-scale commercial operations.', 'standard'),
];

$stats = get_journey_stats();
?>

<section class="section bg-slate-900 pattern-square-grid pattern-square-grid--dark" aria-labelledby="journey-title">
    <div class="pattern-square-grid__overlay pattern-square-grid__overlay--top-left"></div>
    <div class="pattern-square-grid__overlay pattern-square-grid__overlay--bottom-right"></div>

    <div class="container section-content relative z-10">

        <div class="section-header">
            <p class="text-sm font-semibold uppercase tracking-wider text-secondary">
                <?php echo esc_html($content['eyebrow']); ?>
            </p>
            <div class="section-divider-center"></div>
            <h2 id="journey-title" class="text-3xl font-bold text-white md:text-4xl lg:text-5xl">
                <?php echo esc_html($content['title']); ?>
            </h2>
            <p class="text-lg text-slate-400 max-w-3xl mx-auto leading-relaxed">
                <?php echo esc_html($content['text']); ?>
            </p>
        </div>

        <div class="grid grid-cols-2 gap-6 sm:gap-8 md:grid-cols-4 max-w-4xl mx-auto text-center">
            <?php foreach ($stats as $stat) : ?>
                <div class="grid gap-2">
                    <span class="text-3xl font-bold text-white sm:text-4xl lg:text-5xl">
                        <?php echo esc_html($stat['stat']); ?>
                    </span>
                    <span class="text-sm text-slate-400 uppercase tracking-wider">
                        <?php echo esc_html($stat['label']); ?>
                    </span>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>
