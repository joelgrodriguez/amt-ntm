<?php
/**
 * MACH II Family — Workflow
 *
 * Light section. Three steps: Load → Roll → Install. Reel-loading,
 * polyurethane drive rollers, installed gutter. Each step is a grid
 * cell with a mono ordinal label, a 16:9 photo, a short headline,
 * and one line of body copy. Hairline borders between cells act as
 * the blueprint structure.
 *
 * @package Standard
 *
 * @usage MACH II Family (page-machii.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$steps = [
    [
        'ordinal' => '01',
        'label'   => __('Load', 'standard'),
        'title'   => __('Mount the coil.', 'standard'),
        'text'    => __('Steel, aluminum, or copper coil mounts on the rotatable reel stand. One coil yields hundreds of feet of seamless gutter, no factory order, no shipping wait.', 'standard'),
        'image'   => content_url('/uploads/2019/01/mach-ll-gutter-machine-turnstile-and-reel-installation.png'),
        'alt'     => __('NTM MACH II turnstile and reel installation, coil ready to feed', 'standard'),
    ],
    [
        'ordinal' => '02',
        'label'   => __('Roll', 'standard'),
        'title'   => __('Polyurethane drive, stainless forming.', 'standard'),
        'text'    => __('Polyurethane drive rollers (NTM\'s 1994 invention, now industry standard) feed coil through stainless forming rollers at up to 50 feet per minute. Forward-pulling easy-cut shear handles the finish cut.', 'standard'),
        'image'   => content_url('/uploads/2022/01/clean-drive-rollers.jpg'),
        'alt'     => __('Clean polyurethane drive rollers inside an NTM MACH II gutter machine', 'standard'),
    ],
    [
        'ordinal' => '03',
        'label'   => __('Install', 'standard'),
        'title'   => __('Continuous run, on-site.', 'standard'),
        'text'    => __('Finished gutter rolls out of the machine ready to install in one continuous piece. No factory seams, no transport damage, no panels to reorder.', 'standard'),
        'image'   => content_url('/uploads/2026/05/ntm-mach2-gutter-install-abel-003.jpg'),
        'alt'     => __('Abel Cisneros installing a continuous seamless gutter run from a MACH II machine', 'standard'),
    ],
];
?>

<section class="section bg-white border-t border-blue-200" aria-labelledby="machii-workflow-title">
    <div class="container section-content">
        <div class="section-header-left">
            <p class="section-eyebrow">
                <?php esc_html_e('How It Works', 'standard'); ?>
            </p>
            <div class="section-divider"></div>
            <h2 id="machii-workflow-title" class="section-title">
                <?php esc_html_e('From coil to install. No bottlenecks.', 'standard'); ?>
            </h2>
            <p class="section-subtitle text-blue-600 max-w-2xl">
                <?php esc_html_e('On-site fabrication is the whole point of a portable rollformer. The MACH II turns a coil of metal into a continuous seamless gutter run, on the truck, on the jobsite, in the time it used to take to wait for a delivery.', 'standard'); ?>
            </p>
        </div>

        <ol class="grid border border-blue-200 lg:grid-cols-3" role="list">
            <?php foreach ($steps as $i => $step) :
                $is_last = $i === count($steps) - 1;
            ?>
                <li class="grid content-between bg-blue-50 <?php echo $is_last ? '' : 'border-b border-blue-200 lg:border-b-0 lg:border-r'; ?>">
                    <div class="aspect-video overflow-hidden bg-blue-100">
                        <img
                            src="<?php echo esc_url($step['image']); ?>"
                            alt="<?php echo esc_attr($step['alt']); ?>"
                            class="w-full h-full object-cover"
                            loading="lazy"
                        >
                    </div>
                    <div class="grid gap-3 p-6 lg:p-8 content-start">
                        <p class="font-mono text-xs uppercase tracking-[0.18em] text-blue-500">
                            <span class="text-blue-400 mr-2"><?php echo esc_html($step['ordinal']); ?></span>
                            <?php echo esc_html($step['label']); ?>
                        </p>
                        <h3 class="font-sans font-medium tracking-tight text-blue-900 text-xl lg:text-2xl">
                            <?php echo esc_html($step['title']); ?>
                        </h3>
                        <p class="text-blue-600">
                            <?php echo esc_html($step['text']); ?>
                        </p>
                    </div>
                </li>
            <?php endforeach; ?>
        </ol>
    </div>
</section>
