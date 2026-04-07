<?php
/**
 * Machine Product — Specifications Accordion
 *
 * @package Standard
 * @var array{product: \WC_Product, machine: array} $args
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$machine   = $args['machine'] ?? [];
$specs     = $machine['specs'] ?? null;
$resources = $machine['resources'] ?? [];

if (!$specs) {
    return;
}

// Build sections dynamically — only include sections with data.
$sections = [];

// 1. Standard Features
if (!empty($specs['standard_features'])) {
    ob_start(); ?>
    <ul class="spec-list text-slate-700">
        <?php foreach ($specs['standard_features'] as $feature) : ?>
            <li><?php echo esc_html($feature); ?></li>
        <?php endforeach; ?>
    </ul>
    <?php $sections['Standard Features'] = ob_get_clean();
}

// 2. Machine Dimensions
$dim_machine = $specs['dimensions']['machine'] ?? [];
$dim_trailer = $specs['dimensions']['on_trailer'] ?? [];
if (!empty($dim_machine) || !empty($dim_trailer)) {
    ob_start(); ?>
    <?php if (!empty($dim_machine)) : ?>
        <h4 class="text-sm font-semibold text-slate-900 mb-3">Machine</h4>
        <dl class="grid grid-cols-2 md:grid-cols-3 gap-x-8 gap-y-2 mb-6">
            <?php foreach ($dim_machine as $key => $val) : ?>
                <div>
                    <dt class="text-xs text-slate-500 uppercase tracking-wider"><?php echo esc_html(ucwords(str_replace('_', ' ', $key))); ?></dt>
                    <dd class="text-sm font-semibold text-slate-900"><?php echo esc_html($val); ?></dd>
                </div>
            <?php endforeach; ?>
        </dl>
    <?php endif; ?>
    <?php if (!empty($dim_trailer)) : ?>
        <h4 class="text-sm font-semibold text-slate-900 mb-3">On Trailer</h4>
        <dl class="grid grid-cols-2 md:grid-cols-3 gap-x-8 gap-y-2">
            <?php foreach ($dim_trailer as $key => $val) : ?>
                <div>
                    <dt class="text-xs text-slate-500 uppercase tracking-wider"><?php echo esc_html(ucwords(str_replace('_', ' ', $key))); ?></dt>
                    <dd class="text-sm font-semibold text-slate-900"><?php echo esc_html($val); ?></dd>
                </div>
            <?php endforeach; ?>
        </dl>
    <?php endif; ?>
    <?php $sections['Machine Dimensions'] = ob_get_clean();
}

// 3. Performance Specs
$perf = $specs['performance'] ?? [];
if (!empty($perf)) {
    ob_start();
    $shear = $perf['shear'] ?? [];
    $drive = $perf['drive'] ?? [];
    $speed = $perf['speed'] ?? [];
    ?>
    <?php if (!empty($shear)) : ?>
        <h4 class="text-sm font-semibold text-slate-900 mb-2">Shear System</h4>
        <?php if (!empty($shear['type'])) : ?>
            <p class="text-sm text-slate-600 mb-1"><?php echo esc_html($shear['type']); ?></p>
        <?php endif; ?>
        <?php if (!empty($shear['details'])) : ?>
            <ul class="spec-list text-slate-700 mb-4">
                <?php foreach ($shear['details'] as $detail) : ?>
                    <li><?php echo esc_html($detail); ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    <?php endif; ?>
    <?php if (!empty($drive)) : ?>
        <h4 class="text-sm font-semibold text-slate-900 mb-2">Drive System</h4>
        <?php if (!empty($drive['type'])) : ?>
            <p class="text-sm text-slate-600 mb-1"><?php echo esc_html($drive['type']); ?></p>
        <?php endif; ?>
        <?php if (!empty($drive['details'])) : ?>
            <ul class="spec-list text-slate-700 mb-4">
                <?php foreach ($drive['details'] as $detail) : ?>
                    <li><?php echo esc_html($detail); ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    <?php endif; ?>
    <?php if (!empty($speed)) : ?>
        <h4 class="text-sm font-semibold text-slate-900 mb-2">Production Speed</h4>
        <dl class="grid grid-cols-2 gap-x-8 gap-y-2">
            <?php foreach ($speed as $s) : ?>
                <div>
                    <dt class="text-xs text-slate-500 uppercase tracking-wider"><?php echo esc_html($s['source']); ?></dt>
                    <dd class="text-sm font-semibold text-slate-900"><?php echo esc_html($s['rate']); ?></dd>
                </div>
            <?php endforeach; ?>
        </dl>
    <?php endif; ?>
    <?php $sections['Performance Specs'] = ob_get_clean();
}

// 4. Materials Formed
$materials = $specs['materials'] ?? [];
if (!empty($materials)) {
    ob_start(); ?>
    <div class="grid gap-4">
        <?php foreach ($materials as $mat) : ?>
            <div>
                <h4 class="text-sm font-semibold text-slate-900"><?php echo esc_html($mat['name'] ?? ''); ?></h4>
                <?php if (!empty($mat['gauge'])) : ?>
                    <p class="text-sm text-slate-600"><?php echo esc_html($mat['gauge']); ?></p>
                <?php endif; ?>
                <?php if (!empty($mat['note'])) : ?>
                    <p class="text-xs text-slate-500 italic mt-1"><?php echo esc_html($mat['note']); ?></p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
    <?php $sections['Materials Formed'] = ob_get_clean();
}

// 5. Coil Specifications
$coil = $specs['coil'] ?? [];
if (!empty($coil)) {
    $coil_items = [];
    if (!empty($coil['widths']))              { $coil_items['Coil Widths']              = $coil['widths']; }
    if (!empty($coil['finished_widths']))      { $coil_items['Finished Panel Widths']    = $coil['finished_widths']; }
    if (!empty($coil['max_diameter_rack']))    { $coil_items['Max Coil Diameter Rack']   = $coil['max_diameter_rack']; }
    if (!empty($coil['max_diameter_decoil']))  { $coil_items['Max Coil Diameter Decoiler'] = $coil['max_diameter_decoil']; }
    if (!empty($coil['max_weight_reel']))      { $coil_items['Max Weight Reel']          = $coil['max_weight_reel']; }
    if (!empty($coil['max_weight_cradle']))    { $coil_items['Max Weight Cradle']        = $coil['max_weight_cradle']; }

    if (!empty($coil_items)) {
        ob_start(); ?>
        <dl class="grid grid-cols-2 gap-x-8 gap-y-2">
            <?php foreach ($coil_items as $label => $val) : ?>
                <div>
                    <dt class="text-xs text-slate-500 uppercase tracking-wider"><?php echo esc_html($label); ?></dt>
                    <dd class="text-sm font-semibold text-slate-900"><?php echo esc_html($val); ?></dd>
                </div>
            <?php endforeach; ?>
        </dl>
        <?php $sections['Coil Specifications'] = ob_get_clean();
    }
}

// 6. Power Options
$power = $specs['power_options'] ?? [];
if (!empty($power)) {
    ob_start(); ?>
    <ul class="spec-list text-slate-700">
        <?php foreach ($power as $option) : ?>
            <li><?php echo esc_html($option); ?></li>
        <?php endforeach; ?>
    </ul>
    <?php $sections['Power Options'] = ob_get_clean();
}

// 7. Add-On Weights
$weights = $specs['add_on_weights'] ?? [];
if (!empty($weights)) {
    ob_start(); ?>
    <dl class="grid grid-cols-2 gap-x-8 gap-y-2">
        <?php foreach ($weights as $w) : ?>
            <div>
                <dt class="text-xs text-slate-500 uppercase tracking-wider"><?php echo esc_html($w['item']); ?></dt>
                <dd class="text-sm font-semibold text-slate-900"><?php echo esc_html($w['weight']); ?></dd>
            </div>
        <?php endforeach; ?>
    </dl>
    <?php $sections['Add-On Weights'] = ob_get_clean();
}

// 8. Warranty & Patents
$warranty = $specs['warranty'] ?? [];
if (!empty($warranty)) {
    ob_start(); ?>
    <?php if (!empty($warranty['description'])) : ?>
        <p class="text-sm text-slate-700 mb-3"><?php echo esc_html($warranty['description']); ?></p>
    <?php endif; ?>
    <?php if (!empty($warranty['patents'])) : ?>
        <ul class="grid gap-1">
            <?php foreach ($warranty['patents'] as $patent) : ?>
                <li class="text-sm text-slate-600"><?php echo esc_html($patent); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <?php $sections['Warranty & Patents'] = ob_get_clean();
}

if (empty($sections)) {
    return;
}
?>

<section id="machine-specs" class="section bg-slate-50" aria-labelledby="specs-title">
    <div class="container section-content">

        <div class="grid lg:grid-cols-2 gap-12 items-stretch">

            <!-- Left column: header + accordions -->
            <div>
                <div class="section-header-left mb-12">
                    <p class="section-eyebrow"><?php esc_html_e('Technical Specifications', 'standard'); ?></p>
                    <div class="section-divider"></div>
                    <h2 id="specs-title" class="section-title"><?php esc_html_e('Full Details', 'standard'); ?></h2>
                </div>

                <div data-accordion-group>
                    <?php foreach ($sections as $title => $content) : ?>
                        <details class="accordion">
                            <summary>
                                <?php echo esc_html($title); ?>
                                <span class="accordion__icon"><?php icon('chevron-down', ['class' => 'w-4 h-4']); ?></span>
                            </summary>
                            <div class="accordion__body text-sm text-slate-600">
                                <?php echo $content; // Already escaped during build. ?>
                            </div>
                        </details>
                    <?php endforeach; ?>

                    <?php if (!empty($resources['brochure'])) : ?>
                        <div class="mt-6">
                            <a href="<?php echo esc_url($resources['brochure']); ?>" class="btn btn-sm btn-outline-dark" target="_blank" rel="noopener"><?php esc_html_e('Download Full Spec Sheet', 'standard'); ?></a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Right column: vertical machine image — fills full height of container -->
            <div class="hidden lg:block">
                <div class="bg-slate-100 rounded overflow-hidden h-full flex items-center justify-center">
                    <div class="text-center grid gap-4">
                        <svg class="w-16 h-16 text-slate-300 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0022.5 18.75V5.25A2.25 2.25 0 0020.25 3H3.75A2.25 2.25 0 001.5 5.25v13.5A2.25 2.25 0 003.75 21z" />
                        </svg>
                        <span class="text-slate-400 text-sm font-mono"><?php esc_html_e('Machine image', 'standard'); ?></span>
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>
