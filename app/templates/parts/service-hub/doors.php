<?php
/**
 * Service Hub triage doors — three primary paths for machine owners.
 * Mobile: stacked. md+: 3-col. Touch targets >= 44px.
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$doors = [
    [
        'icon'  => 'settings',
        'label' => __('Browse by machine', 'standard'),
        'desc'  => __('Manuals, troubleshooting, parts, and videos for your machine.', 'standard'),
        'url'   => '#machines',
    ],
    [
        'icon'  => 'file-text',
        'label' => __('Open a service request', 'standard'),
        'desc'  => __('Tell the service team what you need. We will follow up.', 'standard'),
        'url'   => \Standard\Url\internal('/service-hub/request/'),
    ],
    [
        'icon'  => 'folder',
        'label' => __('Search the knowledge base', 'standard'),
        'desc'  => __('Search every manual, article, video, and download.', 'standard'),
        'url'   => '#search',
    ],
];
?>
<section class="container section-compact" aria-label="<?php esc_attr_e('Choose a path', 'standard'); ?>">
    <div class="grid gap-4 md:grid-cols-3">
        <?php foreach ($doors as $door) : ?>
            <a href="<?php echo esc_url($door['url']); ?>"
               class="group flex flex-col gap-3 min-h-[44px] bg-white border border-blue-200 p-6 transition-colors duration-200 hover:border-blue-500 no-underline">
                <?php icon($door['icon'], ['class' => 'w-6 h-6 text-blue-700 transition-colors duration-200 group-hover:text-blue-500', 'aria-hidden' => 'true']); ?>
                <span class="font-medium text-heading-sm text-blue-900 transition-colors duration-200 group-hover:text-blue-500">
                    <?php echo esc_html($door['label']); ?>
                </span>
                <span class="font-sans text-blue-600" style="font-size: var(--text-body); line-height: var(--leading-body);">
                    <?php echo esc_html($door['desc']); ?>
                </span>
            </a>
        <?php endforeach; ?>
    </div>
</section>
