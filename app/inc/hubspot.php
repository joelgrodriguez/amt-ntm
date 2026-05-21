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

/**
 * Render a lazy HubSpot form mount point.
 *
 * @param array{
 *     form_id?: string,
 *     portal_id?: string,
 *     region?: string,
 *     target_id?: string,
 *     class?: string
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

    ob_start();
    ?>
    <div
        id="<?php echo esc_attr($target_id); ?>"
        class="<?php echo esc_attr($class); ?>"
        data-hubspot-form
        data-hubspot-region="<?php echo esc_attr($region); ?>"
        data-hubspot-portal-id="<?php echo esc_attr($portal_id); ?>"
        data-hubspot-form-id="<?php echo esc_attr($form_id); ?>"
    >
        <div class="hubspot-form__placeholder" data-hubspot-placeholder>
            <?php esc_html_e('Loading form...', 'standard'); ?>
        </div>
        <noscript>
            <p class="text-sm text-blue-600">
                <?php esc_html_e('Enable JavaScript to load the form, or call New Tech Machinery directly.', 'standard'); ?>
            </p>
        </noscript>
    </div>
    <?php

    return (string) ob_get_clean();
}

/**
 * Restrict HubSpot IDs to the character set HubSpot uses for UUIDs/regions.
 */
function sanitize_hubspot_id(string $value): string
{
    return preg_replace('/[^a-zA-Z0-9-]/', '', $value) ?? '';
}
