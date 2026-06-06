<?php
/**
 * Finance Center — Build · Quote · Finance
 *
 * The point of the whole page: a buyer can do all three online, in one flow.
 * Three numbered steps on a blue-900 surface explain that the configurator
 * builds the machine, returns a real itemized quote, and hands off to the
 * financing application — no phone call, no "contact us for pricing" stall.
 *
 * This is a page-native adaptation of the woo configurator-cta part (which
 * needs a WC_Product and ships only on machine pages). Here it links to the
 * configurator index and to the learning-center article that walks the
 * whole build-and-finance-in-one-place flow. Styling lives in
 * pages/finance-center.css (.finance-flow), not the woo bundle.
 *
 * @package Standard
 *
 * @usage Finance Center (page-finance-center.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$configurator_url = \Standard\Url\internal('/configurator/');

// Resolve a `video` post from its learning-center URL (last path segment is
// the slug) and return its post ID iff it has a featured image, else 0.
// Paths are stable across prod pulls; post IDs are not, so we link by path
// and resolve at render. A 0 means "fall back" so nothing ever blanks.
$resolve_video_thumb = static function (string $url): int {
    $segments = array_values(array_filter(explode('/', $url)));
    $slug     = (string) end($segments);
    $post     = $slug !== '' ? get_page_by_path($slug, OBJECT, 'video') : null;
    return ($post && has_post_thumbnail($post)) ? (int) $post->ID : 0;
};

// Centerpiece video — the end-to-end "build and finance in one place" walk-
// through. Its poster (featured image, fallback to the config mockup) sits in
// the intro's right column with a play overlay and links out to the video
// page. Matches the section's whole-flow thesis.
$feature_video = [
    'title' => __('Build and finance in one place', 'standard'),
    'url'   => '/learning-center/video/build-and-finance-your-ntm-machine-in-one-place-video/',
];
$feature_video['post_id'] = $resolve_video_thumb($feature_video['url']);

$steps = [
    [
        'kicker' => __('Build', 'standard'),
        'title'  => __('Configure your machine.', 'standard'),
        'copy'   => __('Profile, power pack, control system, accessories. Every option a specialist would walk you through, surfaced as a guided picker.', 'standard'),
    ],
    [
        'kicker' => __('Quote', 'standard'),
        'title'  => __('See real pricing instantly.', 'standard'),
        'copy'   => __('Your build returns a transparent, itemized quote you can save, print, or send to a partner. No gatekeeper between you and the number.', 'standard'),
    ],
    [
        'kicker' => __('Finance', 'standard'),
        'title'  => __('Apply in the same flow.', 'standard'),
        'copy'   => __('Send your configured build straight into the financing application. Build, price, and apply without leaving the browser.', 'standard'),
    ],
];
?>

<section
    id="finance-flow"
    class="finance-flow bg-blue-900 text-white border-y border-blue-800"
    aria-labelledby="finance-flow-title"
>
    <div class="container section">

        <div class="finance-flow__intro">
            <div class="finance-flow__header">
                <p class="finance-flow__eyebrow">
                    <span aria-hidden="true" class="finance-flow__eyebrow-dot"></span>
                    <?php esc_html_e('Build · Quote · Finance', 'standard'); ?>
                </p>
                <h2 id="finance-flow-title" class="finance-flow__title">
                    <?php esc_html_e('Build it, price it, and finance it in one place.', 'standard'); ?>
                </h2>
                <p class="finance-flow__lede">
                    <?php esc_html_e('The configurator runs the whole path in the browser. Spec your machine, get a real quote, and start the financing application from the same screen.', 'standard'); ?>
                </p>
            </div>

            <figure class="finance-flow__shot">
                <a
                    href="<?php echo esc_url(\Standard\Url\internal($feature_video['url'])); ?>"
                    class="finance-flow__shot-link"
                    aria-label="<?php echo esc_attr(sprintf(
                        /* translators: %s: video title */
                        __('Watch: %s', 'standard'),
                        $feature_video['title']
                    )); ?>"
                >
                    <span class="finance-flow__shot-frame">
                        <?php if (!empty($feature_video['post_id'])) : ?>
                            <?php echo get_the_post_thumbnail($feature_video['post_id'], 'large', [
                                'class'   => 'finance-flow__shot-img',
                                'loading' => 'lazy',
                                'alt'     => '',
                            ]); ?>
                        <?php else : ?>
                            <img
                                src="<?php echo esc_url(THEME_URI . '/assets/images/config-mockup.png'); ?>"
                                alt=""
                                class="finance-flow__shot-img"
                                width="2613"
                                height="1634"
                                loading="lazy"
                                decoding="async"
                            >
                        <?php endif; ?>
                        <span class="finance-flow__shot-play" aria-hidden="true">
                            <?php icon('play', ['class' => 'w-6 h-6']); ?>
                        </span>
                    </span>
                </a>
                <figcaption class="finance-flow__shot-caption">
                    <?php esc_html_e('Watch: build, price, and finance in one place', 'standard'); ?>
                </figcaption>
            </figure>
        </div>

        <ol class="finance-flow__steps" role="list">
            <?php foreach ($steps as $index => $step) : ?>
                <li class="finance-flow__step">
                    <span class="finance-flow__step-index" aria-hidden="true">
                        <?php echo esc_html(str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT)); ?>
                    </span>
                    <div class="finance-flow__step-body">
                        <p class="finance-flow__step-kicker">
                            <?php echo esc_html($step['kicker']); ?>
                        </p>
                        <h3 class="finance-flow__step-title">
                            <?php echo esc_html($step['title']); ?>
                        </h3>
                        <p class="finance-flow__step-copy">
                            <?php echo esc_html($step['copy']); ?>
                        </p>
                    </div>
                </li>
            <?php endforeach; ?>
        </ol>

        <div class="finance-flow__actions">
            <a href="<?php echo esc_url($configurator_url); ?>" class="btn btn-primary btn--commit" target="_blank" rel="noopener">
                <?php esc_html_e('Open the configurator', 'standard'); ?>
                <?php icon('arrow-right', ['class' => 'w-5 h-5']); ?>
            </a>
        </div>

    </div>
</section>
