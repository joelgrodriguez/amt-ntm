<?php
/**
 * METALCON 2026 — presentation sign-up (HubSpot form).
 *
 * @package Standard
 * @usage page-metalcon-2026.php
 */

declare(strict_types=1);

namespace Standard;

if (!defined('ABSPATH')) {
    exit;
}
?>

<section id="metalcon-meeting-form" class="scroll-mt-24 bg-blue-50 section" aria-labelledby="metalcon-form-title">
    <div class="container">
        <div class="grid gap-12 lg:grid-cols-[minmax(0,0.8fr)_minmax(420px,1.2fr)] lg:gap-16">
            <header class="section-header-left max-w-xl content-start">
                <p class="section-eyebrow"><?php esc_html_e('Special presentation', 'standard'); ?></p>
                <div class="section-divider"></div>
                <h2 id="metalcon-form-title" class="section-title">
                    <?php esc_html_e('Discover the future of portable rollforming', 'standard'); ?>
                </h2>
                <p class="section-subtitle">
                    <?php esc_html_e('Sign up for the special presentation. Pick a date and time, tell us who you are, and we will save you a spot.', 'standard'); ?>
                </p>
                <p class="text-sm leading-relaxed text-blue-600">
                    <?php esc_html_e('We confirm your spot by phone or email.', 'standard'); ?>
                </p>
            </header>

            <div class="border-t-4 border-blue-500 bg-white p-6 md:p-8">
                <?php
                echo HubSpot\render_form([
                    'form_id'   => HubSpot\METALCON_2026_FORM_ID,
                    'target_id' => 'metalcon-signup-form',
                    'class'     => 'min-h-[36rem]',
                ]);
                ?>
            </div>
        </div>
    </div>
</section>
