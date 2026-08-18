<?php
/**
 * METALCON 2026 — hero.
 *
 * @package Standard
 * @usage page-metalcon-2026.php
 */

declare(strict_types=1);

namespace Standard;

if (!defined('ABSPATH')) {
    exit;
}

$config = isset($args['config']) && is_array($args['config']) ? $args['config'] : [];

if (!isset($config['booth_number'], $config['demo_length'], $config['demo_format'], $config['hero_image_url'])) {
    return;
}

$booth_number  = (string) $config['booth_number'];
$demo_length   = (string) $config['demo_length'];
$demo_format   = (string) $config['demo_format'];
$hero_image_url = (string) $config['hero_image_url'];
?>

<section class="relative isolate overflow-hidden bg-blue-900 text-white" aria-labelledby="metalcon-hero-title">
    <img
        src="<?php echo esc_url($hero_image_url); ?>"
        alt=""
        class="absolute inset-0 h-full w-full object-cover"
        loading="eager"
        fetchpriority="high"
        decoding="async"
        aria-hidden="true"
    >
    <div class="absolute inset-0 bg-gradient-to-r from-blue-900/95 via-blue-900/80 to-blue-900/30" aria-hidden="true"></div>

    <div class="relative container flex min-h-[580px] items-center py-16 md:min-h-[640px] md:py-20 lg:min-h-[700px] lg:py-24">
        <div class="grid max-w-2xl content-start gap-6 lg:gap-8">
            <p class="font-mono text-xs font-medium uppercase tracking-widest text-blue-300">
                <?php
                printf(
                    /* translators: %s: METALCON booth number placeholder or confirmed number. */
                    esc_html__('METALCON 2026 · Booth #%s · October 7-9, 2026 · Orlando', 'standard'),
                    esc_html($booth_number)
                );
                ?>
            </p>

            <h1 id="metalcon-hero-title" class="text-balance text-4xl font-semibold leading-tight tracking-tight text-white md:text-5xl lg:text-6xl">
                <?php esc_html_e('An exclusive first look at the SSM siding machine.', 'standard'); ?>
            </h1>

            <p class="max-w-xl text-lg leading-relaxed text-blue-200 lg:text-xl">
                <?php esc_html_e('Sign up to see it run at the open of METALCON, Wednesday October 7.', 'standard'); ?>
            </p>

            <div class="flex flex-col gap-4 sm:flex-row">
                <a href="#metalcon-meeting-form" class="btn btn-primary w-full whitespace-nowrap sm:w-auto">
                    <?php esc_html_e('Save my spot', 'standard'); ?>
                    <?php icon('arrow-down', ['class' => 'h-5 w-5']); ?>
                </a>
                <a
                    href="https://registration.experientevent.com/ShowMTC261"
                    class="btn btn-outline-light w-full whitespace-nowrap sm:w-auto"
                    target="_blank"
                    rel="noopener noreferrer"
                >
                    <?php esc_html_e('Free show pass', 'standard'); ?>
                    <?php icon('external-link', ['class' => 'h-5 w-5']); ?>
                </a>
            </div>

            <p class="max-w-xl text-sm leading-relaxed text-blue-300">
                <?php esc_html_e('We confirm your spot by phone or email. No calendar roulette.', 'standard'); ?>
            </p>
        </div>
    </div>
</section>
