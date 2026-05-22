<?php
/**
 * Resources — Featured strip.
 *
 * Three editorially-pinned resources surfaced above the full library.
 * Treated as larger tiles (5/6 of the page width) with a mono kicker,
 * sans display title, and a one-line lede so the user can see WHAT
 * each one does without clicking through.
 *
 * Args:
 *   featured (WP_Post[]): pinned resources, already resolved and
 *                        filtered to publish state.
 *
 * @package Standard
 * @var array $args
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$featured = $args['featured'] ?? [];
if (!is_array($featured) || $featured === []) {
    return;
}

/**
 * Icon per featured tile. Falls back to 'folder' if a slug isn't mapped.
 * Slug-driven so editorial can swap pins without touching templates.
 */
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

/**
 * One-line action lede per featured tile. Editor copy when present
 * (post excerpt), otherwise a default per slug. Last fallback: title.
 */
$lede_for = static function (\WP_Post $post): string {
    $excerpt = trim(wp_strip_all_tags((string) $post->post_excerpt));
    if ($excerpt !== '') {
        return $excerpt;
    }

    $defaults = [
        'portable-rollforming-calculator' => __('Run job cost, output, and ROI against a real spec.', 'standard'),
        'ntm-coil-width-calculator'       => __('Find the right coil width for any NTM profile.', 'standard'),
        'cutlist-generator'                => __('Build a panel cut list before the truck leaves the yard.', 'standard'),
        'machine-training'                 => __('Operator training programs at the Aurora plant.', 'standard'),
        'machine-footprints'               => __('Setup dimensions for every NTM machine.', 'standard'),
        'manuals'                          => __('Operator and setup manuals for the entire NTM lineup.', 'standard'),
    ];

    return $defaults[$post->post_name] ?? get_the_title($post);
};
?>

<section id="resources-featured"
         aria-labelledby="resources-featured-title"
         tabindex="-1"
         class="bg-white border-b border-blue-200 pt-12 pb-16 lg:pt-16 lg:pb-20 scroll-mt-24">
    <div class="container grid gap-8">

        <header class="section-header-left">
            <p class="section-eyebrow">
                <?php esc_html_e('Featured', 'standard'); ?>
                <span class="text-blue-400" aria-hidden="true">
                    · <?php echo esc_html((string) count($featured)); ?>
                </span>
            </p>
            <div class="section-divider"></div>
            <h2 id="resources-featured-title"
                class="font-sans font-semibold text-blue-900 leading-tight tracking-tight"
                style="font-size: var(--text-heading-sm); line-height: var(--leading-heading-sm);">
                <?php esc_html_e('Start Here', 'standard'); ?>
            </h2>
        </header>

        <ul class="grid grid-cols-1 md:grid-cols-3 gap-px bg-blue-200 border border-blue-200 list-none p-0 m-0">
            <?php foreach ($featured as $post) :
                $url   = get_permalink($post);
                $title = get_the_title($post);
                $icon  = $icon_for((string) $post->post_name);
                $lede  = $lede_for($post);
            ?>
                <li class="bg-white">
                    <a href="<?php echo esc_url($url); ?>"
                       class="group relative grid grid-rows-[auto_1fr_auto] gap-6 p-6 lg:p-8 h-full no-underline transition-colors duration-200 hover:bg-blue-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-inset">

                        <div class="flex items-center justify-between">
                            <span class="inline-flex items-center justify-center w-10 h-10 border border-blue-200 text-blue-700 group-hover:border-blue-500 group-hover:text-blue-500 transition-colors duration-200" aria-hidden="true">
                                <?php icon($icon, ['class' => 'w-5 h-5']); ?>
                            </span>
                            <span class="font-mono font-medium uppercase tracking-widest text-blue-400" style="font-size: 10px;">
                                <?php esc_html_e('Tool', 'standard'); ?>
                            </span>
                        </div>

                        <div class="grid gap-3 content-start">
                            <h3 class="font-sans font-semibold text-blue-900 leading-tight tracking-tight group-hover:text-blue-500 transition-colors duration-200"
                                style="font-size: 24px; line-height: 1.2;">
                                <?php echo esc_html($title); ?>
                            </h3>
                            <p class="font-sans text-blue-600 leading-snug"
                               style="font-size: 14px; line-height: 1.5;">
                                <?php echo esc_html($lede); ?>
                            </p>
                        </div>

                        <span class="inline-flex items-center gap-2 font-mono font-medium uppercase tracking-widest text-caption text-blue-500">
                            <?php esc_html_e('Open', 'standard'); ?>
                            <span class="group-hover:translate-x-0.5 transition-transform duration-200" aria-hidden="true">
                                <?php icon('arrow-right', ['class' => 'w-4 h-4']); ?>
                            </span>
                        </span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>

    </div>
</section>
