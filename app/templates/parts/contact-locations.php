<?php
/**
 * Contact Locations Strip
 *
 * Three-column hairline grid of NTM facility locations. Renders just the
 * grid block (no section wrapper). The caller controls placement and
 * surrounding chrome. Each cell carries a mono eyebrow, facility name,
 * optional provenance line, an Apple Maps address link, and any tel:
 * phone numbers attached to that location.
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

<div class="grid grid-cols-1 md:grid-cols-3 list-none m-0 p-0 border border-blue-200 md:divide-x md:divide-blue-200 divide-y md:divide-y-0">
    <?php foreach ($locations as $loc) : ?>
        <div class="grid gap-3 px-5 py-5 md:px-6 md:py-6 h-full bg-white">
            <p class="section-eyebrow m-0">
                <?php echo esc_html($loc['eyebrow']); ?>
            </p>
            <h3 class="font-sans text-base md:text-lg font-medium tracking-tight text-blue-900 m-0 leading-snug">
                <?php echo esc_html($loc['name']); ?>
            </h3>

            <?php if (!empty($loc['provenance'])) : ?>
                <p class="text-blue-600 text-sm leading-relaxed m-0">
                    <?php echo esc_html($loc['provenance']); ?>
                </p>
            <?php endif; ?>

            <a
                href="<?php echo esc_url(\Standard\ContactData\map_url($loc['map_query'])); ?>"
                target="_blank"
                rel="noreferrer noopener"
                class="not-prose inline-flex items-start gap-2 text-blue-700 hover:text-blue-500 no-underline font-sans text-sm leading-relaxed group"
            >
                <span class="block">
                    <?php echo wp_kses($loc['address_html'], ['br' => []]); ?>
                </span>
                <?php icon('external-link', ['class' => 'w-3.5 h-3.5 shrink-0 mt-1 text-blue-400 group-hover:text-blue-500 transition-colors']); ?>
            </a>

            <?php if (!empty($loc['phones'])) : ?>
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
                </ul>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>
