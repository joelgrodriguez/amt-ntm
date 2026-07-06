<?php
/**
 * Process Section — Front Page
 *
 * Image-left, three-step list right. Replaces the earlier chrome-
 * heavy version (top/bottom mono bars + side hairlines + segmented
 * indicator) with a quieter composition that lets the photo of a real
 * NTM owner do the human-scale work and the steps read as a numbered
 * list.
 *
 * The image is functional social proof, not decoration: it's the face
 * of the buyer who's been through the process. Mono caption names the
 * scene so the photo isn't anonymous.
 *
 * Bottom dual-channel info row was removed when the final-cta section
 * directly below also pushes "Talk to a Specialist" — keeping both
 * was a redundant close.
 *
 * @package Standard
 *
 * @usage Front Page (front-page.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$content = [
    'eyebrow'   => __('How You Buy', 'standard'),
    'title'     => __('Three steps to a portable rollformer of your own.', 'standard'),
    'image'     => content_url('/uploads/2026/01/JIm-and-family-with-SSQ-scaled.jpg'),
    'image_alt' => __('Jim and his family with their NTM SSQ portable rollformer', 'standard'),
    'caption'   => __('Thousands of contractors run their business on an NTM.', 'standard'),
];

// Secondary photos sit in a 2-up row below the hero photo, filling the
// vertical space the steps column would otherwise leave empty. They are
// social proof, not decoration: machine on the road, machine with coils.
$secondary_images = [
    [
        'src' => content_url('/uploads/2025/10/SSQ-on-dirt-road.jpg'),
        'alt' => __('NTM SSQ rollformer being towed down a dirt road to a job site', 'standard'),
    ],
    [
        'src' => content_url('/uploads/2025/09/SSQ-II-with-4-coils.jpg'),
        'alt' => __('NTM SSQ II loaded with four coils of metal ready to run panels', 'standard'),
    ],
];

$phases = [
    [
        'index' => '01',
        'label' => __('Explore', 'standard'),
        'title' => __('Find your machine.', 'standard'),
        'text'  => __('Browse profiles, compare throughput, read the manuals. Spend ten minutes or two weeks. The machine you pick is the one we build.', 'standard'),
    ],
    [
        'index' => '02',
        'label' => __('Build', 'standard'),
        'title' => __('Configure it, or call us.', 'standard'),
        'text'  => __('Use the configurator for a live quote, or talk to a specialist who will build it with you. Same price, same machine, your choice.', 'standard'),
    ],
    [
        'index' => '03',
        'label' => __('Finance & ship', 'standard'),
        'title' => __('Finance it. We ship it.', 'standard'),
        'text'  => __('Apply for financing in the same flow. Lead time is 6 to 10 weeks. Your crew runs panels with our team on-site week one.', 'standard'),
    ],
];
?>

<section class="section bg-white" aria-labelledby="process-title">
    <div class="container">
        <div class="grid gap-10 lg:grid-cols-2 lg:gap-16 lg:items-start">

            <!-- Image column (left). Hero photo on top (aspect-video to
                 match Flagships/Quiz rhythm), then a 2-up row of secondary
                 shots so the column matches the steps column's height
                 instead of floating in the middle with dead space. -->
            <figure class="grid gap-4 lg:order-1" data-reveal="fade">
                <div class="aspect-video overflow-hidden">
                    <?php \Standard\Images\responsive_image($content['image'], $content['image_alt'], 'large', [
                        'class'   => 'w-full h-full object-cover block',
                        'loading' => 'lazy',
                        'sizes'   => '(min-width: 1024px) 50vw, 100vw',
                    ]); ?>
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <?php foreach ($secondary_images as $img) : ?>
                        <div class="aspect-[4/3] overflow-hidden">
                            <?php \Standard\Images\responsive_image($img['src'], $img['alt'], 'large', [
                                'class'   => 'w-full h-full object-cover block',
                                'loading' => 'lazy',
                                'sizes'   => '(min-width: 1024px) 25vw, (min-width: 640px) 50vw, 100vw',
                            ]); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                <!-- Caption as quiet social proof: sans, medium weight,
                     blue-900 (full strength). Reads as a statement, not
                     a footnote. -->
                <figcaption class="font-sans font-medium text-blue-900 text-base lg:text-lg leading-snug">
                    <?php echo esc_html($content['caption']); ?>
                </figcaption>
            </figure>

            <!-- Steps column (right) -->
            <div class="grid gap-8 lg:gap-10 lg:order-2">

                <?php get_template_part('templates/parts/section-header', null, [
                    'id'        => 'process-title',
                    'eyebrow'   => $content['eyebrow'],
                    'title'     => $content['title'],
                    'max_width' => 'max-w-md',
                ]); ?>

                <ol class="stagger grid gap-6 lg:gap-8" role="list">
                    <?php foreach ($phases as $phase) : ?>
                        <li class="reveal grid gap-2">
                            <div class="flex items-baseline gap-3 font-mono uppercase tracking-wider text-xs text-blue-500">
                                <span><?php echo esc_html($phase['index']); ?></span>
                                <span class="w-6 h-px bg-blue-300" aria-hidden="true"></span>
                                <span><?php echo esc_html($phase['label']); ?></span>
                            </div>
                            <h3 class="font-sans font-medium text-blue-900 text-lg lg:text-xl leading-snug">
                                <?php echo esc_html($phase['title']); ?>
                            </h3>
                            <p class="font-sans text-blue-600 text-base leading-relaxed max-w-prose">
                                <?php echo esc_html($phase['text']); ?>
                            </p>
                        </li>
                    <?php endforeach; ?>
                </ol>
            </div>

        </div>
    </div>
</section>
