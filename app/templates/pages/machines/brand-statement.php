<?php
/**
 * Machines Page — Brand Statement
 *
 * Centered brand value proposition text block with square grid pattern.
 *
 * @package Standard
 *
 * @usage Machines Page (page-machines.php)
 */

declare(strict_types=1);

$content = [
    'title' => __('Built for the Jobsite', 'standard'),
    'text'  => __('New Tech Machinery manufactures portable rollforming machines that let contractors fabricate metal panels on-site or in-plant — eliminating factory delays, transportation costs, and damage risks. From standing seam roofing to seamless gutters, NTM machines are trusted in 40+ countries across all seven continents.', 'standard'),
];
?>

<section class="section pattern-square-grid" aria-labelledby="brand-statement-title">
    <div class="pattern-square-grid__overlay pattern-square-grid__overlay--top-left"></div>
    <div class="pattern-square-grid__overlay pattern-square-grid__overlay--bottom-right"></div>
    <div class="container grid gap-6 text-center max-w-3xl mx-auto relative z-10">
        <div class="section-divider-center"></div>
        <h2 id="brand-statement-title" class="text-3xl font-bold text-slate-900 md:text-4xl lg:text-5xl">
            <?php echo esc_html($content['title']); ?>
        </h2>
        <p class="text-lg text-slate-600 leading-relaxed lg:text-xl">
            <?php echo esc_html($content['text']); ?>
        </p>
    </div>
</section>
