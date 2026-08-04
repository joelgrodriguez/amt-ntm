<?php
/**
 * Template Name: ROI Calculator
 *
 * Portable Gutter Machine ROI Calculator. Renders the full static shell —
 * machine picker, inputs, and every result node — which
 * app/resources/js/modules/RoiCalculator.js fills in against the data-roi-*
 * markup contract below. Results are open (no lead gate); the CTA sits under
 * the payback card.
 *
 * Ported from the standalone prototype in /calculator (issue #131). Rates and
 * formulas are unchanged; see calculator/SPEC.md.
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

get_header();

while (have_posts()) :
    the_post();
    ?>

    <main id="primary" class="roi-calculator-page">
        <section class="section">
            <div class="container section-content">

                <div class="roi-calc" data-roi-calculator>

                    <header class="roi-intro">
                        <p class="section-eyebrow"><?php esc_html_e('Gutter Machine ROI', 'standard'); ?></p>
                        <h1 class="roi-intro__title">
                            <?php esc_html_e('Portable Gutter Machine ROI Calculator', 'standard'); ?>
                        </h1>
                        <p class="roi-intro__lede">
                            <?php esc_html_e('Enter your own coil cost and volume to see how quickly an NTM gutter machine pays for itself.', 'standard'); ?>
                        </p>
                    </header>

                    <!-- Step 1: machine -->
                    <div class="roi-step">
                        <div class="roi-step__head">
                            <span class="roi-step__badge" aria-hidden="true">1</span>
                            <h2 class="roi-step__title"><?php esc_html_e('Select your machine', 'standard'); ?></h2>
                        </div>

                        <div class="roi-machines" role="group" aria-label="<?php esc_attr_e('Machine', 'standard'); ?>" data-roi-machines>
                            <?php
                            // Prices mirror MACHINES in RoiCalculator.js — update both together.
                            // `slug` pulls the machine's product photo, so the picker stays in
                            // sync with the catalog rather than carrying its own images.
                            $roi_machines = [
                                '5in'   => [
                                    'slug'  => 'mach-ii-5-gutter-machine',
                                    'name'  => __('5" Machine', 'standard'),
                                    'desc'  => __('Produces 5" K-style gutter', 'standard'),
                                    'price' => __('$9,800', 'standard'),
                                ],
                                '6in'   => [
                                    'slug'  => 'mach-ii-6-gutter-machine',
                                    'name'  => __('6" Machine', 'standard'),
                                    'desc'  => __('Produces 6" K-style gutter', 'standard'),
                                    'price' => __('$10,500', 'standard'),
                                ],
                                'combo' => [
                                    'slug'  => 'mach-ii-5-6-combo-gutter-machine',
                                    'name'  => __('5"/6" Combo', 'standard'),
                                    'desc'  => __('Produces both 5" and 6" gutter', 'standard'),
                                    'price' => __('$12,300', 'standard'),
                                ],
                            ];

                            $roi_first = true;
                            foreach ($roi_machines as $roi_key => $roi_machine) :
                                $roi_product = get_page_by_path($roi_machine['slug'], OBJECT, 'product');
                                $roi_thumb   = $roi_product ? get_the_post_thumbnail(
                                    $roi_product->ID,
                                    'medium',
                                    [
                                        'class'   => 'roi-machine__img',
                                        'alt'     => '',
                                        'loading' => 'lazy',
                                    ]
                                ) : '';
                                ?>
                                <button
                                    type="button"
                                    class="roi-machine"
                                    data-roi-machine="<?php echo esc_attr($roi_key); ?>"
                                    aria-pressed="<?php echo $roi_first ? 'true' : 'false'; ?>"
                                >
                                    <?php if ($roi_thumb !== '') : ?>
                                        <span class="roi-machine__media"><?php echo $roi_thumb; ?></span>
                                    <?php endif; ?>
                                    <span class="roi-machine__name"><?php echo esc_html($roi_machine['name']); ?></span>
                                    <span class="roi-machine__desc"><?php echo esc_html($roi_machine['desc']); ?></span>
                                    <span class="roi-machine__price">
                                        <?php esc_html_e('Starting at', 'standard'); ?>
                                        <strong><?php echo esc_html($roi_machine['price']); ?></strong>
                                    </span>
                                </button>
                            <?php
                                $roi_first = false;
                            endforeach;
                            ?>
                        </div>
                    </div>

                    <!-- Steps 2 + 3 pair up side by side from 1024px so the figures
                         move while the buyer is still typing. Stacked below that. -->
                    <div class="roi-workspace">

                    <!-- Step 2: inputs -->
                    <div class="roi-step roi-step--inputs">
                        <div class="roi-step__head">
                            <span class="roi-step__badge" aria-hidden="true">2</span>
                            <h2 class="roi-step__title"><?php esc_html_e('Configure your setup', 'standard'); ?></h2>
                        </div>

                        <div class="roi-fields">
                            <div class="roi-field">
                                <label class="roi-field__label" for="roi-material">
                                    <?php esc_html_e('Material &amp; coil width', 'standard'); ?>
                                </label>
                                <select class="roi-field__control" id="roi-material" data-roi-material></select>
                            </div>

                            <div class="roi-field">
                                <label class="roi-field__label" for="roi-feet">
                                    <?php esc_html_e('Monthly production', 'standard'); ?>
                                    <span><?php esc_html_e('(linear feet)', 'standard'); ?></span>
                                </label>
                                <input
                                    class="roi-field__control"
                                    type="number"
                                    id="roi-feet"
                                    inputmode="numeric"
                                    value="9600"
                                    min="0"
                                    step="100"
                                    data-roi-feet
                                >
                                <p class="roi-field__hint">
                                    <?php esc_html_e('At 50 ft/min — 9,600 ft/month ≈ 192 minutes of run time', 'standard'); ?>
                                </p>
                            </div>

                            <div class="roi-field">
                                <label class="roi-field__label" for="roi-cost-lb">
                                    <?php esc_html_e('Your coil cost', 'standard'); ?>
                                    <span><?php esc_html_e('($ per pound)', 'standard'); ?></span>
                                </label>
                                <input
                                    class="roi-field__control"
                                    type="number"
                                    id="roi-cost-lb"
                                    inputmode="decimal"
                                    step="0.01"
                                    min="0"
                                    placeholder="<?php esc_attr_e('e.g. 3.99', 'standard'); ?>"
                                    data-roi-cost-lb
                                >
                                <p class="roi-field__hint">
                                    <?php esc_html_e('Auto-calculates $/ft using lbs/ft for the selected material. Required to see results.', 'standard'); ?>
                                </p>
                            </div>

                            <div class="roi-field roi-field--derived">
                                <p class="roi-derived__label" data-roi-derived-note>
                                    <?php esc_html_e('Enter $/lb above to calculate', 'standard'); ?>
                                </p>
                                <p class="roi-derived__value" data-roi-derived-value>—</p>
                                <p class="roi-derived__caption"><?php esc_html_e('Derived cost per foot', 'standard'); ?></p>
                            </div>

                            <div class="roi-field">
                                <label class="roi-field__label" for="roi-premade">
                                    <?php esc_html_e('Pre-made gutter cost', 'standard'); ?>
                                    <span><?php esc_html_e('($/ft — what you pay now)', 'standard'); ?></span>
                                </label>
                                <input
                                    class="roi-field__control"
                                    type="number"
                                    id="roi-premade"
                                    inputmode="decimal"
                                    step="0.01"
                                    min="0"
                                    placeholder="<?php esc_attr_e('e.g. 3.90', 'standard'); ?>"
                                    data-roi-premade
                                >
                                <p class="roi-field__hint">
                                    <?php esc_html_e('Auto-filled from retail pricing when available. Edit to use your own cost.', 'standard'); ?>
                                </p>
                            </div>

                            <div class="roi-field">
                                <label class="roi-field__label" for="roi-price">
                                    <?php esc_html_e('Your selling price', 'standard'); ?>
                                    <span><?php esc_html_e('($/ft — what you charge)', 'standard'); ?></span>
                                </label>
                                <input
                                    class="roi-field__control"
                                    type="number"
                                    id="roi-price"
                                    inputmode="decimal"
                                    step="0.01"
                                    min="0"
                                    placeholder="<?php esc_attr_e('e.g. 4.50', 'standard'); ?>"
                                    data-roi-price
                                >
                                <p class="roi-field__hint">
                                    <?php esc_html_e('Used to calculate your monthly revenue potential.', 'standard'); ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: results -->
                    <div class="roi-results">
                        <div class="roi-step__head">
                            <span class="roi-step__badge roi-step__badge--invert" aria-hidden="true">3</span>
                            <h2 class="roi-step__title roi-step__title--invert">
                                <?php esc_html_e('Your ROI results', 'standard'); ?>
                            </h2>
                        </div>

                        <div class="roi-cards" aria-live="polite">

                            <!-- Cost per foot comparison -->
                            <div class="roi-card roi-card--full roi-card--outline">
                                <p class="roi-card__label"><?php esc_html_e('Cost per foot comparison', 'standard'); ?></p>

                                <p class="roi-cmp__placeholder" data-roi-cmp-placeholder>
                                    <?php esc_html_e('Select a material to see cost comparison', 'standard'); ?>
                                </p>

                                <div class="roi-cmp" data-roi-cmp-rows hidden>
                                    <div class="roi-cmp__row">
                                        <span class="roi-cmp__label"><?php esc_html_e('Machine-made', 'standard'); ?></span>
                                        <span class="roi-cmp__track">
                                            <span class="roi-cmp__fill roi-cmp__fill--machine" style="width:0%" data-roi-cmp-machine-fill></span>
                                        </span>
                                        <span class="roi-cmp__amount" data-roi-cmp-machine-amt>—</span>
                                    </div>
                                    <div class="roi-cmp__row" data-roi-cmp-premade-row hidden>
                                        <span class="roi-cmp__label"><?php esc_html_e('Pre-made cost', 'standard'); ?></span>
                                        <span class="roi-cmp__track">
                                            <span class="roi-cmp__fill roi-cmp__fill--premade" style="width:0%" data-roi-cmp-premade-fill></span>
                                        </span>
                                        <span class="roi-cmp__amount" data-roi-cmp-premade-amt>—</span>
                                    </div>
                                    <p class="roi-cmp__note" data-roi-cmp-note></p>
                                </div>
                            </div>

                            <div class="roi-card">
                                <p class="roi-card__label"><?php esc_html_e('Monthly production', 'standard'); ?></p>
                                <p class="roi-card__value" data-roi-out-feet>—</p>
                                <p class="roi-card__sub"><?php esc_html_e('linear feet / month', 'standard'); ?></p>
                            </div>

                            <div class="roi-card">
                                <p class="roi-card__label"><?php esc_html_e('Monthly material cost', 'standard'); ?></p>
                                <p class="roi-card__value" data-roi-out-material-cost>—</p>
                                <p class="roi-card__sub"><?php esc_html_e('coil stock at your machine-made rate', 'standard'); ?></p>
                            </div>

                            <div class="roi-card roi-card--accent">
                                <p class="roi-card__label"><?php esc_html_e('Monthly revenue potential', 'standard'); ?></p>
                                <p class="roi-card__value" data-roi-out-revenue>—</p>
                                <p class="roi-card__sub"><?php esc_html_e('at your selling price × monthly feet', 'standard'); ?></p>
                            </div>

                            <div class="roi-card roi-card--accent">
                                <p class="roi-card__label"><?php esc_html_e('Monthly savings vs. pre-made', 'standard'); ?></p>
                                <p class="roi-card__value" data-roi-out-savings>—</p>
                                <p class="roi-card__sub" data-roi-out-savings-sub>
                                    <?php esc_html_e('vs. buying pre-made gutter', 'standard'); ?>
                                </p>
                            </div>

                            <!-- Payback -->
                            <div class="roi-card roi-card--full roi-card--payback">
                                <p class="roi-card__label"><?php esc_html_e('Machine payback period', 'standard'); ?></p>

                                <div data-roi-payback-figures hidden>
                                    <p class="roi-payback__months" data-roi-payback-months>—</p>
                                    <p class="roi-payback__weeks" data-roi-payback-weeks>—</p>
                                    <p class="roi-payback__basis" data-roi-payback-basis></p>
                                </div>

                                <p class="roi-payback__message" data-roi-payback-message>
                                    <?php esc_html_e('Enter your coil cost ($/lb) above to calculate payback period', 'standard'); ?>
                                </p>
                            </div>
                        </div>

                        <p class="roi-disclaimer">
                            <?php esc_html_e('* Material cost estimates at 50 ft/min production speed. Does not include labor, overhead, installation, or other operating costs. Actual results will vary. Contact NTM for a personalized quote.', 'standard'); ?>
                        </p>
                    </div>

                    </div><!-- /.roi-workspace -->

                    <!-- CTA (results are open; this is the conversion step) -->
                    <div class="roi-cta">
                        <h2 class="roi-cta__title"><?php esc_html_e('Ready for real numbers?', 'standard'); ?></h2>
                        <p class="roi-cta__desc">
                            <?php esc_html_e('These are estimates. An NTM specialist can quote your exact machine, coil program, and freight.', 'standard'); ?>
                        </p>
                        <div class="roi-cta__actions">
                            <a class="btn btn-primary" href="<?php echo esc_url(home_url('/contact/')); ?>">
                                <?php esc_html_e('Request a quote', 'standard'); ?>
                            </a>
                            <a class="btn btn-secondary" href="tel:+13032940538">
                                <?php esc_html_e('Call 303.294.0538', 'standard'); ?>
                            </a>
                        </div>
                    </div>

                </div>

            </div>
        </section>
    </main>

    <?php
endwhile;

get_footer();
