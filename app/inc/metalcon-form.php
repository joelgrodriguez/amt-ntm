<?php
/**
 * METALCON 2026 presentation sign-up form handler.
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

/**
 * Presentation sessions a visitor can pick, keyed by a stable slug.
 *
 * Single source of truth for the form select and the schedule shown on the
 * page, so the two cannot drift.
 *
 * @return array<string, array{date: string, times: list<string>}>
 */
function metalcon_presentation_schedule(): array {
    return [
        [
            'date'  => __('Wednesday, October 7', 'standard'),
            'times' => ['10:30 a.m.', '1:30 p.m.', '3:00 p.m.'],
        ],
        [
            'date'  => __('Thursday, October 8', 'standard'),
            'times' => ['10:30 a.m.', '1:30 p.m.', '3:00 p.m.'],
        ],
        [
            'date'  => __('Friday, October 9', 'standard'),
            'times' => ['9:30 a.m.'],
        ],
    ];
}

/**
 * Flat session options for the form: slug => "Wednesday, October 7 · 10:30 a.m.".
 *
 * @return array<string, string>
 */
function metalcon_presentation_sessions(): array {
    $sessions = [];

    foreach (metalcon_presentation_schedule() as $day) {
        foreach ($day['times'] as $time) {
            $slug = sanitize_title($day['date'] . ' ' . $time);
            $sessions[$slug] = sprintf('%s · %s', $day['date'], $time);
        }
    }

    return $sessions;
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
 * Process a METALCON presentation sign-up.
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
    $session       = sanitize_key(wp_unslash((string) ($_POST['session'] ?? '')));
    $sessions      = metalcon_presentation_sessions();

    $preserved = [
        'metalcon_name'          => $name,
        'metalcon_company'       => $company,
        'metalcon_email'         => $email,
        'metalcon_phone'         => $phone,
        'metalcon_run_today'     => $run_today,
        'metalcon_session'       => $session,
    ];

    if (
        $name === ''
        || $company === ''
        || $email === ''
        || !is_email($email)
        || $phone === ''
        || !array_key_exists($session, $sessions)
    ) {
        metalcon_form_redirect('error', $preserved);
    }

    $recipient = sanitize_email((string) apply_filters(
        'ntm_metalcon_form_recipient',
        NTM_METALCON_FORM_RECIPIENT
    ));
    $subject = sprintf(__('METALCON 2026 presentation sign-up — %s', 'standard'), $company);
    $body = implode("\n", [
        'Name: ' . $name,
        'Company: ' . $company,
        'Email: ' . $email,
        'Phone: ' . $phone,
        'What do you run today: ' . ($run_today !== '' ? $run_today : 'Not provided'),
        'Presentation: ' . $sessions[$session],
    ]);

    if ($recipient === '' || !wp_mail($recipient, $subject, $body)) {
        metalcon_form_redirect('error', $preserved);
    }

    metalcon_form_redirect('thanks');
}
add_action('admin_post_nopriv_ntm_metalcon_request', __NAMESPACE__ . '\\handle_metalcon_request');
add_action('admin_post_ntm_metalcon_request', __NAMESPACE__ . '\\handle_metalcon_request');
