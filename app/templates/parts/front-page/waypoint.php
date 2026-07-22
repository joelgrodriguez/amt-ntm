<?php
/**
 * Front Page — Mobile path reset.
 *
 * Compact wayfinding after the machine browser. It gives small-screen users a
 * clear next step before the page moves into quiz/sell/education sections.
 *
 * @package Standard
 *
 * @usage Front Page (front-page.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$paths = [
    [
        'icon'  => 'settings',
        'label' => __('Find a machine', 'standard'),
        'text'  => __('Browse the full lineup.', 'standard'),
        'url'   => '/machines/',
    ],
    [
        'icon'  => 'trending-up',
        'label' => __('Upgrade your machine', 'standard'),
        'text'  => __('Accessories and add-ons.', 'standard'),
        'url'   => '/machines/upgrades/',
    ],
    [
        'icon'  => 'life-buoy',
        'label' => __('Service & manuals', 'standard'),
        'text'  => __('Owner support and docs.', 'standard'),
        'url'   => '/service-hub/',
    ],
    [
        'icon'  => 'phone',
        'label' => __('Talk to sales', 'standard'),
        'text'  => __('Get help choosing.', 'standard'),
        'url'   => '/contact/',
    ],
];
?>

<section class="bg-blue-900 text-white border-y border-blue-700 lg:hidden" aria-labelledby="home-waypoint-title">
    <div class="container py-8 md:py-10">
        <div class="grid gap-5 md:grid-cols-3 md:items-center md:gap-8">
            <div class="grid gap-2 md:col-span-1">
                <p class="font-mono text-xs font-medium uppercase tracking-widest text-blue-300 m-0">
                    <?php esc_html_e('Next step', 'standard'); ?>
                </p>
                <h2 id="home-waypoint-title" class="font-sans text-2xl font-medium tracking-tight text-white md:text-3xl m-0">
                    <?php esc_html_e('Choose your path.', 'standard'); ?>
                </h2>
            </div>

            <ul class="grid gap-2 sm:grid-cols-2 md:col-span-2 m-0 p-0 list-none" role="list">
                <?php foreach ($paths as $path) : ?>
                    <li class="grid">
                        <a
                            href="<?php echo esc_url(\Standard\Url\internal($path['url'])); ?>"
                            class="group grid h-full min-h-20 grid-cols-[2.75rem_minmax(0,1fr)] items-center gap-2 border border-blue-700 bg-blue-800/40 p-3 text-white no-underline transition-colors hover:border-blue-400 hover:bg-blue-800"
                        >
                            <span class="flex h-11 w-11 items-center justify-center bg-blue-700 text-blue-200 transition-colors group-hover:bg-blue-600 group-hover:text-white">
                                <?php icon($path['icon'], ['class' => 'w-5 h-5']); ?>
                            </span>
                            <span class="grid min-w-0 gap-0.5">
                                <span class="font-sans text-sm font-medium text-white">
                                    <?php echo esc_html($path['label']); ?>
                                </span>
                                <span class="font-sans text-sm text-blue-200">
                                    <?php echo esc_html($path['text']); ?>
                                </span>
                            </span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</section>
