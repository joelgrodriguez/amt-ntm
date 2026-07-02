<?php
/**
 * Shared Template Part — Fit Helper Callout
 *
 * Compact "Not sure which machine?" callout for machine-fit surfaces.
 * Routes the undecided visitor down one of two doors: the roof panel
 * assessment quiz (self-serve) or a specialist (human). Renders the
 * button pair through cta/two-door.php so the door conventions stay
 * in one place.
 *
 * This is a callout that lives *inside* an existing section — not a
 * full-bleed section of its own. If you need a standalone close-of-page
 * CTA, use cta/closer.php instead.
 *
 * @package Standard
 *
 * @param array $args {
 *     @type string $title            Optional. Callout headline.
 *     @type string $text             Optional. Supporting line.
 *     @type string $quiz_label       Optional. Primary door label.
 *     @type string $quiz_url         Optional. Primary door href (internal).
 *     @type string $specialist_label Optional. Secondary door label.
 *     @type string $specialist_url   Optional. Secondary door href (internal).
 *     @type string $surface          Optional. Background utility. Default 'bg-blue-50'
 *                                    (pass 'bg-white' on blue-50 sections).
 *     @type string $heading_id       Optional. Unique id for aria-labelledby.
 * }
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$defaults = [
    'title'            => __('Not sure which machine fits your work?', 'standard'),
    'text'             => __("Answer five quick questions and we'll point you at the right machine, or talk it through with a specialist.", 'standard'),
    'quiz_label'       => __('Take the Machine Quiz', 'standard'),
    'quiz_url'         => '/roof-panel-machine-assessment-quiz/',
    'specialist_label' => __('Talk to a Specialist', 'standard'),
    'specialist_url'   => '/contact/',
    'surface'          => 'bg-blue-50',
    'heading_id'       => 'fit-helper-title',
];

$content = wp_parse_args($args ?? [], $defaults);
?>

<aside class="grid gap-6 border border-blue-200 <?php echo esc_attr($content['surface']); ?> p-6 md:p-8" aria-labelledby="<?php echo esc_attr($content['heading_id']); ?>">

    <div class="grid gap-2">
        <h3 id="<?php echo esc_attr($content['heading_id']); ?>" class="text-xl font-semibold tracking-tight text-blue-900 md:text-2xl">
            <?php echo esc_html($content['title']); ?>
        </h3>
        <p class="text-blue-700 max-w-2xl">
            <?php echo esc_html($content['text']); ?>
        </p>
    </div>

    <?php
    get_template_part('templates/parts/cta/two-door', null, [
        'primary_label'    => $content['quiz_label'],
        'primary_url'      => $content['quiz_url'],
        'primary_new_tab'  => false,
        'specialist_label' => $content['specialist_label'],
        'specialist_url'   => $content['specialist_url'],
    ]);
    ?>

</aside>
