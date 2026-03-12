<?php
/**
 * Machines Page — Comparison Table
 *
 * Responsive machine specs comparison table.
 * Horizontal scroll on mobile, full table on desktop.
 *
 * @package Standard
 *
 * @usage Machines Page (page-machines.php)
 */

declare(strict_types=1);

use function Standard\MachinesData\get_all_machines;

$content = [
    'eyebrow' => __('Compare', 'standard'),
    'title'   => __('Machine Comparison', 'standard'),
];

$machines = get_all_machines();

$columns = [
    'name'     => __('Machine', 'standard'),
    'profiles' => __('Profiles', 'standard'),
    'speed'    => __('Speed', 'standard'),
    'power'    => __('Power', 'standard'),
    'shear'    => __('Shear', 'standard'),
    'best_for' => __('Best For', 'standard'),
];
?>

<section class="section" aria-labelledby="comparison-title">
    <div class="container section-content">

        <div class="section-header">
            <p class="section-eyebrow">
                <?php echo esc_html($content['eyebrow']); ?>
            </p>
            <div class="section-divider-center"></div>
            <h2 id="comparison-title" class="section-title">
                <?php echo esc_html($content['title']); ?>
            </h2>
        </div>

        <!-- Scroll hint on mobile -->
        <p class="text-xs text-slate-400 text-center mb-2 lg:hidden">
            <?php icon('arrow-right', ['class' => 'w-3 h-3 inline']); ?>
            <?php esc_html_e('Scroll to compare', 'standard'); ?>
        </p>

        <div class="overflow-x-auto -mx-4 lg:mx-0">
            <table class="w-full text-left text-sm min-w-[700px]">
                <thead>
                    <tr class="border-b-2 border-slate-300">
                        <?php $first = true; ?>
                        <?php foreach ($columns as $col) : ?>
                            <th class="py-3 px-4 font-semibold text-slate-900 whitespace-nowrap <?php echo $first ? 'sticky left-0 bg-white z-10 shadow-[2px_0_4px_-2px_rgba(0,0,0,0.1)] lg:static lg:shadow-none' : ''; ?>">
                                <?php echo esc_html($col); ?>
                            </th>
                            <?php $first = false; ?>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($machines as $machine) : ?>
                        <?php $is_flagship = !empty($machine['badge']); ?>
                        <?php $row_bg = $is_flagship ? 'bg-primary/5' : 'bg-white'; ?>
                        <tr class="border-b border-slate-200 <?php echo $is_flagship ? 'font-medium' : ''; ?>">
                            <td class="py-3 px-4 whitespace-nowrap sticky left-0 <?php echo esc_attr($row_bg); ?> z-10 shadow-[2px_0_4px_-2px_rgba(0,0,0,0.1)] lg:static lg:shadow-none">
                                <a href="<?php echo esc_url($machine['url']); ?>" class="text-primary font-semibold hover:underline">
                                    <?php echo esc_html($machine['name']); ?>
                                </a>
                                <?php if ($is_flagship) : ?>
                                    <span class="ml-2 px-1.5 py-0.5 text-xs font-semibold uppercase bg-secondary text-white">
                                        <?php esc_html_e('New', 'standard'); ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3 px-4 text-slate-600">
                                <?php echo esc_html($machine['specs']['profiles']); ?>
                            </td>
                            <td class="py-3 px-4 text-slate-600">
                                <?php echo esc_html($machine['specs']['speed']); ?>
                            </td>
                            <td class="py-3 px-4 text-slate-600">
                                <?php echo esc_html($machine['specs']['power']); ?>
                            </td>
                            <td class="py-3 px-4 text-slate-600">
                                <?php echo esc_html($machine['specs']['shear']); ?>
                            </td>
                            <td class="py-3 px-4 text-slate-600">
                                <?php echo esc_html($machine['specs']['best_for']); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </div>
</section>
