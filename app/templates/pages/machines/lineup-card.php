<?php
/**
 * Machines Page — Lineup Card
 *
 * Individual machine card for the lineup grid. Image + name, whole
 * card is the tap target for the machine's spec page. No secondary
 * CTA — the spec page is the single destination so keyboard users get
 * one stop per card and the hover affordance reads as a real link.
 *
 * @package Standard
 *
 * @usage Via get_template_part() from lineup-grid.php
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$machine = $args['machine'] ?? null;

if (!$machine) {
    return;
}
?>

<div class="bg-white flex flex-col h-full relative group hover:bg-blue-50 transition-colors duration-150">
    <!-- Product Image -->
    <div class="p-4 sm:p-6 flex items-center justify-center aspect-4/3">
        <?php \Standard\Images\responsive_image($machine['image'], $machine['name'], 'product-card', [
            'class' => 'max-h-full w-auto object-contain',
        ]); ?>
    </div>

    <!-- Content. Single card-wide link via the name overlay. -->
    <div class="p-4 sm:p-6 flex flex-col grow gap-4">
        <h4 class="text-2xl font-medium text-blue-900 tracking-tight group-hover:text-blue-500 transition-colors duration-150">
            <a href="<?php echo esc_url(\Standard\Url\internal($machine['url'])); ?>" class="after:absolute after:inset-0 no-underline text-inherit focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2">
                <?php echo esc_html($machine['name']); ?>
            </a>
        </h4>
    </div>
</div>
