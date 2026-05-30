<?php
/**
 * Template Name: Service Request
 *
 * /service-hub/request/ — mounts the HubSpot service-request form. Reads
 * ?machine=<slug> to show which machine the request is for (validated
 * against the active machine list; ignored if unknown). Real backend
 * routing is owned by HubSpot (NetSuite/HubSpot pipeline).
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$machine_slug = isset($_GET['machine']) ? sanitize_key((string) wp_unslash($_GET['machine'])) : '';
$machine      = $machine_slug !== '' ? \Standard\ServiceHubMachines\find_machine($machine_slug) : null;
$machine_name = $machine !== null ? (string) ($machine['name'] ?? $machine['short_name'] ?? '') : '';

get_header();
?>

<main id="primary">

    <?php get_template_part('templates/parts/breadcrumbs'); ?>

    <header class="pattern-dot-grid pattern-dot-grid--surface bg-blue-50 border-b border-blue-200">
        <div class="container section-compact">
            <div class="grid gap-4 max-w-4xl">
                <span class="section-eyebrow"><?php esc_html_e('Service Hub', 'standard'); ?></span>
                <h1 class="font-semibold text-heading-lg lg:text-display text-blue-900 leading-tight tracking-tight">
                    <?php esc_html_e('Open a service request', 'standard'); ?>
                </h1>
                <?php if ($machine_name !== '') : ?>
                    <p class="font-mono font-medium uppercase tracking-wider text-blue-700" style="font-size: var(--text-caption);">
                        <?php
                        printf(
                            /* translators: %s machine name. */
                            esc_html__('For: %s', 'standard'),
                            esc_html($machine_name)
                        );
                        ?>
                    </p>
                <?php endif; ?>
                <p class="font-sans text-blue-600 max-w-2xl" style="font-size: var(--text-body); line-height: var(--leading-body);">
                    <?php esc_html_e('Tell the service team what you need. Include your machine and serial number if you have them, and we will follow up.', 'standard'); ?>
                </p>
            </div>
        </div>
    </header>

    <section class="container section-compact">
        <div class="max-w-2xl">
            <?php
            echo \Standard\HubSpot\render_form([
                'form_id' => \Standard\HubSpot\SERVICE_REQUEST_FORM_ID,
                'class'   => 'service-request-form',
            ]);
            ?>
        </div>
    </section>

    <?php
    get_template_part('templates/parts/cta/closer', null, [
        'title'           => __('Prefer to call?', 'standard'),
        'text'            => __('Our service team has been on the other end of the phone for more than 30 years.', 'standard'),
        'cta_primary'     => __('Talk to a service specialist', 'standard'),
        'cta_primary_url' => '/contact/',
        'section_id'      => 'service-request-closer-title',
    ]);
    ?>
</main>

<?php
get_footer();
