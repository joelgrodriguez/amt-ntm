<?php
/**
 * SVG Icon Loader
 *
 * Loads SVG icons from app/assets/icons/ and outputs them inline.
 * Icons use currentColor for stroke, making them styleable via Tailwind.
 *
 * @file icons.php
 *
 * @usage In templates:
 *   <?php icon('arrow-right'); ?>
 *   <?php icon('menu', ['class' => 'w-5 h-5']); ?>
 *
 * @source Icons from Lucide (https://lucide.dev/icons)
 * @see app/assets/icons/ for available icons
 */

declare(strict_types=1);

namespace Standard {
    if (!defined('ABSPATH')) {
        exit;
    }

    /**
     * Allowed SVG elements and attributes for wp_kses sanitization.
     * Prevents XSS while allowing valid SVG markup.
     */
    const SVG_ALLOWED_HTML = [
        'svg' => [
            'xmlns'          => true,
            'viewbox'        => true,
            'fill'           => true,
            'class'          => true,
            'width'          => true,
            'height'         => true,
            'aria-hidden'    => true,
            'aria-label'     => true,
            'role'           => true,
            'focusable'      => true,
            'stroke'         => true,
            'stroke-width'   => true,
            'stroke-linecap' => true,
            'stroke-linejoin'=> true,
        ],
        'path' => [
            'd'              => true,
            'fill'           => true,
            'fill-rule'      => true,
            'clip-rule'      => true,
            'stroke'         => true,
            'stroke-width'   => true,
            'stroke-linecap' => true,
            'stroke-linejoin'=> true,
        ],
        'circle' => [
            'cx'     => true,
            'cy'     => true,
            'r'      => true,
            'fill'   => true,
            'stroke' => true,
        ],
        'rect' => [
            'x'      => true,
            'y'      => true,
            'width'  => true,
            'height' => true,
            'rx'     => true,
            'ry'     => true,
            'fill'   => true,
        ],
        'g' => [
            'fill'      => true,
            'transform' => true,
        ],
        'polygon' => [
            'points' => true,
            'fill'   => true,
        ],
        'polyline' => [
            'points' => true,
            'fill'   => true,
            'stroke' => true,
        ],
        'line' => [
            'x1'     => true,
            'y1'     => true,
            'x2'     => true,
            'y2'     => true,
            'stroke' => true,
        ],
    ];

    /**
     * Output an SVG icon inline.
     *
     * Loads SVG from app/assets/icons/{name}.svg, injects custom attributes,
     * and outputs sanitized markup. Icons are cached in memory for performance.
     *
     * @param string $name  Icon filename without .svg extension (e.g., 'arrow-right').
     * @param array  $attrs Optional HTML attributes to add to the SVG element.
     *                      Common: ['class' => 'w-4 h-4'], ['aria-label' => 'Next']
     */
    function icon(string $name, array $attrs = []): void {
        static $cache = [];

        // Validate: only allow lowercase letters, numbers, and hyphens
        if (!preg_match('/^[a-z0-9-]+$/', $name)) {
            return;
        }

        // Load and cache SVG content
        if (!isset($cache[$name])) {
            $path = THEME_DIR . '/assets/icons/' . $name . '.svg';
            if (!file_exists($path)) {
                return;
            }
            $cache[$name] = file_get_contents($path);
        }

        $svg = $cache[$name];

        // Inject custom attributes into opening <svg> tag
        if ($attrs) {
            $attr_string = '';
            foreach ($attrs as $key => $value) {
                $attr_string .= ' ' . esc_attr($key) . '="' . esc_attr($value) . '"';
            }
            $svg = preg_replace('/<svg\s/', '<svg' . $attr_string . ' ', $svg, 1);
        }

        // Output sanitized SVG
        echo wp_kses($svg, SVG_ALLOWED_HTML);
    }
}

/**
 * Global wrapper so templates without a namespace can call icon() directly.
 */
namespace {
    if (!function_exists('icon')) {
        function icon(string $name, array $attrs = []): void {
            \Standard\icon($name, $attrs);
        }
    }
}
