<?php
/**
 * Accessories Page — Owner Resources Strip
 *
 * 3-column hairline strip linking to manuals, the riser-kit install
 * video, and the support contact. Hover affordance matches the bucket
 * cards above: heading shifts color, no background fill.
 *
 * @package Standard
 *
 * @usage Accessories Page (page-accessories.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\AccessoriesData\get_owner_resources;

$resources = get_owner_resources();
$total     = count($resources);
?>

<section class="bg-blue-50 border-y border-blue-200" aria-labelledby="owner-resources-title">
    <div class="container py-12 md:py-16">

        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-2 border-b border-blue-200 pb-4">
            <h2 id="owner-resources-title" class="font-mono text-xs md:text-sm font-medium uppercase tracking-wider text-blue-500">
                <?php esc_html_e('Owner Resources', 'standard'); ?>
            </h2>
            <p class="font-sans text-blue-600 text-sm">
                <?php esc_html_e('Already running an NTM machine?', 'standard'); ?>
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3">
            <?php foreach ($resources as $idx => $resource) :
                $is_last = ($idx === $total - 1);
                $border  = 'border-b border-blue-200';
                $border .= $is_last ? '' : ' md:border-b-0 md:border-r';
            ?>
                <a href="<?php echo esc_url(\Standard\Url\internal($resource['url'])); ?>" class="<?php echo esc_attr($border); ?> category-card py-8 md:py-10 md:px-8 grid gap-3 content-start no-underline">
                    <p class="font-mono text-xs uppercase tracking-wider text-blue-500">
                        <?php echo esc_html($resource['eyebrow']); ?>
                    </p>
                    <h3 class="category-card__title font-sans font-medium text-blue-900 text-lg md:text-xl tracking-tight">
                        <?php echo esc_html($resource['label']); ?>
                    </h3>
                    <p class="font-sans text-blue-600 text-sm max-w-prose">
                        <?php echo esc_html($resource['description']); ?>
                    </p>
                </a>
            <?php endforeach; ?>
        </div>

    </div>
</section>
