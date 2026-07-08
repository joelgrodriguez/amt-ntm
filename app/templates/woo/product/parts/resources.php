<?php
/**
 * Machine Product — Resources & Support
 *
 * Three-column icon card grid: one card per resource (Manual, Brochure,
 * Service & Training). Each card carries an icon, mono kicker, title, copy,
 * and an arrowed CTA, linking to the resource. Mobile-first: single column
 * at base, three columns from md up.
 *
 * @package Standard
 * @var array{machine: array} $args
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$machine   = $args['machine'] ?? [];
$resources = $machine['resources'] ?? null;

if (!$resources) {
    return;
}

$rows = [];
if (!empty($resources['manual'])) {
    $rows[] = [
        'icon'   => 'file-text',
        'kicker' => __('Manual', 'standard'),
        'title'  => __('Operator Manual', 'standard'),
        'copy'   => __('Setup, daily operation, maintenance schedule, and troubleshooting.', 'standard'),
        'cta'    => __('Open Manual', 'standard'),
        'url'    => \Standard\Url\canonical($resources['manual']),
    ];
}
if (!empty($resources['brochure'])) {
    $rows[] = [
        'icon'   => 'download',
        'kicker' => __('Brochure', 'standard'),
        'title'  => __('Product Brochure', 'standard'),
        'copy'   => __('Full spec sheet, options, configurations, and pricing reference.', 'standard'),
        'cta'    => __('Open Brochure', 'standard'),
        'url'    => \Standard\Url\canonical($resources['brochure']),
    ];
}
if (!empty($resources['service_training_url'])) {
    $rows[] = [
        'icon'   => 'life-buoy',
        'kicker' => __('Service', 'standard'),
        'title'  => __('Service & Training', 'standard'),
        'copy'   => __('Hands-on training, technical support, and replacement-parts pipeline.', 'standard'),
        'cta'    => __('Learn More', 'standard'),
        'url'    => \Standard\Url\internal($resources['service_training_url']),
    ];
}

// "How to change a profile" is one of the most common owner questions. Each
// item is a Learning Center video/article for this specific machine.
if (!empty($resources['profile_change']) && is_array($resources['profile_change'])) {
    foreach ($resources['profile_change'] as $guide) {
        if (empty($guide['url'])) {
            continue;
        }
        $is_video = str_contains((string) $guide['url'], '/video/');
        $rows[] = [
            'icon'   => $is_video ? 'video' : 'file-text',
            'kicker' => __('Profiles', 'standard'),
            'title'  => (string) ($guide['label'] ?? __('How to Change a Profile', 'standard')),
            'copy'   => __('Step-by-step Learning Center walkthrough for changing profiles on this machine.', 'standard'),
            'cta'    => $is_video ? __('Watch Now', 'standard') : __('Read Guide', 'standard'),
            'url'    => \Standard\Url\internal($guide['url']),
        ];
    }
}

if (empty($rows)) {
    return;
}
?>

<section id="machine-resources" class="resources section bg-white" aria-labelledby="resources-title" data-reveal="fade">
    <div class="container">

        <div class="section-header-left">
            <p class="section-eyebrow"><?php esc_html_e('Resources', 'standard'); ?></p>
            <div class="section-divider"></div>
            <h2 id="resources-title" class="section-title"><?php esc_html_e('Downloads & Support', 'standard'); ?></h2>
        </div>

        <ul class="mt-10 grid gap-6 md:grid-cols-3" role="list">
            <?php foreach ($rows as $row) : ?>
                <li>
                    <a
                        href="<?php echo esc_url($row['url']); ?>"
                        class="group flex h-full flex-col gap-4 border border-blue-100 bg-white p-6 transition-colors hover:border-blue-300 lg:p-8"
                        target="_blank"
                        rel="noopener"
                    >
                        <span class="flex h-12 w-12 items-center justify-center bg-blue-50 text-blue-500 transition-colors group-hover:bg-blue-100">
                            <?php icon($row['icon'], ['class' => 'w-6 h-6']); ?>
                        </span>
                        <span class="font-mono text-[11px] font-medium uppercase tracking-wider text-blue-500">
                            <?php echo esc_html($row['kicker']); ?>
                        </span>
                        <span class="text-xl font-medium text-blue-900">
                            <?php echo esc_html($row['title']); ?>
                        </span>
                        <span class="text-blue-600"><?php echo esc_html($row['copy']); ?></span>
                        <span class="mt-auto inline-flex items-center gap-1 pt-2 font-medium text-blue-700 group-hover:text-blue-900">
                            <span><?php echo esc_html($row['cta']); ?></span>
                            <?php icon('arrow-right', ['class' => 'w-4 h-4 transition-transform group-hover:translate-x-0.5']); ?>
                        </span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>

    </div>
</section>
