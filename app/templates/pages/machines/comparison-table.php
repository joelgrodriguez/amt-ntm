<?php
/**
 * Machines Page — Comparison Table
 *
 * Responsive machine specs comparison table.
 * Single table at every breakpoint; horizontally scrolls on mobile
 * (the page is too dense for a stacked card variant). Dark header,
 * hairline rows, flagship column tinted.
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

<section class="section border-t border-blue-200" aria-labelledby="comparison-title">
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

        <!-- Single table at every breakpoint; horizontal scroll on narrow viewports. -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse border border-blue-200 min-w-[720px]" aria-labelledby="comparison-title">
                <caption class="sr-only"><?php echo esc_html($content['title']); ?></caption>
                <thead>
                    <tr>
                        <th class="bg-blue-800 text-white py-4 px-5 text-left font-medium text-base border-r border-blue-700 sticky left-0 z-10">
                            <?php esc_html_e('Machine', 'standard'); ?>
                        </th>
                        <?php foreach ($machines as $machine) :
                            $is_flagship = !empty($machine['badge']);
                        ?>
                            <th class="<?php echo $is_flagship ? 'bg-blue-500' : 'bg-blue-800'; ?> text-white py-4 px-4 text-center border-r border-blue-700 min-w-[140px]">
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
                <tbody>
                    <?php foreach ($rows as $key => $label) : ?>
                        <tr class="border-b border-blue-200">
                            <td class="py-3 px-5 font-medium text-blue-800 border-r border-blue-200 sticky left-0 bg-white z-10">
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
                    <tr>
                        <td class="py-4 px-5 border-r border-blue-200 sticky left-0 bg-white z-10"></td>
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
