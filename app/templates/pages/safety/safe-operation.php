<?php
/**
 * Safety Page — Safe Operation Resources
 *
 * Links the already-published, already-reviewed safe-operation content from the
 * learning center. This is the lowest-risk block on the page: it points at live
 * content rather than authoring new safety copy. Mirrors the uniq/resources.php
 * mono-labeled link-row pattern.
 *
 * URLs verified live (WP-CLI, 2026-06-23):
 *   - post 264  /learning-center/best-safety-practices-portable-rollformer-infographic/
 *   - video 6316 /learning-center/video/safe-rollforming-machine-operation-video/
 *   - download 2057 /learning-center/download/simple-steps-safe-machine-operation-infographic/
 *   - post 887  /learning-center/what-to-expect-portable-rollforming-machine-training-session/
 *
 * @package Standard
 * @usage page-safety.php
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$content = [
    'eyebrow'  => __('Safe operation', 'standard'),
    'title'    => __('Run it right', 'standard'),
    'subtitle' => __('The equipment is half of it. These walk an operator through safe day-to-day use, from first training session to the practices that keep a crew out of trouble.', 'standard'),
];

$resources = [
    [
        'kind'  => __('Guide', 'standard'),
        'label' => __('10 Best Safety Practices When Using a Portable Rollformer', 'standard'),
        'url'   => '/learning-center/best-safety-practices-portable-rollformer-infographic/',
    ],
    [
        'kind'  => __('Video', 'standard'),
        'label' => __('10 Safety Tips for Operating a Portable Roof Panel or Gutter Machine', 'standard'),
        'url'   => '/learning-center/video/safe-rollforming-machine-operation-video/',
    ],
    [
        'kind'  => __('Download', 'standard'),
        'label' => __('Simple Steps to Safe Machine Operation Infographic', 'standard'),
        'url'   => '/learning-center/download/simple-steps-safe-machine-operation-infographic/',
    ],
    [
        'kind'  => __('Article', 'standard'),
        'label' => __('What to Expect in a Portable Rollforming Machine Training Session', 'standard'),
        'url'   => '/learning-center/what-to-expect-portable-rollforming-machine-training-session/',
    ],
];
?>

<section class="section bg-blue-50 border-b border-blue-200" aria-labelledby="safety-resources-title">
    <div class="container section-content">

        <div class="section-header-left max-w-2xl">
            <p class="section-eyebrow">
                <?php echo esc_html($content['eyebrow']); ?>
            </p>
            <div class="section-divider"></div>
            <h2 id="safety-resources-title" class="section-title">
                <?php echo esc_html($content['title']); ?>
            </h2>
            <p class="section-subtitle text-pretty">
                <?php echo esc_html($content['subtitle']); ?>
            </p>
        </div>

        <ul class="bg-white border border-blue-200" role="list">
            <?php foreach ($resources as $i => $resource) :
                $is_last = ($i === count($resources) - 1);
            ?>
                <li class="<?php echo $is_last ? '' : 'border-b border-blue-200'; ?>">
                    <a
                        href="<?php echo esc_url(\Standard\Url\internal($resource['url'])); ?>"
                        class="group flex items-start gap-4 px-6 py-5 lg:px-8 lg:py-6 transition-colors duration-150 hover:bg-blue-50"
                    >
                        <span class="font-mono text-[10px] uppercase tracking-mono-meta text-blue-400 shrink-0 min-w-20 pt-1">
                            <?php echo esc_html($resource['kind']); ?>
                        </span>
                        <span class="flex-1 font-sans text-base text-blue-700 group-hover:text-blue-500 transition-colors">
                            <?php echo esc_html($resource['label']); ?>
                        </span>
                        <?php icon('arrow-right', ['class' => 'w-4 h-4 text-blue-400 shrink-0 mt-1.5 transition-transform duration-150 group-hover:translate-x-1 group-hover:text-blue-500']); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>

    </div>
</section>
