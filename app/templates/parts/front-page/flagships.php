<?php
/**
 * Flagships Section — Front Page
 *
 * Single product band calling out the roofing flagship (SSQ3). The
 * gutter flagship (MACH II Combo) was removed from this section — it
 * lives in the hero slider and on the category landing pages. The
 * front-page surface this section serves is roof-buyer momentum, not
 * two-product equal-weight.
 *
 * Why this composition: the rest of the page is largely sections that
 * describe the *funnel* (router, tools, three-step, why-own, social-proof).
 * This is the page's second moment of full product gravity after the hero
 * slider — a real machine, real specs, single CTA into the deep product
 * page.
 *
 * Headline + CTA are local to this file (overrides the data-file
 * slogan); `lede` is short and AEO-tuned so the SSQ3 product page
 * keeps keyword authority for the long copy.
 *
 * @package Standard
 *
 * @usage Front Page (front-page.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\MachineProductData\get_machine_product_data;

// Per-flagship overrides. `image_key` picks between the data file's
// `image` (product/action shot) and `hero_image` (alt shot). `headline`
// overrides the data file's slogan when the front-page voice needs to
// differ from the machine page's. `cta_url` points to the deep product
// page so SEO/AEO weight flows there.
$flagships = [
    [
        'data_slug'    => 'ssq3-multipro',
        'model_label'  => 'SSQ3 MultiPro',
        'image_align'  => 'left',
        'image_key'    => 'image', // ntm-ssq3-manual-controller-050 control panel macro
        'badge'        => __('Flagship', 'standard'),
        'headline'     => __('Meet the SSQ3™ MultiPro: The Next Generation of Portable Rollforming', 'standard'),
        'lede'         => __('The SSQ3 MultiPro is NTM\'s most advanced portable roof and wall panel machine: 16 panel profiles, gas or electric power, on-board RFID profile recognition, and 25-minute tooling changeovers.', 'standard'),
        'cta_label'    => __('Explore the SSQ3', 'standard'),
        'cta_url'      => '/machines/roof-wall-panel-machines/ssq3-multipro/',
    ],
];

$rendered_count = 0;
?>

<section class="bg-white" aria-labelledby="flagships-title">
    <h2 id="flagships-title" class="sr-only">
        <?php esc_html_e('Our flagship machines', 'standard'); ?>
    </h2>

    <?php foreach ($flagships as $i => $flagship) :
        $data = get_machine_product_data($flagship['data_slug']);
        if (!$data) {
            continue;
        }

        $category   = $data['category'] ?? '';
        // Headline: front-page override beats the data file's slogan.
        $headline   = $flagship['headline'] ?? $data['hero']['headline'] ?? $data['slogan'] ?? '';
        $lede       = $flagship['lede'] ?? '';
        $image_key  = $flagship['image_key'] ?? 'image';
        $hero_image = $data['hero'][$image_key] ?? $data['hero']['image'] ?? $data['hero']['hero_image'] ?? '';
        $stats      = array_slice($data['stats'] ?? [], 0, 3);

        $cta_label = $flagship['cta_label'] ?? __('Explore', 'standard');
        $cta_url   = $flagship['cta_url']
            ?? \Standard\Url\with_query('/build-finance/', ['machine' => $flagship['data_slug']]);

        $image_first_on_lg = $flagship['image_align'] === 'left';
        $rendered_count++;
        $is_first = $rendered_count === 1;
    ?>
    <div class="<?php echo $is_first ? '' : 'border-t border-blue-200'; ?>">
        <div class="container">
            <div class="grid gap-10 py-16 lg:grid-cols-2 lg:gap-16 lg:py-24 lg:items-center">

                <!-- Image cell (16:9 action photo + optional badge overlay
                     + spec strip beneath) -->
                <div class="grid gap-4 <?php echo $image_first_on_lg ? 'lg:order-1' : 'lg:order-2'; ?>">
                    <?php if ($hero_image) : ?>
                        <div class="relative aspect-video overflow-hidden">
                            <?php \Standard\Images\responsive_image($hero_image, $data['hero']['headline'] ?? '', 'large', [
                                'class'   => 'w-full h-full object-cover block',
                                'loading' => 'lazy',
                            ]); ?>

                            <?php if (!empty($flagship['badge'])) : ?>
                                <!-- Badge: pinned top-left over the photo so
                                     the content column is free of secondary
                                     chrome. text-blue-50 (tinted near-white)
                                     reads as on-image without violating the
                                     pure-white ban. -->
                                <span class="absolute top-0 left-0 inline-flex items-center px-3 py-2 bg-red text-blue-50 font-mono uppercase tracking-wider text-xs font-medium">
                                    <?php echo esc_html($flagship['badge']); ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Spec strip: 3 mono spec cells under the image, hairline dividers -->
                    <?php if (!empty($stats)) : ?>
                        <dl class="grid grid-cols-3 border-y border-blue-200" aria-label="<?php echo esc_attr(sprintf(__('%s key specs', 'standard'), $flagship['model_label'])); ?>">
                            <?php foreach ($stats as $j => $stat) : ?>
                                <div class="py-3 px-3 <?php echo $j > 0 ? 'border-l border-blue-200' : ''; ?> <?php echo $j === 0 ? 'pl-0' : ''; ?>">
                                    <dt class="font-mono uppercase tracking-wider text-[10px] text-blue-400 mb-1">
                                        <?php echo esc_html($stat['label']); ?>
                                    </dt>
                                    <dd class="font-mono font-medium text-blue-900 text-sm lg:text-base">
                                        <?php echo esc_html($stat['value']); ?>
                                    </dd>
                                </div>
                            <?php endforeach; ?>
                        </dl>
                    <?php endif; ?>
                </div>

                <!-- Content cell -->
                <div class="grid gap-6 lg:gap-8 content-start <?php echo $image_first_on_lg ? 'lg:order-2' : 'lg:order-1'; ?>">

                    <!-- Eyebrow: red dot + category. (Badge moved onto the image.) -->
                    <div class="flex items-center gap-3 flex-wrap">
                        <span class="w-2 h-2 bg-red shrink-0" aria-hidden="true"></span>
                        <p class="font-mono uppercase tracking-wider text-xs text-blue-700">
                            <?php echo esc_html($category); ?>
                        </p>
                    </div>

                    <!-- Headline. Allow <br> with class attr so the data
                         layer can insert responsive line breaks. -->
                    <h3 class="font-sans font-medium text-blue-900 tracking-tight leading-tight text-3xl md:text-4xl lg:text-5xl">
                        <?php echo wp_kses($headline, ['br' => ['class' => []]]); ?>
                    </h3>

                    <?php if ($lede) : ?>
                        <p class="font-sans text-blue-600 text-base lg:text-lg max-w-xl leading-relaxed">
                            <?php echo esc_html($lede); ?>
                        </p>
                    <?php endif; ?>

                    <!-- Single primary CTA into the deep product page -->
                    <div class="flex -mt-2 lg:-mt-4">
                        <a
                            href="<?php echo esc_url(\Standard\Url\internal($cta_url)); ?>"
                            class="btn btn-primary"
                        >
                            <?php echo esc_html($cta_label); ?>
                            <?php icon('arrow-right', ['class' => 'w-4 h-4']); ?>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <?php endforeach; ?>
</section>
