<?php
/**
 * Careers — Hero
 *
 * Dark category-style opener: one H1, short employer lede, primary CTA
 * to Mazzella Search All Jobs, secondary jump to role paths. Image uses
 * the existing careers marketing photo already on the live page.
 *
 * @package Standard
 * @usage Careers (templates/template-careers.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$openings_url = defined('NTM_CAREERS_OPENINGS_URL')
    ? NTM_CAREERS_OPENINGS_URL
    : 'https://secure3.entertimeonline.com/ta/6082508.careers?CareersSearch=&lang=en-US';

$hero_image = \Standard\Url\canonical('https://newtechmachinery.com/wp-content/uploads/2024/09/IMG_2726-2.jpg');
$hero_alt   = __('New Tech Machinery team and equipment on the production floor', 'standard');
?>

<section class="relative overflow-hidden bg-blue-900 text-white pattern-dot-grid pattern-dot-grid--dark" aria-labelledby="careers-hero-title">
    <div class="container py-16 md:py-20 lg:py-24">
        <div class="grid items-center gap-10 lg:grid-cols-[1.1fr_1fr] lg:gap-16">

            <div class="grid max-w-2xl gap-6 lg:gap-8">

                <p class="font-mono text-xs uppercase tracking-mono-label text-blue-300">
                    <?php esc_html_e('Careers · New Tech Machinery', 'standard'); ?>
                </p>

                <h1
                    id="careers-hero-title"
                    class="font-sans font-medium tracking-tight text-balance text-white text-4xl md:text-5xl"
                >
                    <?php esc_html_e('Careers at New Tech Machinery', 'standard'); ?>
                </h1>

                <p class="max-w-xl text-lg text-blue-200 lg:text-xl">
                    <?php esc_html_e('Manufacturing jobs in Aurora, Colorado, plus engineering, production, service, and customer support roles on a team that designs, builds, and supports portable rollforming machines.', 'standard'); ?>
                </p>

                <div class="mt-2 flex flex-col gap-4 sm:flex-row">
                    <a
                        href="<?php echo esc_url($openings_url); ?>"
                        class="btn btn-primary"
                        target="_blank"
                        rel="noopener noreferrer"
                    >
                        <?php esc_html_e('View open positions', 'standard'); ?>
                        <?php icon('external-link', ['class' => 'w-5 h-5']); ?>
                    </a>
                    <a href="#career-paths" class="btn btn-outline-light">
                        <?php esc_html_e('Explore career paths', 'standard'); ?>
                        <?php icon('arrow-down', ['class' => 'w-5 h-5']); ?>
                    </a>
                </div>

            </div>

            <div class="relative aspect-video overflow-hidden border border-white/10 bg-blue-800">
                <?php
                \Standard\Images\responsive_image(
                    $hero_image,
                    $hero_alt,
                    'large',
                    [
                        'class'         => 'h-full w-full object-cover',
                        'loading'       => 'eager',
                        'fetchpriority' => 'high',
                        'decoding'      => 'async',
                    ]
                );
                ?>
            </div>

        </div>
    </div>
</section>
