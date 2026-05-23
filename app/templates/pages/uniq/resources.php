<?php
/**
 * UNIQ Page — Resource Library
 *
 * Two columns: instructional documentation (PDFs + manual) and video
 * tutorials. Each row is a mono-labeled link with the resource kind
 * (PDF / MANUAL / VIDEO) as a small affix, and a chevron at the right.
 * The column divider is a single hairline that runs full-height between
 * the two stacks — DESIGN.md §8.5 signature pattern.
 *
 * @package Standard
 *
 * @usage page-uniq-control-system.php
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\MachinesData\get_uniq_resources;

$content = [
    'eyebrow'    => __('Resources', 'standard'),
    'title'      => __('Documentation & Tutorials', 'standard'),
    'subtitle'   => __('Field-update instructions, supplement manual, and the full video library — everything an operator or tech needs to install, run, or upgrade UNIQ.', 'standard'),
    'col_docs'   => __('Instructional Documentation', 'standard'),
    'col_videos' => __('Video Tutorials', 'standard'),
];

$resources = get_uniq_resources();
$docs      = $resources['docs'];
$videos    = $resources['videos'];
?>

<section class="section bg-blue-50 border-b border-blue-200" aria-labelledby="uniq-resources-title">
    <div class="container section-content">

        <div class="section-header max-w-3xl mx-auto">
            <p class="section-eyebrow">
                <?php echo esc_html($content['eyebrow']); ?>
            </p>
            <div class="section-divider-center"></div>
            <h2 id="uniq-resources-title" class="section-title">
                <?php echo esc_html($content['title']); ?>
            </h2>
            <p class="section-subtitle max-w-2xl mx-auto">
                <?php echo esc_html($content['subtitle']); ?>
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 bg-white border border-blue-200">

            <div class="border-b border-blue-200 md:border-b-0 md:border-r">
                <h3 class="font-mono font-medium text-[11px] uppercase tracking-[0.18em] text-blue-500 p-6 lg:p-8 border-b border-blue-200">
                    <?php echo esc_html($content['col_docs']); ?>
                </h3>
                <ul>
                    <?php foreach ($docs as $i => $doc) :
                        $is_last = ($i === count($docs) - 1);
                    ?>
                        <li class="<?php echo $is_last ? '' : 'border-b border-blue-200'; ?>">
                            <a
                                href="<?php echo esc_url(\Standard\Url\internal($doc['url'])); ?>"
                                class="group flex items-start gap-4 px-6 py-5 lg:px-8 lg:py-6 transition-colors duration-150 hover:bg-blue-50"
                            >
                                <span class="font-mono text-[10px] uppercase tracking-[0.15em] text-blue-400 shrink-0 w-16 pt-1">
                                    <?php echo esc_html($doc['kind']); ?>
                                </span>
                                <span class="flex-1 font-sans text-base text-blue-700 group-hover:text-blue-500 transition-colors">
                                    <?php echo esc_html($doc['label']); ?>
                                </span>
                                <?php icon('arrow-right', ['class' => 'w-4 h-4 text-blue-400 shrink-0 mt-1.5 transition-transform duration-150 group-hover:translate-x-1 group-hover:text-blue-500']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div>
                <h3 class="font-mono font-medium text-[11px] uppercase tracking-[0.18em] text-blue-500 p-6 lg:p-8 border-b border-blue-200">
                    <?php echo esc_html($content['col_videos']); ?>
                </h3>
                <ul>
                    <?php foreach ($videos as $i => $video) :
                        $is_last = ($i === count($videos) - 1);
                    ?>
                        <li class="<?php echo $is_last ? '' : 'border-b border-blue-200'; ?>">
                            <a
                                href="<?php echo esc_url(\Standard\Url\internal($video['url'])); ?>"
                                class="group flex items-start gap-4 px-6 py-5 lg:px-8 lg:py-6 transition-colors duration-150 hover:bg-blue-50"
                            >
                                <span class="font-mono text-[10px] uppercase tracking-[0.15em] text-blue-400 shrink-0 w-16 pt-1">
                                    <?php echo esc_html($video['kind']); ?>
                                </span>
                                <span class="flex-1 font-sans text-base text-blue-700 group-hover:text-blue-500 transition-colors">
                                    <?php echo esc_html($video['label']); ?>
                                </span>
                                <?php icon('arrow-right', ['class' => 'w-4 h-4 text-blue-400 shrink-0 mt-1.5 transition-transform duration-150 group-hover:translate-x-1 group-hover:text-blue-500']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

        </div>

    </div>
</section>
