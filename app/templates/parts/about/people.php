<?php
/**
 * About — The People Behind The Machine
 *
 * Photo-led section. Four hard-cropped portraits across the disciplines
 * that touch every customer: engineering, production, service, support.
 * Captions name the role only, not the person; HR-proof, and keeps the
 * emphasis on "this is a real company with real people," not on
 * individual celebrity.
 *
 * Layout is intentionally not a uniform 4-up card grid. Two photos run
 * taller, two run wider; the page rhythm breaks here on purpose, since
 * this is the section that has to make a skeptical contractor stop
 * scrolling.
 *
 * @package Standard
 * @usage About Page (page-about.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$content = [
    'eyebrow' => __('The people behind the machine', 'standard'),
    'title'   => __('When you call NTM, the phone is answered by someone who knows the machine.', 'standard'),
    'lede'    => __('The engineers, builders, techs, and support reps are all in the same building. That\'s the whole point.', 'standard'),
];

$people = [
    [
        'image'   => 'https://newtechmachinery.com/wp-content/uploads/2026/05/ntm-team-engineer-003.jpg',
        'alt'     => __('NTM engineer at a workstation, reviewing CAD drawings of a rollformer mechanism.', 'standard'),
        'role'    => __('Engineering', 'standard'),
        'caption' => __('Designs the next machine. Answers the support call about the last one.', 'standard'),
        'ratio'   => 'aspect-[3/4]',
        'span'    => 'lg:col-span-4',
    ],
    [
        'image'   => 'https://newtechmachinery.com/wp-content/uploads/2026/05/ntm-team-production-004.jpg',
        'alt'     => __('NTM production technician on the Aurora shop floor, assembling a rollforming machine.', 'standard'),
        'role'    => __('Production', 'standard'),
        'caption' => __('Builds, tests, and crates every machine in Aurora or Hermosillo.', 'standard'),
        'ratio'   => 'aspect-[4/5]',
        'span'    => 'lg:col-span-3',
    ],
    [
        'image'   => 'https://newtechmachinery.com/wp-content/uploads/2026/05/ntm-team-service-002.jpg',
        'alt'     => __('NTM service technician inspecting a rollforming machine in the field.', 'standard'),
        'role'    => __('Service', 'standard'),
        'caption' => __('Field repairs, refurbishments, on-site training. Same uniform, same answers.', 'standard'),
        'ratio'   => 'aspect-[3/4]',
        'span'    => 'lg:col-span-3',
    ],
    [
        'image'   => 'https://newtechmachinery.com/wp-content/uploads/2026/05/ntm-team-customer-service-002.jpg',
        'alt'     => __('NTM customer service representative on a headset call with a contractor.', 'standard'),
        'role'    => __('Customer service', 'standard'),
        'caption' => __('Phones answered by people, not menus. Aurora-based, machine-trained.', 'standard'),
        'ratio'   => 'aspect-[4/5]',
        'span'    => 'lg:col-span-2',
    ],
];
?>

<section class="bg-white py-16 lg:py-24 border-t border-blue-200" aria-labelledby="about-people-title">
    <div class="container">
        <div class="grid lg:grid-cols-12 gap-10 lg:gap-16 mb-12 lg:mb-16">
            <div class="lg:col-span-8 grid gap-6 content-start">
                <p class="font-mono uppercase tracking-wider text-xs text-red">
                    <?php echo esc_html($content['eyebrow']); ?>
                </p>
                <h2 id="about-people-title" class="font-sans font-medium text-blue-900 text-2xl md:text-3xl lg:text-[2.5rem] leading-tight tracking-tight">
                    <?php echo esc_html($content['title']); ?>
                </h2>
            </div>
            <div class="lg:col-span-4 flex lg:items-end">
                <p class="font-sans text-blue-700 text-base lg:text-lg leading-relaxed">
                    <?php echo esc_html($content['lede']); ?>
                </p>
            </div>
        </div>

        <ul class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-6 lg:gap-8" role="list">
            <?php foreach ($people as $person) : ?>
                <li class="<?php echo esc_attr($person['span']); ?> grid gap-4 content-start">
                    <figure class="grid gap-4">
                        <div class="<?php echo esc_attr($person['ratio']); ?> w-full overflow-hidden bg-blue-50">
                            <?php \Standard\Images\responsive_image($person['image'], $person['alt'], 'large', [
                                'class'   => 'block w-full h-full object-cover',
                                'loading' => 'lazy',
                            ]); ?>
                        </div>
                        <figcaption class="grid gap-1">
                            <span class="font-mono uppercase tracking-wider text-xs text-red">
                                <?php echo esc_html($person['role']); ?>
                            </span>
                            <span class="font-sans text-blue-900 text-base leading-snug">
                                <?php echo esc_html($person['caption']); ?>
                            </span>
                        </figcaption>
                    </figure>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>
