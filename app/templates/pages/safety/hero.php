<?php
/**
 * Safety Page — Hero
 *
 * Factual identity statement, no superlative. The legal gate (2026-06-17)
 * keeps this to a stance, not a claim: how NTM approaches safety, not a
 * ranking. NO "safest machine on the market." Copy is mirrored into
 * docs/legal/safety-copy-review.md for counsel.
 *
 * @package Standard
 * @usage page-safety.php
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$content = [
    'eyebrow'  => __('Safety', 'standard'),
    'title'    => __('Operator protection, engineered into the machine.', 'standard'),
    'subtitle' => __('NTM machines ship with guard interlocks, a power-interruption safety circuit, and on-controller operator alerts as part of the design. Paired with one-on-one operator training, that is how a crew runs the machine day in and day out.', 'standard'),
];
?>

<section class="relative overflow-hidden bg-blue-900 text-white pattern-dot-grid pattern-dot-grid--dark" aria-labelledby="safety-hero-title">
    <div class="container py-16 lg:py-20 xl:py-24">
        <div class="grid gap-6 lg:gap-8 max-w-3xl">

            <p class="font-mono text-xs uppercase tracking-mono-label text-blue-300">
                <?php echo esc_html($content['eyebrow']); ?>
            </p>

            <h1 id="safety-hero-title" class="font-sans font-medium tracking-tight text-white text-4xl lg:text-5xl text-balance">
                <?php echo esc_html($content['title']); ?>
            </h1>

            <p class="text-lg text-blue-200 max-w-2xl lg:text-xl text-pretty">
                <?php echo esc_html($content['subtitle']); ?>
            </p>

        </div>
    </div>
</section>
