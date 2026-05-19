<?php
/**
 * Contact Section Template Part
 *
 * Two-column layout with contact info/map on left and HubSpot form on right.
 * Light background with dot grid pattern.
 *
 * @package Standard
 *
 * @usage Front Page (front-page.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$content = [
    'title'      => __("Let's Talk", 'standard'),
    'text'       => __('Ready to take control of your business? Contact us today to discuss your equipment needs.', 'standard'),
    'form_title' => __('Send Us a Message', 'standard'),
    'form_text'  => __("Fill out the form below and we'll get back to you within one business day.", 'standard'),
];

$contact_info = [
    'address' => '16265 E. 33rd Dr. Suite 40, Aurora, Colorado 80011',
    'email'   => 'support@newtechmachinery.com',
    'phone'   => '303.294.0538',
    'map_url' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3066.8553948429734!2d-104.82490548462348!3d39.76307597944626!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x876c62956ef5f97b%3A0x8b6b5a3928336406!2s16265%20E%2033rd%20Dr%20%2340%2C%20Aurora%2C%20CO%2080011%2C%20USA!5e0!3m2!1sen!2sus!4v1634704182859!5m2!1sen!2sus',
];

$directions_url = 'https://www.google.com/maps/dir//' . urlencode($contact_info['address']);
?>

<section id="contact" class="section bg-blue-50 overflow-hidden" aria-labelledby="contact-title">
    <div class="container section-content">

        <!-- Section Header -->
        <div class="section-header">
            <h2 id="contact-title" class="section-title">
                <?php echo esc_html($content['title']); ?>
            </h2>
            <p class="section-subtitle-centered">
                <?php echo esc_html($content['text']); ?>
            </p>
        </div>

        <div class="grid gap-12 lg:grid-cols-2 lg:gap-16">

            <!-- Left Column: Contact Info + Map -->
            <div class="grid gap-6 content-start">

                <!-- Contact Info -->
                <div class="grid gap-4">
                    <a
                        href="<?php echo esc_url($directions_url); ?>"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="group flex items-center gap-3 text-blue-700 hover:text-blue-500 transition-colors"
                    >
                        <?php icon('external-link', ['class' => 'w-5 h-5 text-blue-500 shrink-0']); ?>
                        <span><?php echo esc_html($contact_info['address']); ?></span>
                    </a>

                    <a
                        href="mailto:<?php echo esc_attr($contact_info['email']); ?>"
                        class="group flex items-center gap-3 text-blue-700 hover:text-blue-500 transition-colors"
                    >
                        <?php icon('mail', ['class' => 'w-5 h-5 text-blue-500 shrink-0']); ?>
                        <span><?php echo esc_html($contact_info['email']); ?></span>
                    </a>

                    <a
                        href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $contact_info['phone'])); ?>"
                        class="group flex items-center gap-3 text-blue-700 hover:text-blue-500 transition-colors"
                    >
                        <?php icon('phone', ['class' => 'w-5 h-5 text-blue-500 shrink-0']); ?>
                        <span><?php echo esc_html($contact_info['phone']); ?></span>
                    </a>
                </div>

                <!-- Map (click-to-load: hydrated to an iframe by ContactLazy.js) -->
                <button
                    type="button"
                    data-map-placeholder
                    data-map-src="<?php echo esc_url($contact_info['map_url']); ?>"
                    data-map-title="<?php echo esc_attr__('NTM Location Map', 'standard'); ?>"
                    class="group relative aspect-video w-full bg-blue-100 border border-blue-200 overflow-hidden cursor-pointer focus-visible:outline-2 focus-visible:outline-blue-500 focus-visible:outline-offset-2"
                    aria-label="<?php esc_attr_e('Load interactive map of NTM location', 'standard'); ?>"
                >
                    <!-- Static map-shaped placeholder. Grid pattern reads as a blueprint. -->
                    <div
                        class="absolute inset-0 bg-blue-50 transition-colors duration-200 group-hover:bg-blue-100"
                        style="background-image: linear-gradient(to right, var(--color-blue-200) 1px, transparent 1px), linear-gradient(to bottom, var(--color-blue-200) 1px, transparent 1px); background-size: 32px 32px;"
                        aria-hidden="true"
                    ></div>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="flex items-center gap-3 bg-white border border-blue-200 px-4 py-3 font-mono uppercase tracking-wider text-xs text-blue-700 group-hover:border-blue-500 group-hover:text-blue-500 transition-colors duration-200">
                            <?php icon('external-link', ['class' => 'w-4 h-4', 'aria-hidden' => 'true']); ?>
                            <span><?php esc_html_e('Load map', 'standard'); ?></span>
                        </div>
                    </div>
                </button>

            </div>

            <!-- Right Column: Form -->
            <div class="bg-blue-50 border border-blue-200 p-8 lg:p-10">
                <h3 class="text-xl font-medium text-blue-700 mb-2">
                    <?php echo esc_html($content['form_title']); ?>
                </h3>
                <p class="text-blue-600 mb-6">
                    <?php echo esc_html($content['form_text']); ?>
                </p>

                <!-- HubSpot form hydrated by ContactLazy.js when the section is near the viewport. -->
                <div id="contact-form" class="min-h-[24rem]">
                    <noscript>
                        <p class="font-sans text-blue-600 text-sm">
                            <?php esc_html_e('Enable JavaScript to load the contact form, or email us directly.', 'standard'); ?>
                        </p>
                    </noscript>
                </div>
            </div>

        </div>

    </div>
</section>
