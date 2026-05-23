<?php
/**
 * UNIQ Page — Final CTA
 *
 * Two-column closer. Left rail: support promise + primary contact CTA.
 * Right rail: four owner-resource jumps (Learning Center, Manuals,
 * Service & Training, Owner Resources) preserved from the legacy page,
 * but presented as a mono-labeled link grid rather than four blue
 * buttons stacked next to each other.
 *
 * Light section so it doesn't compete with the dark "How It Works" two
 * blocks earlier; the page ends quiet, not loud.
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
    ['label' => __('Learning Center', 'standard'),     'href' => '/learning-center/',                    'kind' => 'HUB'],
    ['label' => __('Manuals', 'standard'),             'href' => '/learning-center/resource/manuals/',   'kind' => 'DOCS'],
    ['label' => __('Service & Training', 'standard'),  'href' => '/service-training/',                   'kind' => 'TEAM'],
    ['label' => __('Owner Resources', 'standard'),     'href' => '/owner-resources/',                    'kind' => 'PORTAL'],
];
?>

<section class="section bg-white" aria-labelledby="uniq-final-title">
    <div class="container">
        <div class="grid gap-12 lg:grid-cols-12 lg:gap-16 lg:items-start">

            <div class="lg:col-span-6 grid gap-8">
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

            <div class="lg:col-span-6">
                <p class="font-mono text-[11px] uppercase tracking-[0.18em] text-blue-500 mb-4">
                    <?php esc_html_e('More from NTM', 'standard'); ?>
                </p>
                <ul class="grid border border-blue-200">
                    <?php foreach ($resources as $i => $resource) :
                        $is_last = ($i === count($resources) - 1);
                    ?>
                        <li class="<?php echo $is_last ? '' : 'border-b border-blue-200'; ?>">
                            <a
                                href="<?php echo esc_url(\Standard\Url\internal($resource['href'])); ?>"
                                class="group flex items-center gap-4 px-6 py-5 lg:px-8 transition-colors duration-150 hover:bg-blue-50"
                            >
                                <span class="font-mono text-[10px] uppercase tracking-[0.15em] text-blue-400 w-16 shrink-0">
                                    <?php echo esc_html($resource['kind']); ?>
                                </span>
                                <span class="flex-1 font-sans font-medium text-base text-blue-900 group-hover:text-blue-500 transition-colors">
                                    <?php echo esc_html($resource['label']); ?>
                                </span>
                                <?php icon('arrow-right', ['class' => 'w-4 h-4 text-blue-400 shrink-0 transition-transform duration-150 group-hover:translate-x-1 group-hover:text-blue-500']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

        </div>
    </div>
</section>
