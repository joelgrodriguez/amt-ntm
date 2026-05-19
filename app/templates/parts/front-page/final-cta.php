<?php
/**
 * Final CTA — Front Page
 *
 * Spec-sheet last line. Two monospaced rows, each a door into the buyer's
 * chosen path. The page's "two doors, equal weight" principle gets a literal
 * compositional form: two rows, identical weight, sibling treatment.
 *
 * No centered hero stack, no big subheading, no SaaS-template close. The
 * page ends the way a technical drawing ends: a legend, two entries, a
 * trailing rule.
 *
 * @package Standard
 *
 * @usage Front Page (front-page.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$content = [
    'eyebrow' => __('Two doors · equal weight', 'standard'),
    'sr_title' => __('Ready to take control of your business', 'standard'),
];

$doors = [
    [
        'label' => __('Configure', 'standard'),
        'meta'  => __('Self-serve quote', 'standard'),
        'url'   => '/configurator/',
    ],
    [
        'label' => __('Specialist', 'standard'),
        'meta'  => __('Talk to a human', 'standard'),
        'url'   => '#contact',
    ],
];
?>

<section class="bg-white border-t border-blue-200" aria-labelledby="final-cta-title">
    <div class="container">
        <h2 id="final-cta-title" class="sr-only">
            <?php echo esc_html($content['sr_title']); ?>
        </h2>

        <!-- Eyebrow rule -->
        <div class="flex items-center gap-4 pt-12 lg:pt-16 pb-4">
            <span class="w-2 h-2 bg-red shrink-0" aria-hidden="true"></span>
            <span class="font-mono uppercase tracking-wider text-xs text-blue-700">
                <?php echo esc_html($content['eyebrow']); ?>
            </span>
            <span class="flex-1 h-px bg-blue-200" aria-hidden="true"></span>
        </div>

        <!-- Doors: two mono spec rows, each a full-width link -->
        <ul class="border-y border-blue-200">
            <?php foreach ($doors as $i => $door) : ?>
                <li class="<?php echo $i > 0 ? 'border-t border-blue-200' : ''; ?>">
                    <a
                        href="<?php echo esc_url(\Standard\Url\internal($door['url'])); ?>"
                        class="group flex items-baseline gap-4 py-6 lg:py-8 no-underline focus-visible:outline-2 focus-visible:outline-blue-500 focus-visible:outline-offset-[-2px] hover:bg-blue-50 transition-colors duration-200"
                    >
                        <span class="font-mono uppercase tracking-wider text-xs text-blue-500 w-8 shrink-0 pl-1 md:pl-3 md:w-12">
                            <?php echo sprintf('%02d', $i + 1); ?>
                        </span>
                        <span class="font-sans font-medium text-blue-900 text-2xl md:text-4xl lg:text-5xl leading-none tracking-tight group-hover:text-blue-500 transition-colors duration-200">
                            <?php echo esc_html($door['label']); ?>
                        </span>
                        <span class="hidden md:inline-block font-mono uppercase tracking-wider text-xs text-blue-600 ml-2">
                            <?php echo esc_html($door['meta']); ?>
                        </span>
                        <span class="ml-auto pr-1 md:pr-3 text-blue-400 group-hover:text-blue-500 transition-colors duration-200" aria-hidden="true">
                            <?php icon('arrow-right', ['class' => 'w-5 h-5 md:w-6 md:h-6']); ?>
                        </span>
                    </a>
                    <span class="md:hidden block font-mono uppercase tracking-wider text-xs text-blue-600 pl-12 pb-4 -mt-4">
                        <?php echo esc_html($door['meta']); ?>
                    </span>
                </li>
            <?php endforeach; ?>
        </ul>

        <!-- Closing signature row -->
        <div class="flex items-baseline justify-between pt-4 pb-12 lg:pb-16 font-mono uppercase tracking-wider text-xs text-blue-400">
            <span><?php esc_html_e('End of sheet', 'standard'); ?></span>
            <span><?php esc_html_e('New Tech Machinery', 'standard'); ?></span>
        </div>
    </div>
</section>
