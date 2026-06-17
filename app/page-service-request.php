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
                    <?php esc_html_e('Check the Service Hub and FAQ first, many answers are there. If you still need us, send a request and the service team will follow up.', 'standard'); ?>
                </p>
            </div>
        </div>
    </header>

    <section class="section" aria-labelledby="service-request-form-title">
        <div class="container">
            <div class="grid gap-12 lg:grid-cols-[minmax(0,1fr)_minmax(420px,560px)] lg:gap-16 lg:items-start">

                <!-- Left rail: deflect before the form. Many "service requests"
                     are answered faster by the hub search or the FAQ. -->
                <div class="grid gap-6 min-w-0 content-start">
                    <header class="grid gap-3 max-w-xl">
                        <p class="section-eyebrow"><?php esc_html_e('Before you start', 'standard'); ?></p>
                        <h2 class="font-sans text-2xl md:text-3xl font-medium tracking-tight text-blue-900 m-0">
                            <?php esc_html_e('You may not need to wait for us.', 'standard'); ?>
                        </h2>
                        <p class="text-blue-600" style="font-size: var(--text-body); line-height: var(--leading-body);">
                            <?php esc_html_e('Most questions are already answered in the Service Hub and our FAQ. Check there first, you might get your answer in seconds instead of a follow-up.', 'standard'); ?>
                        </p>
                    </header>

                    <ul class="grid gap-3 m-0 p-0 list-none max-w-xl" role="list">
                        <li>
                            <a href="<?php echo esc_url(\Standard\Url\internal('/service-hub/') . '#search'); ?>" class="group flex items-start gap-4 border border-blue-200 bg-blue-50 p-5 no-underline transition-colors duration-200 hover:border-blue-500">
                                <?php icon('search', ['class' => 'w-5 h-5 text-blue-500 shrink-0 mt-0.5']); ?>
                                <span class="grid gap-1">
                                    <span class="font-sans font-medium text-blue-900 group-hover:text-blue-700"><?php esc_html_e('Search the Service Hub', 'standard'); ?></span>
                                    <span class="text-blue-600" style="font-size: var(--text-caption);"><?php esc_html_e('Manuals, troubleshooting, parts, and videos for every machine.', 'standard'); ?></span>
                                </span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo esc_url(\Standard\Url\internal('/faq/')); ?>" class="group flex items-start gap-4 border border-blue-200 bg-blue-50 p-5 no-underline transition-colors duration-200 hover:border-blue-500">
                                <?php icon('help-circle', ['class' => 'w-5 h-5 text-blue-500 shrink-0 mt-0.5']); ?>
                                <span class="grid gap-1">
                                    <span class="font-sans font-medium text-blue-900 group-hover:text-blue-700"><?php esc_html_e('Visit the FAQ', 'standard'); ?></span>
                                    <span class="text-blue-600" style="font-size: var(--text-caption);"><?php esc_html_e('Quick answers to the questions we hear most often.', 'standard'); ?></span>
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Right: the service-request form. -->
                <aside class="border-t border-blue-500 border-x-0 border-b-0 bg-blue-50 p-6 md:p-8 lg:sticky lg:top-24" aria-labelledby="service-request-form-title">
                    <div class="grid gap-6">
                        <header class="grid gap-3">
                            <p class="section-eyebrow"><?php esc_html_e('Still need us?', 'standard'); ?></p>
                            <h2 id="service-request-form-title" class="font-sans text-2xl md:text-3xl font-medium tracking-tight text-blue-900 m-0">
                                <?php esc_html_e('Send your request', 'standard'); ?>
                            </h2>
                            <p class="text-blue-600" style="font-size: var(--text-caption); line-height: var(--leading-body);">
                                <?php esc_html_e('Include your machine and serial number if you have them, and the service team will follow up.', 'standard'); ?>
                            </p>
                        </header>
                        <?php
                        echo \Standard\HubSpot\render_form([
                            'form_id' => \Standard\HubSpot\SERVICE_REQUEST_FORM_ID,
                            'class'   => 'service-request-form',
                        ]);
                        ?>
                    </div>
                </aside>

            </div>
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
