<?php
/**
 * METALCON 2026 meeting-request form handler.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard;

if (!defined('ABSPATH')) {
    exit;
}

if (!defined('NTM_METALCON_FORM_RECIPIENT')) {
    define('NTM_METALCON_FORM_RECIPIENT', (string) get_option('admin_email'));
}

/** @return array<string, string> */
function metalcon_preferred_days(): array {
    return [
        'wed-oct-7' => __('Wed Oct 7', 'standard'),
        'thu-oct-8' => __('Thu Oct 8', 'standard'),
        'fri-oct-9' => __('Fri Oct 9', 'standard'),
        'any-day'   => __('Any day', 'standard'),
    ];
}

/**
 * Redirect to the form state and stop processing.
 *
 * @param array<string, string> $args
 */
function metalcon_form_redirect(string $status, array $args = []): void {
    $url = add_query_arg(
        array_merge(['metalcon' => $status], $args),
        home_url('/metalcon-2026/')
    );

    wp_safe_redirect($url . '#metalcon-meeting-form');
    exit;
}

/**
 * Process a METALCON meeting request.
 */
function handle_metalcon_request(): void {
    $nonce = sanitize_text_field(wp_unslash((string) ($_POST['metalcon_nonce'] ?? '')));
    if ($nonce === '' || !wp_verify_nonce($nonce, 'ntm_metalcon_request')) {
        metalcon_form_redirect('error');
    }

    if (sanitize_text_field(wp_unslash((string) ($_POST['website'] ?? ''))) !== '') {
        metalcon_form_redirect('thanks');
    }

    $name          = sanitize_text_field(wp_unslash((string) ($_POST['name'] ?? '')));
    $company       = sanitize_text_field(wp_unslash((string) ($_POST['company'] ?? '')));
    $email         = sanitize_email(wp_unslash((string) ($_POST['email'] ?? '')));
    $phone         = sanitize_text_field(wp_unslash((string) ($_POST['phone'] ?? '')));
    $run_today     = sanitize_text_field(wp_unslash((string) ($_POST['run_today'] ?? '')));
    $preferred_day = sanitize_key(wp_unslash((string) ($_POST['preferred_day'] ?? '')));
    $days          = metalcon_preferred_days();

    $preserved = [
        'metalcon_name'          => $name,
        'metalcon_company'       => $company,
        'metalcon_email'         => $email,
        'metalcon_phone'         => $phone,
        'metalcon_run_today'     => $run_today,
        'metalcon_preferred_day' => $preferred_day,
    ];

    if (
        $name === ''
        || $company === ''
        || $email === ''
        || !is_email($email)
        || $phone === ''
        || !array_key_exists($preferred_day, $days)
    ) {
        metalcon_form_redirect('error', $preserved);
    }

    $recipient = sanitize_email((string) apply_filters(
        'ntm_metalcon_form_recipient',
        NTM_METALCON_FORM_RECIPIENT
    ));
    $subject = sprintf(__('METALCON 2026 meeting request — %s', 'standard'), $company);
    $body = implode("\n", [
        'Name: ' . $name,
        'Company: ' . $company,
        'Email: ' . $email,
        'Phone: ' . $phone,
        'What do you run today: ' . ($run_today !== '' ? $run_today : 'Not provided'),
        'Preferred day: ' . $days[$preferred_day],
    ]);

    if ($recipient === '' || !wp_mail($recipient, $subject, $body)) {
        metalcon_form_redirect('error', $preserved);
    }

    metalcon_form_redirect('thanks');
}
add_action('admin_post_nopriv_ntm_metalcon_request', __NAMESPACE__ . '\\handle_metalcon_request');
add_action('admin_post_ntm_metalcon_request', __NAMESPACE__ . '\\handle_metalcon_request');
