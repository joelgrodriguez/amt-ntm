<?php
/**
 * Accessories Page — Fit-by-Machine Matrix
 *
 * "I own an SSQ3 / MACH II / SSH. What fits?" Hairline grid table:
 * rows = bucket categories, columns = machine families, cells = product counts.
 *
 * Cell counts are derived from product_tag membership (see
 * inc/woo/accessory-tag-map.php for the source-of-truth tag system).
 *
 * @package Standard
 *
 * @usage Accessories Page (page-accessories.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\AccessoriesData\get_fitment_machines;
use function Standard\AccessoriesData\get_fitment_rows;
use function Standard\AccessoriesData\get_fitment_matrix;

$machines = get_fitment_machines();
$rows     = get_fitment_rows();
$matrix   = get_fitment_matrix();
?>

<section class="section" aria-labelledby="fit-by-machine-title">
    <div class="container section-content">

        <div class="section-header">
            <p class="section-eyebrow"><?php esc_html_e('Fitment', 'standard'); ?></p>
            <div class="section-divider-center"></div>
            <h2 id="fit-by-machine-title" class="section-title">
                <?php esc_html_e('What Fits Your Machine', 'standard'); ?>
            </h2>
            <p class="section-subtitle-centered">
                <?php esc_html_e('Counts show how many accessories can ship with each machine.', 'standard'); ?>
                <br class="hidden md:inline">
                <?php esc_html_e('Click any cell to jump to that group.', 'standard'); ?>
            </p>
        </div>
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full border-collapse border border-blue-200 font-mono text-sm">
                <thead>
                    <tr>
                        <th class="bg-blue-800 text-white py-4 px-5 text-left font-medium border-r border-blue-700">
                            <?php esc_html_e('Category', 'standard'); ?>
                        </th>
                        <?php foreach ($machines as $machine) : ?>
                            <th class="bg-blue-800 text-white py-4 px-3 text-center font-medium border-r border-blue-700 last:border-r-0">
                                <a href="<?php echo esc_url(\Standard\Url\internal($machine['url'])); ?>" class="text-white no-underline hover:text-blue-200">
                                    <?php echo esc_html($machine['label']); ?>
                                </a>
                            </th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows as $row_idx => $row) :
                        $row_bg = $row_idx % 2 === 0 ? 'bg-white' : 'bg-blue-50';
                    ?>
                        <tr class="border-b border-blue-200 <?php echo esc_attr($row_bg); ?>">
                            <th scope="row" class="py-4 px-5 text-left font-medium text-blue-700 border-r border-blue-200 bg-blue-100">
                                <a href="#catalog-<?php echo esc_attr($row['id']); ?>" class="text-blue-700 no-underline hover:text-blue-500">
                                    <?php echo esc_html($row['label']); ?>
                                </a>
                            </th>
                            <?php foreach ($machines as $machine) :
                                $count = $matrix[$row['id']][$machine['slug']] ?? 0;
                                $empty = $count === 0;
                            ?>
                                <td class="py-4 px-3 text-center border-r border-blue-200 last:border-r-0 <?php echo $empty ? 'text-blue-300' : 'text-blue-700 font-medium'; ?>">
                                    <?php if ($empty) : ?>
                                        &mdash;
                                    <?php else : ?>
                                        <a href="#catalog-<?php echo esc_attr($row['id']); ?>" class="no-underline text-blue-700 hover:text-blue-500">
                                            <?php echo esc_html((string) $count); ?>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="grid gap-4 md:hidden">
            <?php foreach ($machines as $machine) : ?>
                <div class="border border-blue-200 bg-white">
                    <div class="bg-blue-800 px-4 py-3">
                        <a href="<?php echo esc_url(\Standard\Url\internal($machine['url'])); ?>" class="font-mono font-medium text-white no-underline">
                            <?php echo esc_html($machine['label']); ?>
                        </a>
                    </div>
                    <dl class="divide-y divide-blue-200">
                        <?php foreach ($rows as $row) :
                            $count = $matrix[$row['id']][$machine['slug']] ?? 0;
                            $empty = $count === 0;
                        ?>
                            <div class="flex justify-between items-center px-4 py-3 font-mono text-sm">
                                <dt class="text-blue-700">
                                    <?php echo esc_html($row['label']); ?>
                                </dt>
                                <dd>
                                    <?php if ($empty) : ?>
                                        <span class="text-blue-300">&mdash;</span>
                                    <?php else : ?>
                                        <a href="#catalog-<?php echo esc_attr($row['id']); ?>" class="text-blue-500 font-medium no-underline">
                                            <?php
                                            /* translators: %d: number of products fitting this machine */
                                            printf(esc_html__('%d items', 'standard'), $count);
                                            ?>
                                        </a>
                                    <?php endif; ?>
                                </dd>
                            </div>
                        <?php endforeach; ?>
                    </dl>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>
