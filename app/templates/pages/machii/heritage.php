<?php
/**
 * MACH II Family — Heritage
 *
 * Light two-column section. Image left, copy right. The copy is the
 * leadership receipt for the hero's heritage claim: 1991 SSP, 1994
 * MACH II, polyurethane drive rollers invented here. Three dated
 * proof rows under the body carry the boast.
 *
 * Voice references PRODUCT.md "Leadership as fact, not slogan" and
 * pulls the origin language verbatim from templates/parts/about/
 * origin.php. The lockup deliberately leaves space around the seam
 * borders so it reads as a spec sheet, not a marketing block.
 *
 * @package Standard
 *
 * @usage MACH II Family (page-machii.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$image     = content_url('/uploads/2022/08/20220811_NTM_MACH-II-5-6-Specs_Featured-Image.jpg');
$image_alt = __('NTM MACH II 5"/6" seamless gutter machine, full-machine spec view', 'standard');

$receipts = [
    [
        'year'  => '1991',
        'event' => __('SSP', 'standard'),
        'note'  => __('Invented the portable rollformed roof panel category.', 'standard'),
    ],
    [
        'year'  => '1994',
        'event' => __('MACH II', 'standard'),
        'note'  => __('Did the same for portable seamless gutter machines.', 'standard'),
    ],
];
?>

<section class="section bg-white border-t border-blue-200" aria-labelledby="machii-heritage-title">
    <div class="container">
        <div class="grid gap-10 md:grid-cols-2 md:gap-12 lg:gap-16 md:items-center">

            <div>
                <img
                    src="<?php echo esc_url($image); ?>"
                    alt="<?php echo esc_attr($image_alt); ?>"
                    class="w-full aspect-[4/3] object-cover"
                    loading="lazy"
                >
            </div>

            <div class="grid gap-8 content-start">
                <div class="section-header-left">
                    <p class="section-eyebrow">
                        <?php esc_html_e('Since 1994', 'standard'); ?>
                    </p>
                    <div class="section-divider"></div>
                    <h2 id="machii-heritage-title" class="section-title">
                        <?php esc_html_e('First the SSP. Then the MACH II. The category started here.', 'standard'); ?>
                    </h2>
                </div>

                <p class="section-subtitle text-blue-600 max-w-xl">
                    <?php esc_html_e('The SSP Roof Panel Machine shipped in 1991. Three years later the MACH II Seamless Gutter Machine did the same for gutters: polyurethane drive rollers, easy-cut shears, 5" K-style at 50 feet per minute from raw coil. The MACH II line has been the industry benchmark for portable seamless gutter machines for over 30 years, and the contractors who bought one in the 90s are still running them.', 'standard'); ?>
                </p>

                <dl class="grid divide-y divide-blue-200 border-t border-blue-200">
                    <?php foreach ($receipts as $row) : ?>
                        <div class="grid grid-cols-[auto_1fr] gap-x-8 gap-y-1 py-4 sm:grid-cols-[6rem_8rem_1fr] sm:items-baseline">
                            <dt class="font-mono text-sm font-medium text-blue-500 tracking-wider uppercase">
                                <?php echo esc_html($row['year']); ?>
                            </dt>
                            <dd class="font-mono text-sm font-medium text-blue-900 uppercase tracking-wider col-start-1 sm:col-start-2">
                                <?php echo esc_html($row['event']); ?>
                            </dd>
                            <dd class="text-blue-600 col-span-2 sm:col-span-1 sm:col-start-3">
                                <?php echo esc_html($row['note']); ?>
                            </dd>
                        </div>
                    <?php endforeach; ?>
                </dl>
            </div>

        </div>
    </div>
</section>
