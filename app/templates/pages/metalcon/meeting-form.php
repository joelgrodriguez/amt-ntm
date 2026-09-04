<?php
/**
 * METALCON 2026 — native presentation sign-up form.
 *
 * @package Standard
 * @usage page-metalcon-2026.php
 */

declare(strict_types=1);

namespace Standard;

if (!defined('ABSPATH')) {
    exit;
}

$status = sanitize_key(wp_unslash((string) ($_GET['metalcon'] ?? '')));
$values = [
    'name'          => sanitize_text_field(wp_unslash((string) ($_GET['metalcon_name'] ?? ''))),
    'company'       => sanitize_text_field(wp_unslash((string) ($_GET['metalcon_company'] ?? ''))),
    'email'         => sanitize_email(wp_unslash((string) ($_GET['metalcon_email'] ?? ''))),
    'phone'         => sanitize_text_field(wp_unslash((string) ($_GET['metalcon_phone'] ?? ''))),
    'run_today'     => sanitize_text_field(wp_unslash((string) ($_GET['metalcon_run_today'] ?? ''))),
    'session'       => sanitize_key(wp_unslash((string) ($_GET['metalcon_session'] ?? ''))),
];
$sessions = metalcon_presentation_sessions();
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
                <?php if ($status === 'thanks') : ?>
                    <div class="grid gap-3" role="status">
                        <h3 class="text-2xl font-medium tracking-tight text-blue-900">
                            <?php esc_html_e('You are on the list.', 'standard'); ?>
                        </h3>
                        <p class="text-base leading-relaxed text-blue-600">
                            <?php esc_html_e('We will contact you by phone or email to confirm your spot.', 'standard'); ?>
                        </p>
                    </div>
                <?php else : ?>
                    <?php if ($status === 'error') : ?>
                        <div class="mb-6 border-l-4 border-red bg-red/5 p-4 text-blue-900" role="alert">
                            <?php esc_html_e('We could not send your sign-up. Check the required fields and try again.', 'standard'); ?>
                        </div>
                    <?php endif; ?>

                    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" class="grid gap-5">
                        <input type="hidden" name="action" value="ntm_metalcon_request">
                        <?php wp_nonce_field('ntm_metalcon_request', 'metalcon_nonce', false); ?>

                        <div class="absolute left-[-10000px] top-auto h-px w-px overflow-hidden" aria-hidden="true">
                            <label for="metalcon-website"><?php esc_html_e('Website', 'standard'); ?></label>
                            <input id="metalcon-website" type="text" name="website" value="" tabindex="-1" autocomplete="off">
                        </div>

                        <div class="grid gap-5 sm:grid-cols-2">
                            <div class="field">
                                <label for="metalcon-name" class="field-label"><?php esc_html_e('Name', 'standard'); ?></label>
                                <input id="metalcon-name" class="field-input" type="text" name="name" value="<?php echo esc_attr($values['name']); ?>" autocomplete="name" required>
                            </div>
                            <div class="field">
                                <label for="metalcon-company" class="field-label"><?php esc_html_e('Company', 'standard'); ?></label>
                                <input id="metalcon-company" class="field-input" type="text" name="company" value="<?php echo esc_attr($values['company']); ?>" autocomplete="organization" required>
                            </div>
                            <div class="field">
                                <label for="metalcon-email" class="field-label"><?php esc_html_e('Email', 'standard'); ?></label>
                                <input id="metalcon-email" class="field-input" type="email" name="email" value="<?php echo esc_attr($values['email']); ?>" autocomplete="email" required>
                            </div>
                            <div class="field">
                                <label for="metalcon-phone" class="field-label"><?php esc_html_e('Phone', 'standard'); ?></label>
                                <input id="metalcon-phone" class="field-input" type="tel" name="phone" value="<?php echo esc_attr($values['phone']); ?>" autocomplete="tel" required>
                            </div>
                        </div>

                        <div class="field">
                            <label for="metalcon-run-today" class="field-label"><?php esc_html_e('What do you run today?', 'standard'); ?></label>
                            <input id="metalcon-run-today" class="field-input" type="text" name="run_today" value="<?php echo esc_attr($values['run_today']); ?>">
                        </div>

                        <div class="field">
                            <label for="metalcon-session" class="field-label"><?php esc_html_e('Date and time', 'standard'); ?></label>
                            <select id="metalcon-session" class="field-select" name="session" required>
                                <option value=""><?php esc_html_e('Choose a date and time', 'standard'); ?></option>
                                <?php foreach ($sessions as $value => $label) : ?>
                                    <option value="<?php echo esc_attr($value); ?>" <?php selected($values['session'], $value); ?>>
                                        <?php echo esc_html($label); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary btn--commit w-full whitespace-nowrap sm:w-auto sm:justify-self-start">
                            <?php esc_html_e('Save my spot', 'standard'); ?>
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
