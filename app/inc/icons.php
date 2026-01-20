<?php
/**
 * Icon loader utility.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard {
    if (!defined('ABSPATH')) {
        exit;
    }

    const SVG_ALLOWED_HTML = [
        'svg' => [
            'xmlns' => true,
            'viewbox' => true,
            'fill' => true,
            'class' => true,
            'width' => true,
            'height' => true,
            'aria-hidden' => true,
            'aria-label' => true,
            'role' => true,
            'focusable' => true,
        ],
        'path' => [
            'd' => true,
            'fill' => true,
            'fill-rule' => true,
            'clip-rule' => true,
            'stroke' => true,
            'stroke-width' => true,
            'stroke-linecap' => true,
            'stroke-linejoin' => true,
        ],
        'circle' => [
            'cx' => true,
            'cy' => true,
            'r' => true,
            'fill' => true,
            'stroke' => true,
        ],
        'rect' => [
            'x' => true,
            'y' => true,
            'width' => true,
            'height' => true,
            'rx' => true,
            'ry' => true,
            'fill' => true,
        ],
        'g' => [
            'fill' => true,
            'transform' => true,
        ],
        'polygon' => [
            'points' => true,
            'fill' => true,
        ],
        'polyline' => [
            'points' => true,
            'fill' => true,
            'stroke' => true,
        ],
        'line' => [
            'x1' => true,
            'y1' => true,
            'x2' => true,
            'y2' => true,
            'stroke' => true,
        ],
    ];

    function icon(string $name, array $attrs = []): void {
        static $cache = [];

        if (!preg_match('/^[a-z0-9-]+$/', $name)) {
            return;
        }

        if (!isset($cache[$name])) {
            $path = THEME_DIR . '/assets/icons/' . $name . '.svg';
            if (!file_exists($path)) {
                return;
            }
            $cache[$name] = file_get_contents($path);
        }

        $svg = $cache[$name];

        if ($attrs) {
            $attr_string = '';
            foreach ($attrs as $key => $value) {
                $attr_string .= ' ' . esc_attr($key) . '="' . esc_attr($value) . '"';
            }
            $svg = preg_replace('/<svg\s/', '<svg' . $attr_string . ' ', $svg, 1);
        }

        echo wp_kses($svg, SVG_ALLOWED_HTML);
    }
}

namespace {
    if (!function_exists('icon')) {
        function icon(string $name, array $attrs = []): void {
            \Standard\icon($name, $attrs);
        }
    }
}
