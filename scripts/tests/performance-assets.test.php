<?php
/**
 * Regression checks for catalog image and third-party loading policy.
 */

declare(strict_types=1);

$root = dirname(__DIR__, 2);
$failures = [];

function check(bool $condition, string $message): void
{
    global $failures;
    if (!$condition) {
        $failures[] = $message;
    }
}

function read_theme_file(string $relative): string
{
    global $root;
    $path = $root . '/' . $relative;

    if (!is_file($path)) {
        return '';
    }

    $contents = file_get_contents($path);
    return is_string($contents) ? $contents : '';
}

$template_php = '';
foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root . '/app/templates')) as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $template_php .= file_get_contents($file->getPathname());
    }
}

$images = read_theme_file('app/inc/images.php');
$performance = read_theme_file('app/inc/performance.php');
$video = read_theme_file('app/inc/video.php');
$header = read_theme_file('app/header.php');
$loader = read_theme_file('app/resources/js/modules/ThirdPartyLoader.js');
$page_templates = read_theme_file('app/inc/page-templates.php');
$configurator_shell = read_theme_file('app/templates/template-configurator-shell.php');
$wistia_sync = read_theme_file('scripts/media/sync-wistia-thumbnails.php');
$machine_template = read_theme_file('app/templates/woo/product/single-machine-default.php');
$accessory_hero = read_theme_file('app/templates/woo/product/parts/accessory-hero.php');

check(!str_contains($template_php, 'E-v1.js'), 'Templates must not eagerly load Wistia E-v1.js.');
check(!str_contains($header, 'js.hs-scripts.com'), 'Header must not eagerly load HubSpot chat.');
check(str_contains($video, 'data-video-facade'), 'Wistia must render through the click facade.');
check(str_contains($wistia_sync, '$wpdb->postmeta'), 'Wistia poster sync must discover database-backed product videos.');
check(str_contains($images, 'type="image/webp"'), 'Attachment markup must expose WebP sources.');
check(str_contains($images, 'wp_get_attachment_image_srcset'), 'Image helper must preserve responsive srcsets.');
check(str_contains($images, 'int|string $attachment_id'), 'Image helper must accept numeric-string attachment IDs from WordPress and WooCommerce.');
check(str_contains($machine_template, '(int) $product->get_image_id()'), 'Default machine hero must normalize its featured-image ID.');
check(str_contains($accessory_hero, '(int) $product->get_image_id()'), 'Accessory hero must normalize its featured-image ID.');
check(str_contains($performance, 'wc-blocks-style'), 'Catalog policy must remove WooCommerce block CSS.');
check(str_contains($performance, 'tec-user-agent'), 'Catalog policy must remove Events Calendar user-agent JS.');
check(str_contains($performance, "wp_dequeue_style('dashicons')"), 'Public catalog pages must remove Dashicons.');
check(!str_contains($performance, 'google_gtagjs'), 'Theme must not delay Site Kit/GA page-view measurement.');
check(!str_contains($performance, 'jquery-bind-first'), 'Theme must not delay PixelYourSite/Meta measurement.');
check(!str_contains($loader, 'data-ntm-deferred-script'), 'Core analytics must not pass through the replay/chat gate.');
check(str_contains($loader, 'requestIdleCallback'), 'Replay loader must retain its post-load idle fallback.');
check(str_contains($loader, "window.addEventListener('load'"), 'Replay idle scheduling must wait for page load.');
check(str_contains($loader, 'loadClarity'), 'Clarity must load through the non-essential third-party gate.');
check(str_contains($loader, 'loadCorbel'), 'Corbel chat must load through the third-party gate.');
check(!str_contains($loader, 'loadHubspot'), 'The theme must not load HubSpot chat.');
check(str_contains($performance, 'dequeue_hubspot_chat_loaders'), 'Public pages must suppress plugin-owned HubSpot loaders.');
check(str_contains($performance, "wp_dequeue_script('leadin-script-loader-js')"), 'The official HubSpot chat loader must be removed.');
check(str_contains($performance, "wp_dequeue_script('standard-hubspot-tracker')"), 'The site integration HubSpot loader must be removed.');
check($configurator_shell !== '', 'The configurator page tree must use its dedicated shell template.');
check(
    !str_contains($configurator_shell, 'Template Name:'),
    'The internal configurator shell must not be assignable as a generic WordPress page template.'
);
check(
    !str_contains($configurator_shell, 'min-height: 100vh'),
    'Configurator sizing must not override the dynamic iOS viewport with a 100vh minimum.'
);
check(
    str_contains($configurator_shell, 'body.configurator-shell #primary iframe,'),
    'Configurator iframe sizing must stay scoped to the primary embed canvas.'
);
check(
    str_contains($page_templates, "get_theme_file_path('templates/template-configurator-shell.php')"),
    'Configurator routing must use the dedicated shell template.'
);
check(
    !str_contains($page_templates, 'template-empty-shell.php'),
    'Configurator routing must not retain the obsolete generic shell filename.'
);

foreach (['w1u1r55n9v', 'kdv2kphni1', 'd43ez7v1wc', 'vf198bnz3w', 'jxmgaicen7', 'qmq0ibzvx7', 'gxl0kqlpxl'] as $media_id) {
    check(
        is_file($root . '/app/assets/images/wistia/' . $media_id . '-480.webp')
            && is_file($root . '/app/assets/images/wistia/' . $media_id . '-960.webp'),
        'Stored responsive Wistia poster is missing for ' . $media_id . '.'
    );
}

if ($failures !== []) {
    fwrite(STDERR, implode(PHP_EOL, array_map(static fn(string $failure): string => 'FAIL: ' . $failure, $failures)) . PHP_EOL);
    exit(1);
}

echo "Performance asset checks passed.\n";
