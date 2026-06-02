<?php
/**
 * Roof Panel vs Gutter — Run Both
 *
 * Short reframe for the buyer who realizes the answer might be "both."
 * Roofing and gutters sell to the same customer on the same job, so a
 * lot of NTM owners add the second machine for a second revenue stream.
 * Routes softly to both category pages and the guided chooser.
 *
 * @package Standard
 *
 * @usage Roof Panel vs Gutter (page-roof-panel-vs-gutter.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}
?>

<section class="section" aria-labelledby="vs-run-both-title">
    <div class="container">
        <div class="grid items-start gap-8 border border-blue-200 p-6 md:grid-cols-[1fr_auto] md:items-center md:gap-12 md:p-10 lg:p-12">

            <div class="grid max-w-2xl gap-4">
                <p class="section-eyebrow"><?php esc_html_e('Or run both', 'standard'); ?></p>
                <h2 id="vs-run-both-title" class="font-sans text-2xl font-medium tracking-tight text-balance text-blue-900 lg:text-3xl">
                    <?php esc_html_e('The Answer Is Often “Both.”', 'standard'); ?>
                </h2>
                <p class="text-base text-blue-600 text-pretty lg:text-lg">
                    <?php esc_html_e('Roofs and gutters get sold on the same job, to the same customer, by the same crew. Plenty of NTM owners start with one machine and add the other once the work is there, turning a single trade into two revenue streams without subbing either one out. If that sounds like your business, you do not have to choose.', 'standard'); ?>
                </p>
            </div>

            <div class="flex shrink-0 flex-col gap-3">
                <a href="<?php echo esc_url(\Standard\Url\internal('/machines/')); ?>" class="btn btn-primary justify-center">
                    <?php esc_html_e('See all machines', 'standard'); ?>
                    <?php icon('arrow-right', ['class' => 'w-5 h-5']); ?>
                </a>
                <a href="<?php echo esc_url(\Standard\Url\internal('/choose-your-machine/')); ?>" class="btn btn-outline-dark justify-center">
                    <?php esc_html_e('Help me choose', 'standard'); ?>
                </a>
            </div>

        </div>
    </div>
</section>
