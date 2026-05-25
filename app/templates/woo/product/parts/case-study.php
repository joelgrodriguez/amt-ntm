<?php
/**
 * Machine Product — Customer Story (chrome-bar testimonial)
 *
 * Mirrors templates/parts/front-page/social-proof.php so the site reads
 * as one chrome-bar testimonial system instead of a magazine spread
 * here and a strip on the home page.
 *
 * Falls back to the same shared testimonial set used on the front page
 * when per-machine testimonials aren't authored yet.
 *
 * @package Standard
 * @var array{machine: array} $args
 *
 * @usage Single Machine Product (single-machine.php)
 * @see js/modules/SocialProof.js - Slider functionality
 * @see templates/parts/front-page/social-proof.php - Shared composition
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$machine      = $args['machine'] ?? [];
$testimonials = $machine['testimonials'] ?? [];

$cdn = 'https://newtechmachinery.com/wp-content/uploads/2025/06';

// Shared fallback set — same authored quotes used on the front page so
// the "shared testimonial" between surfaces is intentional, not coincidence.
if (empty($testimonials)) {
    $testimonials = [
        [
            'quote'    => __('If you’re trying to jump into the metal business, contact New Tech Machinery. They’re the best! They’re going to give you the information that you need, and they’re going to help you grow your business.', 'standard'),
            'name'     => 'Danaik Garay',
            'company'  => 'Alsteel Metal Manufacturing',
            'location' => 'Fort Myers, Florida',
            'slug'     => 'Danaik-1',
        ],
        [
            'quote'    => __('What attracted me to New Tech Machinery was the quality of the machine and ease of switching out dies. If you do have a problem, there’s a sales team that you can call.', 'standard'),
            'name'     => 'Todd Andrews',
            'company'  => 'Classic Metals Inc.',
            'location' => 'Chester, South Carolina',
            'slug'     => 'Todd',
        ],
        [
            'quote'    => __('I chose New Tech Machinery over the competition because of how valued I felt as a customer. They truly valued me and appreciated me as a person, it wasn’t just another sale.', 'standard'),
            'name'     => 'Jim Averill',
            'company'  => 'Gunnison Sheet Metal',
            'location' => 'Gunnison, Colorado',
            'slug'     => 'Jim',
        ],
        [
            'quote'    => __('For me, New Tech Machinery has always been the top-of-the-line machine for gutters, so I wanted to have something that I can rely on.', 'standard'),
            'name'     => 'Abel Cisneros',
            'company'  => 'C&S Rain Gutters',
            'location' => 'Greeley, Colorado',
            'slug'     => 'Abel',
        ],
        [
            'quote'    => __('The relationship I’ve built with NTM has been fantastic. I call up Tom and tell him what I want, and I think he has me what I need on paper within the day.', 'standard'),
            'name'     => 'Mike Lemke',
            'company'  => 'Lemke Exteriors',
            'location' => 'Moorehead, Minnesota',
            'slug'     => 'Mike',
        ],
        [
            'quote'    => __('New Tech overall has been great to work with, and between the panel options and the service, that’s why we keep going back to them.', 'standard'),
            'name'     => 'Keith Ryan',
            'company'  => 'Metal Maniacs',
            'location' => 'Fort Myers, Florida',
            'slug'     => 'Keith',
        ],
    ];
}

if (empty($testimonials)) {
    return;
}

$content = [
    'eyebrow'    => __('From the field', 'standard'),
    'channel'    => __('Customer testimonials', 'standard'),
    'sr_title'   => __('Customer testimonials', 'standard'),
    'nav_label'  => __('Testimonial navigation', 'standard'),
    'cta_label'  => __('All customer stories', 'standard'),
    'cta_url'    => '/learning-center/category/testimonials/',
    'count_left' => __('Stories', 'standard'),
];

$total = count($testimonials);
?>

<section id="machine-case-study" class="social-proof bg-blue-50 text-blue-600 border-y border-blue-200" aria-labelledby="machine-case-study-title">
    <h2 id="machine-case-study-title" class="sr-only">
        <?php echo esc_html($content['sr_title']); ?>
    </h2>

    <!-- Top chrome bar -->
    <div class="border-b border-blue-200">
        <div class="border-x border-blue-200 container">
            <div class="flex items-center justify-between py-3 text-xs font-mono uppercase tracking-wider">
                <div class="flex items-center gap-3 pl-3">
                    <span class="w-2 h-2 bg-red shrink-0" aria-hidden="true"></span>
                    <span><?php echo esc_html($content['eyebrow']); ?></span>
                </div>
                <div class="flex items-center gap-3 pr-3">
                    <span class="hidden md:inline"><?php echo esc_html($content['channel']); ?></span>
                    <span class="text-blue-900">
                        <span class="social-proof__current" data-current>1</span>
                        <span aria-hidden="true">/</span>
                        <?php echo esc_html((string) $total); ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Slider body -->
    <div class="border-x border-blue-200 container">
        <div
            class="social-proof__track max-w-[1040px] mx-auto grid"
            role="region"
            aria-roledescription="carousel"
            aria-label="<?php esc_attr_e('Customer testimonials', 'standard'); ?>"
        >
            <?php foreach ($testimonials as $index => $testimonial) : ?>
                <?php
                $is_active = $index === 0;
                $has_portrait = !empty($testimonial['slug']);
                ?>
                <blockquote
                    class="social-proof__slide [grid-area:1/1] grid gap-8 p-6 md:p-10 lg:p-12 <?php echo $has_portrait ? 'md:grid-cols-[140px_1fr] md:gap-12 md:items-start' : ''; ?> transition-opacity duration-200 ease-out <?php echo $is_active ? 'opacity-100' : 'opacity-0 pointer-events-none'; ?>"
                    data-index="<?php echo esc_attr((string) $index); ?>"
                    aria-roledescription="<?php esc_attr_e('slide', 'standard'); ?>"
                    aria-label="<?php echo esc_attr(sprintf(__('%1$d of %2$d', 'standard'), $index + 1, $total)); ?>"
                    <?php echo $is_active ? '' : 'aria-hidden="true"'; ?>
                >
                    <?php if ($has_portrait) : ?>
                        <img
                            src="<?php echo esc_url($cdn . '/' . $testimonial['slug'] . '-150x150.png'); ?>"
                            srcset="<?php echo esc_url($cdn . '/' . $testimonial['slug'] . '-150x150.png'); ?> 150w, <?php echo esc_url($cdn . '/' . $testimonial['slug'] . '-300x300.png'); ?> 300w"
                            sizes="(min-width: 768px) 140px, 120px"
                            alt=""
                            role="presentation"
                            width="140"
                            height="140"
                            class="w-28 h-28 md:w-[140px] md:h-[140px] object-cover mx-auto md:mx-0"
                            loading="<?php echo $is_active ? 'eager' : 'lazy'; ?>"
                            <?php echo $is_active ? '' : 'fetchpriority="low"'; ?>
                            decoding="async"
                        />
                    <?php endif; ?>

                    <div class="grid gap-6 content-start text-center md:text-left">
                        <p class="font-sans font-medium text-blue-900 text-xl leading-snug md:text-2xl">
                            &ldquo;<?php echo esc_html($testimonial['quote']); ?>&rdquo;
                        </p>

                        <footer>
                            <cite class="not-italic grid gap-1">
                                <span class="font-mono uppercase font-medium tracking-wider text-sm text-blue-900">
                                    <?php echo esc_html($testimonial['name']); ?>
                                </span>
                                <?php if (!empty($testimonial['company'])) : ?>
                                    <span class="font-sans text-base text-blue-600">
                                        <?php echo esc_html($testimonial['company']); ?>
                                    </span>
                                <?php endif; ?>
                                <?php if (!empty($testimonial['location'])) : ?>
                                    <span class="font-mono uppercase tracking-wider text-xs text-blue-500">
                                        <?php echo esc_html($testimonial['location']); ?>
                                    </span>
                                <?php endif; ?>
                            </cite>
                        </footer>
                    </div>
                </blockquote>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Bottom chrome bar -->
    <div class="border-t border-blue-200">
        <div class="border-x border-blue-200 container">
            <div class="flex items-center justify-between py-3 font-mono uppercase tracking-wider text-[0.625rem] md:text-xs">
                <div class="flex items-center gap-2 pl-3">
                    <span class="hidden md:inline"><?php echo esc_html((string) $total); ?></span>
                    <span class="hidden md:inline"><?php echo esc_html($content['count_left']); ?></span>
                </div>

                <nav class="flex gap-1" aria-label="<?php echo esc_attr($content['nav_label']); ?>">
                    <?php foreach ($testimonials as $index => $testimonial) : ?>
                        <?php $is_active = $index === 0; ?>
                        <button
                            type="button"
                            class="social-proof__dot h-3 border-none cursor-pointer transition-colors duration-150 hover:bg-blue-500 focus-visible:outline-2 focus-visible:outline-blue-500 focus-visible:outline-offset-2 <?php echo $is_active ? 'bg-red w-3' : 'bg-blue-300 w-1'; ?>"
                            data-index="<?php echo esc_attr((string) $index); ?>"
                            aria-label="<?php echo esc_attr(sprintf(__('View testimonial %d', 'standard'), $index + 1)); ?>"
                            <?php echo $is_active ? 'aria-current="true"' : ''; ?>
                        ></button>
                    <?php endforeach; ?>
                </nav>

                <div class="flex items-center gap-2 pr-3">
                    <a
                        href="<?php echo esc_url(\Standard\Url\internal($content['cta_url'])); ?>"
                        class="text-blue-900 inline-flex items-center gap-2 hover:text-blue-500 transition-colors duration-150"
                    >
                        <span><?php echo esc_html($content['cta_label']); ?></span>
                        <?php icon('arrow-right', ['class' => 'w-3 h-3']); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
