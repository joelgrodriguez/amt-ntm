<?php
/**
 * Contact Page Data
 *
 * Hardcoded FAQ + location data for the /contact lead-form template.
 * Content lifted from post 210 so the template no longer depends on
 * Advanced Gutenberg blocks for structured data.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard\ContactData;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * @return list<array{question: string, answer: string}>
 */
function get_faq_items(): array
{
    return [
        [
            'question' => 'What’s the difference between New Tech Machinery’s standing seam roof panel machines?',
            'answer'   => 'Each NTM roof panel machine has different features, profiles, and capabilities based on your project types, business needs, and budget. <a href="' . esc_url(home_url('/roof-panel-machine-assessment-quiz/')) . '">Take our quiz</a> to learn which of our roof panel machines might be right for your business.',
        ],
        [
            'question' => 'Does NTM sell used portable rollforming machinery?',
            'answer'   => 'No, New Tech Machinery doesn’t offer used rollforming machinery, but <a href="' . esc_url(home_url('/learning-center/best-places-to-find-used-portable-rollforming-machines/')) . '">this article</a> covers where you can purchase pre-owned machines.',
        ],
        [
            'question' => 'Does NTM offer financing?',
            'answer'   => 'NTM does not offer in-house financing but partners with various leasing and financing agencies. <a href="' . esc_url(home_url('/machines/leasing-financing/')) . '">Visit our financing page</a> to learn more.',
        ],
        [
            'question' => 'What is New Tech Machinery’s warranty?',
            'answer'   => 'NTM offers a three-year limited warranty against manufacturer’s defects, including electrical, and a limited lifetime warranty against separation of the drive rollers. <a href="' . esc_url(home_url('/general-terms-conditions/')) . '">Read the full warranty terms here</a>.',
        ],
        [
            'question' => 'What should I do if I need troubleshooting help or service on my machine?',
            'answer'   => '<ol><li>First, refer to your machine manual. Find a digital copy on our website here: <a href="' . esc_url(home_url('/learning-center/resource/manuals/')) . '">NTM machine manuals</a>.</li><li>Still need help? <a href="https://support.newtechmachinery.com/" target="_blank" rel="noreferrer noopener">Visit the Service &amp; Support Center</a> for answers to common troubleshooting problems.</li><li>If you can’t find an answer, or want to talk to a Service Tech over the phone, <a href="https://support.newtechmachinery.com/contact/" target="_blank" rel="noreferrer noopener">contact the Service department</a>. A Service Technician will be with you as soon as possible.</li></ol>',
        ],
    ];
}

/**
 * @return list<array{
 *     eyebrow: string,
 *     name: string,
 *     address_html: string,
 *     map_query: string,
 *     phones: list<array{label: string, display: string, tel: string, note?: string}>,
 *     fax?: string
 * }>
 */
function get_locations(): array
{
    return [
        [
            'eyebrow'      => __('Sales · Aurora, CO', 'standard'),
            'name'         => __('NTM Sales and Manufacturing Facility', 'standard'),
            'address_html' => '16265 E. 33rd Dr. Suite 40<br>Aurora, Colorado 80011',
            'map_query'    => '16265 E. 33rd Dr. Suite 40, Aurora, CO 80011',
            'phones'       => [
                ['label' => __('Phone', 'standard'), 'display' => '303.294.0538', 'tel' => '+13032940538'],
            ],
            'fax' => '303.294.9407',
        ],
        [
            'eyebrow'      => __('Service · Aurora, CO', 'standard'),
            'name'         => __('Service and Engineering Center', 'standard'),
            'address_html' => '16401 East 33rd Dr., Suite 10<br>Aurora, Colorado 80011',
            'map_query'    => '16401 East 33rd Dr. Suite 10, Aurora, CO 80011',
            'phones'       => [
                [
                    'label'   => __('Phone', 'standard'),
                    'display' => '303.294.0538',
                    'tel'     => '+13032940538',
                    'note'    => __('Select option 2 after the prompt', 'standard'),
                ],
            ],
        ],
        [
            'eyebrow'      => __('Manufacturing · Hermosillo, MX', 'standard'),
            'name'         => __('NTM Mexico Manufacturing Facility', 'standard'),
            'address_html' => 'Latitud Oriente #27,<br>Latitud Industrial Park<br>Hermosillo, Mexico',
            'map_query'    => 'Latitud Oriente 27, Latitud Industrial Park, Hermosillo, Mexico',
            'phones'       => [
                ['label' => __('Phone', 'standard'), 'display' => '+52 662.218.41.0', 'tel' => '+5266221841'],
            ],
        ],
    ];
}

/**
 * Build a universal maps URL. Apple Maps' query format works on both
 * Apple devices (opens Apple Maps) and Android/desktop (web fallback).
 */
function map_url(string $query): string
{
    return 'https://maps.apple.com/?q=' . rawurlencode($query);
}
