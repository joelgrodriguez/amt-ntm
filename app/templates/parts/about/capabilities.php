<?php
/**
 * About — Full-Gamut Capabilities
 *
 * What "partnering with NTM" actually means. Five capabilities, each
 * with one factual proof. Layout breaks the page's eyebrow/headline/lede
 * rhythm with numbered cells on a hairline grid, so it doesn't read as
 * "the fifth identical section."
 *
 * @package Standard
 * @usage About Page (page-about.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$content = [
    'eyebrow' => __('The full machine', 'standard'),
    'title'   => __('We don\'t just sell you a machine. We are every step that keeps it running.', 'standard'),
    'lede'    => __('Most rollformer brands disappear after the truck leaves your shop. We engineer, manufacture, ship, train, and service the machine we sold you. The same company. The same building.', 'standard'),
];

$capabilities = [
    [
        'num'   => '01',
        'label' => __('Design', 'standard'),
        'body'  => __('Our engineering team designs every NTM machine in-house in Aurora. No licensed platforms, no rebadged frames.', 'standard'),
    ],
    [
        'num'   => '02',
        'label' => __('Engineer', 'standard'),
        'body'  => __('Mechanical, electrical, and controls engineering under one roof. The same engineers who designed UNIQ answer the support call about it.', 'standard'),
    ],
    [
        'num'   => '03',
        'label' => __('Manufacture', 'standard'),
        'body'  => __('Two factories, Aurora and Hermosillo. Machines are assembled, tested, and crated by NTM employees, not contract assemblers.', 'standard'),
    ],
    [
        'num'   => '04',
        'label' => __('Ship', 'standard'),
        'body'  => __('Factory-direct shipping to 40+ countries. Replacement parts ship from NTM inventory, not from a third-party warehouse on a queue.', 'standard'),
    ],
    [
        'num'   => '05',
        'label' => __('Train & service', 'standard'),
        'body'  => __('Operator training at the Aurora facility. Service techs on the phone. Field repairs and refurbishments by the people who built the machine.', 'standard'),
    ],
];
?>

<section class="bg-blue-900 py-16 lg:py-24" aria-labelledby="about-capabilities-title">
    <div class="container">
        <div class="grid lg:grid-cols-12 gap-10 lg:gap-16 mb-12 lg:mb-16">
            <div class="lg:col-span-7 grid gap-6 content-start">
                <p class="font-mono uppercase tracking-wider text-xs text-red-300">
                    <?php echo esc_html($content['eyebrow']); ?>
                </p>
                <h2 id="about-capabilities-title" class="font-sans font-medium text-white text-2xl md:text-3xl lg:text-[2.5rem] leading-tight tracking-tight">
                    <?php echo esc_html($content['title']); ?>
                </h2>
            </div>
            <div class="lg:col-span-5 flex lg:items-end">
                <p class="font-sans text-blue-200 text-base lg:text-lg leading-relaxed max-w-xl">
                    <?php echo esc_html($content['lede']); ?>
                </p>
            </div>
        </div>

        <ol class="border-t border-blue-700 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5">
            <?php foreach ($capabilities as $i => $cap) : ?>
                <li class="px-0 md:px-6 lg:px-7 py-10 lg:py-12
                    <?php
                    $borders = ['border-t border-blue-700'];
                    if ($i > 0) {
                        $borders[] = 'md:border-l';
                    }
                    if ($i === 0) {
                        $borders[] = 'border-t-0 md:border-t-0';
                    }
                    echo esc_attr(implode(' ', $borders));
                    ?>">
                    <div class="grid gap-4 md:gap-5">
                        <span class="font-mono text-sm text-red-300 tracking-wider">
                            <?php echo esc_html($cap['num']); ?>
                        </span>
                        <h3 class="font-sans font-medium text-white text-xl md:text-2xl leading-tight tracking-tight">
                            <?php echo esc_html($cap['label']); ?>
                        </h3>
                        <p class="font-sans text-blue-200 text-base leading-relaxed">
                            <?php echo esc_html($cap['body']); ?>
                        </p>
                    </div>
                </li>
            <?php endforeach; ?>
        </ol>
    </div>
</section>
