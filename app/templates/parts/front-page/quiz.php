<?php
/**
 * Quiz Section — Front Page
 *
 * Two-column band promoting the 10-question roof panel machine
 * assessment quiz. Replaces the earlier 3-dropdown Router form,
 * which asked the buyer to commit to spec answers (profile, coil
 * width, volume) they might not have. The quiz is the friendlier
 * funnel: ten questions, one CTA, no spec literacy required.
 *
 * Image-right composition pairs with the Flagships SSQ3 callout
 * directly above (image-left), creating an alternating cadence.
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
    'eyebrow'   => __('Not Sure Which Machine?', 'standard'),
    'title'     => __('Which portable roof panel machine is best for you and your business operations?', 'standard'),
    'body'      => __('Take this 10-question quiz and find out which NTM roof panel machine is best suited for your metal roofing projects: the SSR™ MultiPro Jr., SSH™ MultiPro, or SSQ II™ MultiPro. See how the right machine can improve your manufacturing.', 'standard'),
    'cta'       => __('Take the 10-Question Quiz', 'standard'),
    'cta_url'   => '/roof-panel-machine-assessment-quiz/',
    'image'     => content_url('/uploads/2026/04/training-3-scaled.jpg'),
    'image_alt' => __('NTM training session with a roof panel machine in operation', 'standard'),
];
?>

<section class="section bg-blue-50" aria-labelledby="quiz-title">
    <div class="container">
        <div class="grid gap-10 lg:grid-cols-2 lg:gap-16 lg:items-center">

            <!-- Content column (left) -->
            <div class="grid gap-6 lg:gap-8 content-start lg:order-1">

                <!-- Eyebrow: red dot + mono category -->
                <div class="flex items-center gap-3">
                    <span class="w-2 h-2 bg-red shrink-0" aria-hidden="true"></span>
                    <p class="font-mono uppercase tracking-wider text-xs text-blue-700">
                        <?php echo esc_html($content['eyebrow']); ?>
                    </p>
                </div>

                <!-- Headline (the question itself) -->
                <h2 id="quiz-title" class="font-sans font-medium text-blue-900 tracking-tight leading-tight text-3xl md:text-4xl lg:text-5xl">
                    <?php echo esc_html($content['title']); ?>
                </h2>

                <!-- Body (the offer) -->
                <p class="font-sans text-blue-600 text-base lg:text-lg max-w-xl leading-relaxed">
                    <?php echo esc_html($content['body']); ?>
                </p>

                <!-- CTA -->
                <div class="flex -mt-2 lg:-mt-4">
                    <a
                        href="<?php echo esc_url(\Standard\Url\internal($content['cta_url'])); ?>"
                        class="btn btn-primary"
                    >
                        <?php echo esc_html($content['cta']); ?>
                        <?php icon('arrow-right', ['class' => 'w-4 h-4']); ?>
                    </a>
                </div>
            </div>

            <!-- Image column (right) -->
            <div class="lg:order-2">
                <div class="aspect-video overflow-hidden">
                    <img
                        src="<?php echo esc_url($content['image']); ?>"
                        alt="<?php echo esc_attr($content['image_alt']); ?>"
                        loading="lazy"
                        decoding="async"
                        class="w-full h-full object-cover block"
                    >
                </div>
            </div>

        </div>
    </div>
</section>
