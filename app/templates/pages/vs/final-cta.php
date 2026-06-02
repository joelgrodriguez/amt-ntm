<?php
/**
 * Roof Panel vs Gutter — Final CTA
 *
 * Closing dark band. This is a top-of-funnel decision page, so the
 * close is "still not sure?" — talk to a specialist or take the quiz.
 * No configurator here on purpose: the configurator is a bottom-of-
 * funnel destination for buyers who already know their machine
 * (see docs/handoff/03-mega-menu-spec.md, configurator placement rule).
 *
 * @package Standard
 *
 * @usage Roof Panel vs Gutter (page-roof-panel-vs-gutter.php)
 * @see   templates/parts/cta/closer.php
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

get_template_part('templates/parts/cta/closer', null, [
    'section_id'        => 'vs-final-cta-title',
    'title'             => __('Still Not Sure Which Way to Go?', 'standard'),
    'text'              => __('Tell us about the work you do and the customers you serve, and an NTM specialist will point you to the right machine. No pressure, no obligation.', 'standard'),
    'cta_primary'       => __('Talk to a Specialist', 'standard'),
    'cta_primary_url'   => '/contact/',
    'cta_secondary'     => __('Take the machine quiz', 'standard'),
    'cta_secondary_url' => '/choose-your-machine/',
]);
