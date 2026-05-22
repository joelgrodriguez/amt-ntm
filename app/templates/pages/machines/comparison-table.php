<?php
/**
 * Machines Page — Comparison Table
 *
 * Responsive machine specs comparison table.
 * Dark header, hairline rows, highlighted flagship column.
 * Stacks to card layout on mobile, full table on desktop.
 *
 * @package Standard
 *
 * @usage Machines Page (page-machines.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\MachinesData\get_all_machines;

$content = [
    'eyebrow' => __('Compare', 'standard'),
    'title'   => __('Specs, Side by Side', 'standard'),
];

$machines = get_all_machines();

$rows = [
    'profiles' => __('Profiles', 'standard'),
    'speed'    => __('Speed', 'standard'),
    'power'    => __('Power', 'standard'),
    'shear'    => __('Shear', 'standard'),
    'best_for' => __('Best For', 'standard'),
];
?>

<section class="section" aria-labelledby="comparison-title">
    <div class="container section-content">

        <div class="section-header-left">
            <p class="section-eyebrow">
                <?php echo esc_html($content['eyebrow']); ?>
            </p>
            <div class="section-divider"></div>
            <h2 id="comparison-title" class="section-title">
                <?php echo esc_html($content['title']); ?>
            </h2>
        </div>

        <!-- Mobile: Card layout -->
        <div class="grid gap-6 sm:grid-cols-2 lg:hidden">
            <?php foreach ($machines as $machine) :
                $is_flagship = !empty($machine['badge']);
            ?>
                <div class="card <?php echo $is_flagship ? 'border-blue-500' : ''; ?>">
                    <!-- Card header -->
                    <div class="<?php echo $is_flagship ? 'bg-blue-500' : 'bg-blue-800'; ?> px-4 py-4 text-center">
                        <a href="<?php echo esc_url(\Standard\Url\internal($machine['url'])); ?>" class="text-lg font-medium text-white no-underline hover:underline">
                            <?php echo esc_html($machine['short_name'] ?? $machine['name']); ?>
                        </a>
                        <?php if ($is_flagship) : ?>
                            <span class="block text-xs font-medium uppercase tracking-wider text-white/70 mt-1">
                                <?php echo esc_html($machine['badge']); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                    <!-- Card rows -->
                    <?php foreach ($rows as $key => $label) : ?>
                        <div class="flex justify-between gap-4 px-4 py-3 text-sm border-b border-blue-100 bg-white">
                            <span class="font-medium text-blue-700"><?php echo esc_html($label); ?></span>
                            <span class="text-blue-600 text-right"><?php echo esc_html($machine['specs'][$key]); ?></span>
                        </div>
                    <?php endforeach; ?>
                    <!-- Card CTA -->
                    <div class="px-4 py-4 bg-white border-t border-blue-200">
                        <a href="<?php echo esc_url(\Standard\Url\internal($machine['url'])); ?>" class="btn <?php echo $is_flagship ? 'btn-primary' : 'btn-outline-dark'; ?> btn-sm w-full justify-center">
                            <?php esc_html_e('Explore', 'standard'); ?>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Desktop: Full table -->
        <div class="hidden lg:block overflow-x-auto">
            <table class="w-full text-sm border-collapse border border-blue-200" aria-labelledby="comparison-title">
                <caption class="sr-only"><?php echo esc_html($content['title']); ?></caption>
                <!-- Header row: machine names -->
                <thead>
                    <tr>
                        <th class="bg-blue-800 text-white py-4 px-5 text-left font-medium text-base border-r border-blue-700">
                            <?php esc_html_e('Machine', 'standard'); ?>
                        </th>
                        <?php foreach ($machines as $machine) :
                            $is_flagship = !empty($machine['badge']);
                        ?>
                            <th class="<?php echo $is_flagship ? 'bg-blue-500' : 'bg-blue-800'; ?> text-white py-4 px-4 text-center border-r border-blue-700">
                                <a href="<?php echo esc_url(\Standard\Url\internal($machine['url'])); ?>" class="text-white no-underline hover:underline font-medium text-sm">
                                    <?php echo esc_html($machine['short_name'] ?? $machine['name']); ?>
                                </a>
                                <?php if ($is_flagship) : ?>
                                    <span class="block text-xs font-medium uppercase tracking-wider text-white/70 mt-0.5">
                                        <?php echo esc_html($machine['badge']); ?>
                                    </span>
                                <?php endif; ?>
                            </th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <!-- Data rows -->
                <tbody>
                    <?php foreach ($rows as $key => $label) : ?>
                        <tr class="border-b border-blue-200">
                            <td class="py-3 px-5 font-medium text-blue-800 border-r border-blue-200">
                                <?php echo esc_html($label); ?>
                            </td>
                            <?php foreach ($machines as $machine) :
                                $is_flagship = !empty($machine['badge']);
                                $value = $machine['specs'][$key] ?? '';
                            ?>
                                <td class="py-3 px-4 text-center text-blue-600 border-r border-blue-200 <?php echo $is_flagship ? 'bg-blue-500/5' : ''; ?>">
                                    <?php echo esc_html($value); ?>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                    <!-- Explore row -->
                    <tr>
                        <td class="py-4 px-5 border-r border-blue-200"></td>
                        <?php foreach ($machines as $machine) :
                            $is_flagship = !empty($machine['badge']);
                        ?>
                            <td class="py-4 px-4 text-center border-r border-blue-200 <?php echo $is_flagship ? 'bg-blue-500/5' : ''; ?>">
                                <a href="<?php echo esc_url(\Standard\Url\internal($machine['url'])); ?>" class="btn <?php echo $is_flagship ? 'btn-primary' : 'btn-outline-dark'; ?> btn-sm">
                                    <?php esc_html_e('Explore', 'standard'); ?>
                                </a>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</section>
