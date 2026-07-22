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
 * Image-left composition: photo anchors the buyer first, copy
 * answers the photo's implicit question.
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
    'title'     => __('Which portable roof panel machine is best for you?', 'standard'),
    'body'      => __('Take this short quiz to find the right NTM roof panel machine for your operation: the SSR™ MultiPro Jr., SSH™ MultiPro, or SSQ II™ MultiPro. Increase uptime and ROI by fabricating your own panels.', 'standard'),
    'cta'       => __('Take the 10-Question Quiz', 'standard'),
    'cta_url'   => '/roof-panel-machine-assessment-quiz/',
    'image'     => content_url('/uploads/2026/06/ssq3-operator-at-controls.jpg'),
    'image_alt' => __('NTM operator at the SSQ3 MultiPro controls on a job site', 'standard'),
];
?>

<section class="section bg-white" aria-labelledby="quiz-title">
    <div class="container">
        <div class="grid gap-10 lg:grid-cols-2 lg:gap-16 lg:items-center">

            <!-- Content column (right) -->
            <div class="content-start lg:order-2" data-reveal="fade">
                <?php get_template_part('templates/parts/section-header', null, [
                    'id'      => 'quiz-title',
                    'eyebrow' => $content['eyebrow'],
                    'title'   => $content['title'],
                    'lede'    => $content['body'],
                    'cta'     => [
                        'label' => $content['cta'],
                        'url'   => $content['cta_url'],
                    ],
                ]); ?>
            </div>

            <!-- Image column (left) -->
            <div class="lg:order-1">
                <div class="aspect-video overflow-hidden" data-reveal="image">
                    <?php \Standard\Images\responsive_image($content['image'], $content['image_alt'], 'large', [
                        'class'   => 'w-full h-full object-cover block',
                        'loading' => 'lazy',
                        'sizes'   => '(min-width: 1024px) 50vw, 100vw',
                    ]); ?>
                </div>
            </div>

        </div>
    </div>
</section>
