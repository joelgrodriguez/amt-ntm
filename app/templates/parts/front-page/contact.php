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

$content = [
    'eyebrow'    => __('Get in Touch', 'standard'),
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

<section id="contact" class="section bg-slate-50 overflow-hidden" aria-labelledby="contact-title">
    <div class="container section-content">

        <!-- Section Header -->
        <div class="section-header">
            <p class="section-eyebrow">
                <?php echo esc_html($content['eyebrow']); ?>
            </p>
            <div class="section-divider-center"></div>
            <h2 id="contact-title" class="section-title">
                <?php echo esc_html($content['title']); ?>
            </h2>
            <p class="section-subtitle-centered">
                <?php echo esc_html($content['text']); ?>
            </p>
        </div>

        <div class="grid gap-12 lg:grid-cols-2 lg:gap-16">

            <!-- Left Column: Contact Info + Map -->
            <div class="space-y-6">

                <!-- Contact Info -->
                <div class="space-y-4">
                    <a
                        href="<?php echo esc_url($directions_url); ?>"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="group flex items-center gap-3 text-slate-700 hover:text-secondary transition-colors"
                    >
                        <?php icon('external-link', ['class' => 'w-5 h-5 text-secondary shrink-0']); ?>
                        <span><?php echo esc_html($contact_info['address']); ?></span>
                    </a>

                    <a
                        href="mailto:<?php echo esc_attr($contact_info['email']); ?>"
                        class="group flex items-center gap-3 text-slate-700 hover:text-secondary transition-colors"
                    >
                        <?php icon('mail', ['class' => 'w-5 h-5 text-secondary shrink-0']); ?>
                        <span><?php echo esc_html($contact_info['email']); ?></span>
                    </a>

                    <a
                        href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $contact_info['phone'])); ?>"
                        class="group flex items-center gap-3 text-slate-700 hover:text-secondary transition-colors"
                    >
                        <?php icon('phone', ['class' => 'w-5 h-5 text-secondary shrink-0']); ?>
                        <span><?php echo esc_html($contact_info['phone']); ?></span>
                    </a>
                </div>

                <!-- Map -->
                <div class="relative aspect-video bg-slate-100 border border-slate-200 overflow-hidden">
                    <iframe
                        src="<?php echo esc_url($contact_info['map_url']); ?>"
                        width="100%"
                        height="100%"
                        class="absolute inset-0"
                        style="border:0;"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        title="<?php echo esc_attr__('NTM Location Map', 'standard'); ?>"
                    ></iframe>
                </div>

            </div>

            <!-- Right Column: Form -->
            <div class="bg-slate-50 border border-slate-200 p-8 lg:p-10">
                <h3 class="text-xl font-bold text-slate-900 mb-2">
                    <?php echo esc_html($content['form_title']); ?>
                </h3>
                <p class="text-slate-600 mb-6">
                    <?php echo esc_html($content['form_text']); ?>
                </p>

                <div id="contact-form">
                    <script charset="utf-8" type="text/javascript" src="//js.hsforms.net/forms/embed/v2.js"></script>
                    <script>
                        hbspt.forms.create({
                            region: "na1",
                            portalId: "4478417",
                            formId: "8819d347-bf19-49e1-8e49-cd45dbd7235f"
                        });
                    </script>
                </div>
            </div>

        </div>

    </div>
</section>
