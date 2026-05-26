<?php
/**
 * UNIQ Page — Final CTA
 *
 * Two-column closer with a 7/5 split favoring the contact column.
 * Left rail (lg:col-span-7): support promise + primary contact CTA;
 * this is the section's primary action. Right rail (lg:col-span-5):
 * four owner-resource jumps as a tight mono-link stack, no surrounding
 * border so it sits beside the contact block as a quiet secondary
 * affordance rather than a competing bordered grid.
 *
 * Light section so it doesn't compete with the dark "How It Works"
 * two blocks earlier; the page ends quiet, not loud.
 *
 * @package Standard
 *
 * @usage page-uniq-control-system.php
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$content = [
    'eyebrow'        => __("Support", 'standard'),
    'title'          => __('Want to Know More?', 'standard'),
    'text'           => __("Questions about UNIQ or any portable rollformer in the lineup? We staff a real service team and a real training group — talk to either.", 'standard'),
    'cta_primary'    => __('Contact NTM', 'standard'),
    'cta_primary_url'=> '/contact/',
    'cta_secondary'  => __('Service & Training', 'standard'),
    'cta_secondary_url' => '/service-training/',
];

$resources = [
    ['label' => __('Learning Center', 'standard'),    'href' => '/learning-center/'],
    ['label' => __('Manuals', 'standard'),            'href' => '/learning-center/resource/manuals/'],
    ['label' => __('Service & Training', 'standard'), 'href' => '/service-training/'],
    ['label' => __('Owner Resources', 'standard'),    'href' => '/owner-resources/'],
];
?>

<section class="section bg-white" aria-labelledby="uniq-final-title">
    <div class="container">
        <div class="grid gap-12 lg:grid-cols-12 lg:gap-16 lg:items-start">

            <div class="lg:col-span-7 grid gap-8">
                <div class="section-header-left">
                    <p class="section-eyebrow">
                        <?php echo esc_html($content['eyebrow']); ?>
                    </p>
                    <div class="section-divider"></div>
                    <h2 id="uniq-final-title" class="section-title">
                        <?php echo esc_html($content['title']); ?>
                    </h2>
                    <p class="section-subtitle max-w-xl">
                        <?php echo esc_html($content['text']); ?>
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="<?php echo esc_url(\Standard\Url\internal($content['cta_primary_url'])); ?>" class="btn btn-primary">
                        <?php echo esc_html($content['cta_primary']); ?>
                        <?php icon('arrow-right', ['class' => 'w-5 h-5']); ?>
                    </a>
                    <a href="<?php echo esc_url(\Standard\Url\internal($content['cta_secondary_url'])); ?>" class="btn btn-outline-dark">
                        <?php echo esc_html($content['cta_secondary']); ?>
                    </a>
                </div>
            </div>

            <div class="lg:col-span-5 lg:pt-2">
                <p class="font-mono text-[11px] uppercase tracking-[0.18em] text-blue-500 mb-6">
                    <?php esc_html_e('More from NTM', 'standard'); ?>
                </p>
                <ul role="list" class="grid gap-3">
                    <?php foreach ($resources as $resource) : ?>
                        <li>
                            <a
                                href="<?php echo esc_url(\Standard\Url\internal($resource['href'])); ?>"
                                class="group inline-flex items-center gap-3 font-sans text-base text-blue-700 hover:text-blue-500 transition-colors duration-150"
                            >
                                <?php echo esc_html($resource['label']); ?>
                                <?php icon('arrow-right', ['class' => 'w-3 h-3 text-blue-400 transition-transform duration-150 group-hover:translate-x-1 group-hover:text-blue-500']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

        </div>
    </div>
</section>
