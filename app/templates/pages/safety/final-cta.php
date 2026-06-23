<?php
/**
 * Safety Page — Final CTA
 *
 * Data wrapper for the shared closer CTA. Routes to a specialist for the
 * training/safety conversation. Factual, no claim (legal gate).
 *
 * @package Standard
 * @usage page-safety.php
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

get_template_part('templates/parts/cta/closer', null, [
    'section_id'      => 'safety-cta-title',
    'title'           => __('Questions about operating safely?', 'standard'),
    'text'            => __('Talk to a specialist about operator training and the safety systems on the machine you are considering.', 'standard'),
    'cta_primary'     => __('Talk to a Specialist', 'standard'),
    'cta_primary_url' => '/contact/',
]);
