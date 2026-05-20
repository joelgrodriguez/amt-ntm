<?php
/**
 * About — Origin
 *
 * Single-column narrative with inline data callouts in the right margin
 * on lg+ (collapses below the narrative on mobile). Replaces the earlier
 * two-column narrative + spec-ledger layout, which doubled the same
 * facts in prose and table.
 *
 * H2 is a proof line ('Five firsts, forty countries, two factories.'),
 * not another leadership claim. The leadership claim is staked once in
 * the manifesto; this section proves it with the dated receipts.
 *
 * Larry Coben gets one earned mention in the narrative (the founding
 * sentence) rather than a footnote in a removed spec ledger.
 *
 * @package Standard
 * @usage About Page (page-about.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$content = [
    'eyebrow' => __('Origin', 'standard'),
    'title'   => __('Five firsts, forty countries, two factories.', 'standard'),
    'p1'      => __('The SSP Roof Panel Machine shipped in 1991, and the modern portable roof panel category followed. Three years later the MACH II Seamless Gutter Machine did the same for gutters. NTM didn\'t join the category. NTM made it.', 'standard'),
    'p2'      => __('NTM was the first portable rollforming company to commercially produce a polyurethane drive roller machine with separate forming rollers. Today almost every portable rollformer on the market uses that approach. The patents have aged out. The lead hasn\'t.', 'standard'),
    'p3'      => __('Larry Coben founded the company in Denver in 1991 and stayed long enough to ship four product platforms. The engineering instincts he set are still the ones our machines are built on.', 'standard'),
];

// Right-margin callouts on lg+, inline below narrative on mobile.
// Three is the cap; more turns the gutter back into a ledger.
$callouts = [
    [
        'k' => __('Headquarters', 'standard'),
        'v' => 'Aurora, Colorado',
    ],
    [
        'k' => __('Second plant', 'standard'),
        'v' => __('Hermosillo, Mexico (2004)', 'standard'),
    ],
];
?>

<section class="bg-white py-16 lg:py-24 border-t border-blue-200" aria-labelledby="about-origin-title">
    <div class="container">

        <!-- Eyebrow + headline -->
        <div class="max-w-4xl mb-12 lg:mb-16">
            <p class="font-mono uppercase tracking-wider text-xs text-red mb-5">
                <?php echo esc_html($content['eyebrow']); ?>
            </p>
            <h2 id="about-origin-title" class="font-sans font-medium text-blue-900 text-2xl md:text-3xl lg:text-[2.5rem] leading-tight tracking-tight">
                <?php echo esc_html($content['title']); ?>
            </h2>
        </div>

        <!-- Narrative column with inline callouts in the right gutter on lg+ -->
        <div class="grid lg:grid-cols-12 gap-10 lg:gap-16">

            <!-- Narrative -->
            <div class="lg:col-span-8">
                <div class="grid gap-6 font-sans text-blue-700 text-base lg:text-lg leading-relaxed max-w-2xl">
                    <p><?php echo esc_html($content['p1']); ?></p>
                    <p><?php echo esc_html($content['p2']); ?></p>
                    <p><?php echo esc_html($content['p3']); ?></p>
                </div>
            </div>

            <!-- Marginalia: data callouts in the right gutter on lg+,
                 stacked below narrative on mobile. Mono labels, sans
                 values, hairline above each item, no surrounding box. -->
            <aside class="lg:col-span-4 grid gap-6 content-start" aria-label="<?php esc_attr_e('Company data', 'standard'); ?>">
                <?php foreach ($callouts as $callout) : ?>
                    <div class="grid gap-1 border-t border-blue-200 pt-4">
                        <span class="font-mono uppercase tracking-wider text-xs text-blue-500">
                            <?php echo esc_html($callout['k']); ?>
                        </span>
                        <span class="font-sans font-medium text-blue-900 text-base lg:text-lg leading-snug">
                            <?php echo esc_html($callout['v']); ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </aside>

        </div>

    </div>
</section>
