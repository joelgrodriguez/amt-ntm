<?php
/**
 * Machines Page — Which Machine Decision Helper
 *
 * Centered CTA section placed after comparison table.
 * Links to existing machine quiz and contact page.
 *
 * @package Standard
 *
 * @usage Machines Page (page-machines.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$content = [
    'title'         => __('Not Sure Which Machine Is Right for You?', 'standard'),
    'text'          => __("Answer a few questions about your business and project types, and we'll recommend the best machine for your needs — or talk directly with one of our specialists.", 'standard'),
    'cta_quiz'      => __('Take the Machine Quiz', 'standard'),
    'cta_quiz_url'  => '/roof-panel-machine-assessment-quiz/',
    'cta_talk'      => __('Talk to a Specialist', 'standard'),
    'cta_talk_url'  => '/contact/',
];
?>

<section class="section-compact pattern-square-grid" aria-labelledby="which-machine-title">
    <div class="pattern-square-grid__overlay pattern-square-grid__overlay--top-left"></div>
    <div class="pattern-square-grid__overlay pattern-square-grid__overlay--bottom-right"></div>

    <div class="container grid gap-8 text-center max-w-3xl mx-auto relative z-10">
        <div class="grid gap-4">
            <h2 id="which-machine-title" class="text-2xl font-medium text-blue-900 md:text-3xl lg:text-4xl">
                <?php echo esc_html($content['title']); ?>
            </h2>
            <p class="text-lg text-blue-600 max-w-2xl mx-auto">
                <?php echo esc_html($content['text']); ?>
            </p>
        </div>

        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="<?php echo esc_url(\Standard\Url\internal($content['cta_quiz_url'])); ?>" class="btn btn-primary btn-lg">
                <?php icon('help-circle', ['class' => 'w-5 h-5']); ?>
                <?php echo esc_html($content['cta_quiz']); ?>
            </a>
            <a href="<?php echo esc_url(\Standard\Url\internal($content['cta_talk_url'])); ?>" class="btn btn-outline-dark btn-lg">
                <?php echo esc_html($content['cta_talk']); ?>
            </a>
        </div>
    </div>
</section>
