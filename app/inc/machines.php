<?php
/**
 * Featured Machines Configuration
 *
 * Centralized machine data for the front page hero slider.
 * This can be converted to WooCommerce product queries later.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard\Machines;

/**
 * Get featured machines for the hero slider.
 *
 * Each machine includes:
 * - id: Unique identifier (used for CSS/JS targeting)
 * - category: Machine category (displayed as label)
 * - title: Machine name displayed on slide
 * - subtitle: Optional tagline or description
 * - background_image: Path to background image (required)
 * - background_video: Path to background video (optional, mp4)
 * - finance_url: URL for "Build & Finance" button
 * - learn_more_url: URL for "Learn More" button
 *
 * @return array<int, array{
 *     id: string,
 *     category: string,
 *     title: string,
 *     subtitle: string,
 *     background_image: string,
 *     background_video: string,
 *     finance_url: string,
 *     learn_more_url: string
 * }>
 */
function get_featured_machines(): array {
    // Using images from the WordPress media library
    $uploads_url = 'https://newtechmachinery.com/wp-content/uploads';

    return [
        // Roof & Wall Panel Machines
        [
            'id'               => 'ssq3-multipro',
            'category'         => 'Roof & Wall Panel Machines',
            'title'            => 'SSQ3™ MultiPro',
            'subtitle'         => 'Roof and Wall Panel Machine',
            'background_image' => $uploads_url . '/2026/01/Screenshot-2026-01-07-at-9.37.43-AM.png',
            'background_video' => '',
            'finance_url'      => '/build-finance/?machine=ssq3-multipro',
            'learn_more_url'   => '/machines/ssq3-multipro/',
        ],
        [
            'id'               => 'ssq-ii-multipro',
            'category'         => 'Roof & Wall Panel Machines',
            'title'            => 'SSQ II™ MultiPro',
            'subtitle'         => 'Roof and Wall Panel Machine',
            'background_image' => $uploads_url . '/2025/12/starting-SSQ-on-job-site-1024x576-1.jpg',
            'background_video' => '',
            'finance_url'      => '/build-finance/?machine=ssq-ii-multipro',
            'learn_more_url'   => '/machines/ssq-ii-multipro/',
        ],
        [
            'id'               => 'ssh-multipro',
            'category'         => 'Roof & Wall Panel Machines',
            'title'            => 'SSH™ MultiPro',
            'subtitle'         => 'Roof Panel Machine',
            'background_image' => $uploads_url . '/2025/09/Machine-on-rooftop-scaled.jpg',
            'background_video' => '',
            'finance_url'      => '/build-finance/?machine=ssh-multipro',
            'learn_more_url'   => '/machines/ssh-multipro/',
        ],
        [
            'id'               => 'ssr-multipro-jr',
            'category'         => 'Roof & Wall Panel Machines',
            'title'            => 'SSR™ MultiPro Jr.',
            'subtitle'         => 'Roof Panel Machine',
            'background_image' => $uploads_url . '/2023/05/5V-on-site.jpg',
            'background_video' => '',
            'finance_url'      => '/build-finance/?machine=ssr-multipro-jr',
            'learn_more_url'   => '/machines/ssr-multipro-jr/',
        ],
        [
            'id'               => '5vc-5v-crimp',
            'category'         => 'Roof & Wall Panel Machines',
            'title'            => '5VC-5V CRIMP™',
            'subtitle'         => 'Roof Panel Machine',
            'background_image' => $uploads_url . '/2023/05/5V-on-site.jpg',
            'background_video' => '',
            'finance_url'      => '/build-finance/?machine=5vc-5v-crimp',
            'learn_more_url'   => '/machines/5vc-5v-crimp/',
        ],
        [
            'id'               => 'wav-wall-panel',
            'category'         => 'Roof & Wall Panel Machines',
            'title'            => 'WAV™',
            'subtitle'         => 'Wall Panel Machine',
            'background_image' => $uploads_url . '/2025/09/Machine-on-rooftop-scaled.jpg',
            'background_video' => '',
            'finance_url'      => '/build-finance/?machine=wav-wall-panel',
            'learn_more_url'   => '/machines/wav-wall-panel/',
        ],
        // Seamless Gutter Machines
        [
            'id'               => 'mach-ii-gutter',
            'category'         => 'Seamless Gutter Machines',
            'title'            => 'MACH II™ Gutter Machines',
            'subtitle'         => 'Available in 5″, 6″, and 5″/6″ Combo',
            'background_image' => $uploads_url . '/2024/07/20240612_NTM_CS-Rain-Gutters-Interview_V1.00_03_30_06.Still002.jpg',
            'background_video' => '',
            'finance_url'      => '/build-finance/?machine=mach-ii',
            'learn_more_url'   => '/machines/mach-ii/',
        ],
        [
            'id'               => 'bg7-box-gutter',
            'category'         => 'Seamless Gutter Machines',
            'title'            => 'BG7™',
            'subtitle'         => 'Box Gutter Machine',
            'background_image' => $uploads_url . '/2023/09/BG7-forming-gutter-scaled.jpg',
            'background_video' => '',
            'finance_url'      => '/build-finance/?machine=bg7-box-gutter',
            'learn_more_url'   => '/machines/bg7-box-gutter/',
        ],
    ];
}
