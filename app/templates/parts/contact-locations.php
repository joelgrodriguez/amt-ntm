<?php
/**
 * Contact Locations Strip
 *
 * Three-column hairline grid of NTM facility locations. Each column has
 * a mono eyebrow, a facility name, an address (clickable → Apple Maps,
 * which falls back to web for non-Apple clients), and tel:-linked phone
 * numbers. Sits below the lead-form grid as the proof seam.
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$locations = \Standard\ContactData\get_locations();

if (empty($locations)) {
    return;
}
?>

<section class="bg-blue-50 border-t border-blue-200" aria-labelledby="contact-locations-title">
    <div class="container">
        <header class="section-header-left max-w-3xl pt-12 lg:pt-16">
            <p class="section-eyebrow"><?php esc_html_e('Locations', 'standard'); ?></p>
            <h2 id="contact-locations-title" class="font-sans text-3xl md:text-4xl font-medium tracking-tight text-blue-900 m-0">
                <?php esc_html_e('Where NTM lives.', 'standard'); ?>
            </h2>
        </header>

        <ul class="grid grid-cols-1 md:grid-cols-3 list-none m-0 p-0 mt-10 lg:mt-12 pb-12 lg:pb-16 md:divide-x md:divide-blue-200 divide-y md:divide-y-0 border-t border-blue-200">
            <?php foreach ($locations as $loc) : ?>
                <li class="m-0 p-0">
                    <div class="grid gap-3 px-6 py-8 md:px-8 md:py-10 h-full">
                        <p class="section-eyebrow m-0">
                            <?php echo esc_html($loc['eyebrow']); ?>
                        </p>
                        <h3 class="font-sans text-lg md:text-xl font-medium tracking-tight text-blue-900 m-0 leading-snug">
                            <?php echo esc_html($loc['name']); ?>
                        </h3>

                        <a
                            href="<?php echo esc_url(\Standard\ContactData\map_url($loc['map_query'])); ?>"
                            target="_blank"
                            rel="noreferrer noopener"
                            class="not-prose inline-flex items-start gap-2 text-blue-700 hover:text-blue-500 no-underline font-sans text-base leading-relaxed group"
                        >
                            <span class="block">
                                <?php echo wp_kses($loc['address_html'], ['br' => []]); ?>
                            </span>
                            <?php icon('external-link', ['class' => 'w-3.5 h-3.5 shrink-0 mt-1.5 text-blue-400 group-hover:text-blue-500 transition-colors']); ?>
                        </a>

                        <ul class="grid gap-1.5 list-none m-0 p-0 mt-1">
                            <?php foreach ($loc['phones'] as $phone) : ?>
                                <li class="m-0 p-0 font-mono text-sm">
                                    <span class="text-blue-400 uppercase tracking-widest text-xs"><?php echo esc_html($phone['label']); ?></span>
                                    <a
                                        href="tel:<?php echo esc_attr($phone['tel']); ?>"
                                        class="ml-1 text-blue-700 hover:text-blue-500 no-underline"
                                    >
                                        <?php echo esc_html($phone['display']); ?>
                                    </a>
                                    <?php if (!empty($phone['note'])) : ?>
                                        <span class="block text-blue-400 text-xs mt-0.5"><?php echo esc_html($phone['note']); ?></span>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                            <?php if (!empty($loc['fax'])) : ?>
                                <li class="m-0 p-0 font-mono text-sm">
                                    <span class="text-blue-400 uppercase tracking-widest text-xs"><?php esc_html_e('Fax', 'standard'); ?></span>
                                    <span class="ml-1 text-blue-700"><?php echo esc_html($loc['fax']); ?></span>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>
