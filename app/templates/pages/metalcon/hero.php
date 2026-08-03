<?php
/**
 * METALCON 2026 — hero and meeting-request form.
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

if (!isset($config['booth_number'], $config['demo_length'], $config['demo_format'])) {
    return;
}

$booth_number = (string) $config['booth_number'];
$demo_length  = (string) $config['demo_length'];
$demo_format  = (string) $config['demo_format'];
?>

<section class="relative overflow-hidden border-b border-blue-800 bg-blue-900 text-white pattern-dot-grid pattern-dot-grid--dark" aria-labelledby="metalcon-hero-title">
    <div class="container py-16 md:py-20 lg:py-24">
        <div class="grid gap-12 lg:grid-cols-[minmax(0,1fr)_minmax(420px,560px)] lg:items-start lg:gap-16">

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
                    <?php esc_html_e('See the SSM Siding Machine run live — and run your own panel.', 'standard'); ?>
                </h1>

                <p class="max-w-xl text-lg leading-relaxed text-blue-200 lg:text-xl">
                    <?php
                    printf(
                        /* translators: 1: placeholder demo length, 2: placeholder demo format. */
                        esc_html__('Book a %1$s %2$s with our team. Skip the floor crowd.', 'standard'),
                        esc_html($demo_length),
                        esc_html($demo_format)
                    );
                    ?>
                </p>

                <div class="flex flex-col gap-4 sm:flex-row">
                    <a href="#metalcon-meeting-form" class="btn btn-primary w-full sm:w-60">
                        <?php esc_html_e('Request a booth meeting', 'standard'); ?>
                        <?php icon('arrow-down', ['class' => 'h-5 w-5']); ?>
                    </a>
                    <a
                        href="https://registration.experientevent.com/ShowMTC261"
                        class="btn btn-outline-light w-full sm:w-60"
                        target="_blank"
                        rel="noopener noreferrer"
                    >
                        <?php esc_html_e('Get a free show pass', 'standard'); ?>
                        <?php icon('external-link', ['class' => 'h-5 w-5']); ?>
                    </a>
                </div>

                <p class="max-w-xl text-sm leading-relaxed text-blue-300">
                    <?php esc_html_e('Sales will confirm your exact meeting time by phone or email. No calendar roulette.', 'standard'); ?>
                </p>
            </div>

            <aside
                id="metalcon-meeting-form"
                class="scroll-mt-24 border-t-4 border-blue-500 bg-white p-6 text-blue-900 md:p-8"
                aria-labelledby="metalcon-form-title"
            >
                <div class="grid gap-6">
                    <header class="grid gap-3">
                        <p class="section-eyebrow"><?php esc_html_e('Your private booth meeting', 'standard'); ?></p>
                        <h2 id="metalcon-form-title" class="text-2xl font-medium tracking-tight text-blue-900 md:text-3xl">
                            <?php esc_html_e('Request a time with the NTM team', 'standard'); ?>
                        </h2>
                        <p class="text-base leading-relaxed text-blue-600">
                            <?php esc_html_e('Tell us what you run today and which show day works best. We will follow up with an exact time.', 'standard'); ?>
                        </p>
                    </header>

                    <?php
                    echo HubSpot\render_form([
                        'form_id'   => HubSpot\METALCON_FORM_ID,
                        'target_id' => 'metalcon-2026-hubspot-form',
                        'class'     => 'metalcon-meeting-request-form',
                    ]);
                    ?>
                </div>
            </aside>

        </div>
    </div>
</section>
