<?php
/**
 * Machines Page — Which Machine Decision Helper
 *
 * Centered CTA section after the comparison table.
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
    'text'          => __("Answer a few questions about your business and project types, and we'll recommend the best machine for your needs, or talk directly with one of our specialists.", 'standard'),
    'cta_quiz'      => __('Take the Machine Quiz', 'standard'),
    'cta_quiz_url'  => '/roof-panel-machine-assessment-quiz/',
    'cta_talk'      => __('Talk to a Specialist', 'standard'),
    'cta_talk_url'  => '/contact/',
];

$bg = $args['bg'] ?? '';
?>

<section class="section <?php echo esc_attr($bg); ?>" aria-labelledby="which-machine-title">
    <div class="container grid gap-8 text-center max-w-3xl mx-auto">
        <div class="grid gap-4">
            <h2 id="which-machine-title" class="section-title">
                <?php echo esc_html($content['title']); ?>
            </h2>
            <p class="section-subtitle max-w-2xl mx-auto">
                <?php echo esc_html($content['text']); ?>
            </p>
        </div>

        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="<?php echo esc_url(\Standard\Url\internal($content['cta_quiz_url'])); ?>" class="btn btn-primary">
                <?php echo esc_html($content['cta_quiz']); ?>
            </a>
            <a href="<?php echo esc_url(\Standard\Url\internal($content['cta_talk_url'])); ?>" class="btn btn-outline-dark">
                <?php echo esc_html($content['cta_talk']); ?>
            </a>
        </div>
    </div>
</section>
