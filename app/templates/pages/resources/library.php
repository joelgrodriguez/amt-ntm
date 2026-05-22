<?php
/**
 * Resources — Full library.
 *
 * Denser grid of every published resource not already pinned in the
 * featured strip. No filter sidebar: resources are heterogeneous and
 * don't have a useful sub-taxonomy.
 *
 * Args:
 *   exclude (int[]): IDs already shown in the featured strip.
 *
 * @package Standard
 * @var array $args
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$exclude = (array) ($args['exclude'] ?? []);
$exclude = array_values(array_unique(array_filter(array_map('intval', $exclude))));

$query = new \WP_Query([
    'post_type'              => 'resource',
    'post_status'            => 'publish',
    'posts_per_page'         => -1,
    'post__not_in'           => $exclude,
    'orderby'                => 'title',
    'order'                  => 'ASC',
    'update_post_meta_cache' => false,
    'no_found_rows'          => true,
]);

if (!$query->have_posts()) {
    return;
}

$icon_for = static function (string $slug): string {
    $map = [
        'portable-rollforming-calculator' => 'settings',
        'ntm-coil-width-calculator'       => 'settings',
        'cutlist-generator'               => 'settings',
        'machine-footprints'              => 'filter',
        'machine-training'                => 'graduation-cap',
        'manuals'                         => 'file-text',
        'downloads'                       => 'download',
        'leasing-financing'               => 'dollar-sign',
        'clip-information'                => 'help-circle',
        'ul-test-information'             => 'help-circle',
        'section-179-information'         => 'dollar-sign',
        'ntm-brand-guidelines'            => 'file-text',
        'rollforming-learning-center'     => 'graduation-cap',
        'roof-panel-machine-assessment-quiz' => 'help-circle',
    ];
    return $map[$slug] ?? 'folder';
};
?>

<section id="resources-library"
         aria-labelledby="resources-library-title"
         tabindex="-1"
         class="bg-white pt-16 pb-16 lg:pt-20 lg:pb-20 scroll-mt-24">
    <div class="container grid gap-8">

        <header class="flex flex-wrap items-end justify-between gap-4">
            <div class="section-header-left">
                <p class="section-eyebrow">
                    <?php esc_html_e('Library', 'standard'); ?>
                    <span class="text-blue-400" aria-hidden="true">
                        · <?php echo esc_html((string) $query->post_count); ?>
                    </span>
                </p>
                <div class="section-divider"></div>
                <h2 id="resources-library-title"
                    class="font-sans font-semibold text-blue-900 leading-tight tracking-tight"
                    style="font-size: var(--text-heading-sm); line-height: var(--leading-heading-sm);">
                    <?php esc_html_e('Every Resource', 'standard'); ?>
                </h2>
            </div>
        </header>

        <ul class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-px list-none p-0 m-0">
            <?php while ($query->have_posts()) : $query->the_post();
                $post  = get_post();
                $url   = get_permalink($post);
                $title = get_the_title($post);
                $icon  = $icon_for((string) $post->post_name);
            ?>
                <li class="bg-white border border-blue-200">
                    <a href="<?php echo esc_url($url); ?>"
                       class="group relative grid grid-rows-[auto_1fr_auto] gap-4 p-5 h-full min-h-[160px] no-underline transition-colors duration-200 hover:bg-blue-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-inset">

                        <span class="inline-flex items-center justify-center w-8 h-8 text-blue-500" aria-hidden="true">
                            <?php icon($icon, ['class' => 'w-5 h-5']); ?>
                        </span>

                        <h3 class="font-sans font-semibold text-blue-900 leading-snug tracking-tight group-hover:text-blue-500 transition-colors duration-200"
                            style="font-size: 16px; line-height: 1.3;">
                            <?php echo esc_html($title); ?>
                        </h3>

                        <span class="font-mono font-medium uppercase tracking-widest text-caption text-blue-400 group-hover:text-blue-500 transition-colors duration-200">
                            <?php esc_html_e('Open', 'standard'); ?>
                            <span class="inline-block ml-1 group-hover:translate-x-0.5 transition-transform duration-200" aria-hidden="true">→</span>
                        </span>
                    </a>
                </li>
            <?php endwhile; ?>
        </ul>

    </div>
</section>

<?php wp_reset_postdata(); ?>
