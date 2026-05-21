<?php
/**
 * Map product slugs to the product_tag that identifies compatible accessories.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard\Woo\AccessoryTagMap;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * @return array<string, string>
 */
function get_map(): array {
    return [
        'bg7-box-gutter-machine'                => 'BG7',
        'ssq-roof-panel-machine'                => 'SSQII',
        'ssq3-multipro'                         => 'SSQIII',
        'ssq3-roof-panel-machine'               => 'SSQIII',
        'ssh-roof-panel-machine'                => 'SSH',
        'ssr-multipro-jr-roof-panel-machine'    => 'SSR',
        'ssr-roof-panel-machine'                => 'SSR',
        '5vc-5v-crimp-roof-panel-machine'       => '5VC',
        'wav-wall-panel-machine'                => 'WAV',
        'mach-ii-5-6-5-6-gutter-machines'       => 'MACHII',
        'mach-ii-5-6-combo-gutter-machine'      => 'MACHII',
        'mach-ii-combo-gutter-machine'          => 'MACHII',
        'mach-ii-6-gutter-machine'              => 'MACHII',
        'mach-ii-5-gutter-machine'              => 'MACHII',
        'mach-ii-7-8-gutter-machine'            => 'MACHII-7/8',
        'the-nasser-multipro-gutter-machine'    => 'NASSER',
    ];
}

function tag_for_slug(string $slug): ?string {
    return get_map()[$slug] ?? null;
}
