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

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\MachinesData\get_journey_stats;

$content = [
    'eyebrow' => __('Our Story', 'standard'),
    'title'   => __('The Journey Into Portable Rollforming', 'standard'),
    'text'    => __('What started in 1991 as a mission to bring panel fabrication to the jobsite has grown into the industry standard. Today, NTM machines are trusted by contractors in over 40 countries across all seven continents — from residential roofers to large-scale commercial operations.', 'standard'),
];

$stats = get_journey_stats();
?>

<section class="section bg-blue-900 pattern-square-grid pattern-square-grid--dark" aria-labelledby="journey-title">
    <div class="pattern-square-grid__overlay pattern-square-grid__overlay--top-left"></div>
    <div class="pattern-square-grid__overlay pattern-square-grid__overlay--bottom-right"></div>

    <div class="container section-content relative z-10">

        <div class="section-header">
            <p class="text-sm font-medium uppercase tracking-wider text-red">
                <?php echo esc_html($content['eyebrow']); ?>
            </p>
            <div class="section-divider-center"></div>
            <h2 id="journey-title" class="text-3xl font-medium text-white md:text-4xl lg:text-5xl">
                <?php echo esc_html($content['title']); ?>
            </h2>
            <p class="text-lg text-blue-400 max-w-3xl mx-auto leading-relaxed">
                <?php echo esc_html($content['text']); ?>
            </p>
        </div>

        <div class="grid grid-cols-2 gap-6 sm:gap-8 md:grid-cols-4 max-w-4xl mx-auto text-center">
            <?php foreach ($stats as $stat) : ?>
                <div class="grid gap-2">
                    <span class="text-3xl font-medium text-white sm:text-4xl lg:text-5xl">
                        <?php echo esc_html($stat['stat']); ?>
                    </span>
                    <span class="text-sm text-blue-400 uppercase tracking-wider">
                        <?php echo esc_html($stat['label']); ?>
                    </span>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>
