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

use function Standard\Images\responsive_image;

$configurator_url = \Standard\Url\internal('/configurator/');
$walkthrough_url  = \Standard\Url\internal('/learning-center/how-to-build-and-finance-your-ntm-rollformer-all-on-one-site/');

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

        <div class="finance-flow__body">
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

            <figure class="finance-flow__shot">
                <span class="finance-flow__shot-frame">
                    <?php
                    responsive_image(
                        content_url('/uploads/2024/08/configurator.png'),
                        __('The NTM configurator with a machine build in progress', 'standard'),
                        'large',
                        ['class' => 'finance-flow__shot-img']
                    );
                    ?>
                </span>
                <figcaption class="finance-flow__shot-caption">
                    <?php esc_html_e('The configurator — build, price, and apply in one screen', 'standard'); ?>
                </figcaption>
            </figure>
        </div>

        <div class="finance-flow__actions">
            <a href="<?php echo esc_url($configurator_url); ?>" class="btn btn-emphasis btn--commit">
                <?php esc_html_e('Open the configurator', 'standard'); ?>
                <?php icon('arrow-right', ['class' => 'w-5 h-5']); ?>
            </a>
            <a href="<?php echo esc_url($walkthrough_url); ?>" class="finance-flow__alt-link">
                <?php esc_html_e('See how it works, step by step', 'standard'); ?>
                <span aria-hidden="true">&rarr;</span>
            </a>
        </div>

    </div>
</section>
