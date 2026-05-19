<?php
/**
 * Final CTA Section — Front Page
 *
 * Thin data wrapper around the shared closer CTA part. Centralizes the
 * closing copy used on the front page; the visual template lives at
 * templates/parts/cta/closer.php.
 *
 * @package Standard
 *
 * @usage Front Page (front-page.php)
 * @see   templates/parts/cta/closer.php
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

get_template_part('templates/parts/cta/closer', null, [
    'title'           => __('Ready to Take Control of Your Business?', 'standard'),
    'text'            => __('Join thousands of contractors who stopped waiting on suppliers and started rolling their own profits.', 'standard'),
    'cta_primary'     => __('Talk to a Specialist', 'standard'),
    'cta_primary_url' => '/contact/',
    'section_id'      => 'final-cta-title',
]);
