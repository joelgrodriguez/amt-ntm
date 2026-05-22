<?php
/**
 * Contact Routing Strip
 *
 * Three-column hairline strip above the contact lead-form grid.
 * Each cell is a distinct visitor path: quote, service, distributor.
 * Uses the §8.5 cross-pattern: full-bleed horizontal seams above and
 * below, full-height column dividers between cells, clean intersections.
 *
 * @package Standard
 *
 * @usage Via get_template_part() with args:
 *   - form_anchor: string (anchor id of the lead form, e.g. 'lead-form-210')
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$form_anchor = isset($args['form_anchor']) ? (string) $args['form_anchor'] : '';

$cells = [
    [
        'eyebrow' => __('Quote · Sales', 'standard'),
        'title'   => __('Talk to a rollforming specialist', 'standard'),
        'cta'     => __('Use the form below', 'standard'),
        'href'    => $form_anchor !== '' ? '#' . $form_anchor : '#form',
        'icon'    => 'arrow-down',
        'newtab'  => false,
    ],
    [
        'eyebrow' => __('Service · Support', 'standard'),
        'title'   => __('Already own an NTM machine?', 'standard'),
        'cta'     => __('Visit the Support Center', 'standard'),
        'href'    => 'https://support.newtechmachinery.com/',
        'icon'    => 'external-link',
        'newtab'  => true,
    ],
    [
        'eyebrow' => __('Dealer · Distributor', 'standard'),
        'title'   => __('Carry NTM machines', 'standard'),
        'cta'     => __('Become a distributor', 'standard'),
        'href'    => esc_url(home_url('/become-an-ntm-distributor/')),
        'icon'    => 'arrow-right',
        'newtab'  => false,
    ],
];
?>

<section class="bg-blue-50 border-t border-b border-blue-200" aria-label="<?php esc_attr_e('Choose a contact path', 'standard'); ?>">
    <div class="container">
        <ul class="grid grid-cols-1 md:grid-cols-3 list-none m-0 p-0 md:divide-x md:divide-blue-200 divide-y md:divide-y-0">
            <?php foreach ($cells as $cell) : ?>
                <li class="m-0 p-0">
                    <a
                        href="<?php echo esc_url($cell['href']); ?>"
                        <?php if ($cell['newtab']) : ?>target="_blank" rel="noreferrer noopener"<?php endif; ?>
                        class="group grid gap-3 px-6 py-8 md:px-8 md:py-10 no-underline hover:bg-white focus-visible:bg-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-blue-500 transition-colors duration-200 h-full"
                    >
                        <p class="section-eyebrow m-0">
                            <?php echo esc_html($cell['eyebrow']); ?>
                        </p>
                        <h2 class="font-sans text-xl md:text-2xl font-medium tracking-tight text-blue-900 m-0 leading-tight">
                            <?php echo esc_html($cell['title']); ?>
                        </h2>
                        <p class="font-mono text-sm text-blue-700 group-hover:text-blue-500 m-0 inline-flex items-center gap-2 mt-1">
                            <span><?php echo esc_html($cell['cta']); ?></span>
                            <?php icon($cell['icon'], ['class' => 'w-4 h-4 shrink-0']); ?>
                        </p>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>
