<?php
/**
 * HubSpot form helpers.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard\HubSpot;

if (!defined('ABSPATH')) {
    exit;
}

const DEFAULT_REGION = 'na1';
const DEFAULT_PORTAL_ID = '4478417';
const DEFAULT_FORM_ID = 'e5267365-c19e-4f19-991a-003c5fdbeecf';
const META_FORM_ID = 'e5160c2b-c2f3-4a09-9eaa-0b9c5a3986a3';
// METALCON 2026 presentation sign-up. Its time-slot options must match
// Standard\metalcon_presentation_schedule().
const METALCON_2026_FORM_ID = 'cc935d63-78bc-41bb-b87a-fcfa06440033';
const SSM_FORM_ID = '660c0552-28f7-4b3c-9f7e-7fd9c98f9561';
// Service-request form. Defaults to the general form until the dedicated
// HubSpot form (NetSuite/HubSpot pipeline) is created; swap the literal then.
const SERVICE_REQUEST_FORM_ID = DEFAULT_FORM_ID;

/**
 * Render a lazy HubSpot form mount point.
 *
 * @param array{
 *     form_id?: string,
 *     portal_id?: string,
 *     region?: string,
 *     target_id?: string,
 *     class?: string,
 *     noscript_html?: string
 * } $args
 * @return string
 */
function render_form(array $args = []): string
{
    $form_id = sanitize_hubspot_id((string) ($args['form_id'] ?? DEFAULT_FORM_ID));

    if ($form_id === '') {
        return '';
    }

    $portal_id = sanitize_hubspot_id((string) ($args['portal_id'] ?? DEFAULT_PORTAL_ID));
    $region = sanitize_hubspot_id((string) ($args['region'] ?? DEFAULT_REGION));
    $target_id = sanitize_html_class((string) ($args['target_id'] ?? 'hubspot-form-' . substr(md5($form_id . '-' . (string) get_the_ID()), 0, 10)));
    $class = trim('hubspot-form min-h-[28rem] ' . (string) ($args['class'] ?? ''));
    $call_html = call_sales_html();
    $noscript_html = isset($args['noscript_html']) && is_string($args['noscript_html'])
        ? $args['noscript_html']
        : '<p class="text-sm text-blue-600">' . esc_html__('Enable JavaScript to load the form.', 'standard') . ' ' . $call_html . '</p>';
    $failure_html = '<p class="text-sm text-blue-600" role="status">' . esc_html__('The form could not load.', 'standard') . ' ' . $call_html . '</p>';

    ob_start();
    ?>
    <div
        id="<?php echo esc_attr($target_id); ?>"
        class="<?php echo esc_attr($class); ?>"
        aria-busy="true"
        data-hubspot-form
        data-hubspot-region="<?php echo esc_attr($region); ?>"
        data-hubspot-portal-id="<?php echo esc_attr($portal_id); ?>"
        data-hubspot-form-id="<?php echo esc_attr($form_id); ?>"
    >
        <div class="hubspot-form__placeholder" data-hubspot-placeholder aria-hidden="true">
            <div class="hubspot-form__skeleton">
                <span class="hubspot-form__skeleton-eyebrow"><?php esc_html_e('Loading', 'standard'); ?></span>
                <span class="hubspot-form__skeleton-line hubspot-form__skeleton-line--label"></span>
                <span class="hubspot-form__skeleton-line hubspot-form__skeleton-line--field"></span>
                <span class="hubspot-form__skeleton-line hubspot-form__skeleton-line--label"></span>
                <span class="hubspot-form__skeleton-line hubspot-form__skeleton-line--field"></span>
                <span class="hubspot-form__skeleton-line hubspot-form__skeleton-line--label"></span>
                <span class="hubspot-form__skeleton-line hubspot-form__skeleton-line--textarea"></span>
                <span class="hubspot-form__skeleton-line hubspot-form__skeleton-line--button"></span>
            </div>
        </div>
        <noscript>
            <?php echo wp_kses_post($noscript_html); ?>
        </noscript>
        <template data-hubspot-fallback><?php echo wp_kses_post($failure_html); ?></template>
    </div>
    <?php

    return (string) ob_get_clean();
}

/**
 * "Call NTM Sales" sentence with a tap-to-call link, used when a form cannot load.
 */
function call_sales_html(): string
{
    $phone = null;

    if (function_exists('Standard\\ContactData\\get_locations')) {
        foreach (\Standard\ContactData\get_locations() as $location) {
            if (!empty($location['phones'][0])) {
                $phone = $location['phones'][0];
                break;
            }
        }
    }

    if ($phone === null) {
        return esc_html__('Call New Tech Machinery directly.', 'standard');
    }

    return sprintf(
        /* translators: %s: linked NTM sales phone number. */
        esc_html__('Call NTM Sales at %s.', 'standard'),
        '<a class="font-medium text-blue-500 underline" href="tel:' . esc_attr($phone['tel']) . '">' . esc_html($phone['display']) . '</a>'
    );
}

/**
 * Restrict HubSpot IDs to the character set HubSpot uses for UUIDs/regions.
 */
function sanitize_hubspot_id(string $value): string
{
    return preg_replace('/[^a-zA-Z0-9-]/', '', $value) ?? '';
}
