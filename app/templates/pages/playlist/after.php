<?php
/**
 * First-Time Buyer Playlist — After You Watch
 *
 * The playlist teaches; it does not convert on its own. Once a first
 * buyer has watched the chapters, this rail hands them off to the three
 * pages that take the next concrete step: resolve roof-vs-gutter, get
 * matched to a machine, or work out the money. Same grammar as the
 * vs/keep-reading rail, but pointed at decision pages instead of
 * articles.
 *
 * @package Standard
 *
 * @usage First-Time Buyer Playlist (page-first-time-buyer-playlist.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$next_steps = [
    [
        'kicker' => __('Still deciding', 'standard'),
        'title'  => __('Roof Panel or Gutter Machine?', 'standard'),
        'text'   => __('See the two families side by side and find which one matches the work you do.', 'standard'),
        'cta'    => __('Compare the two', 'standard'),
        'url'    => '/roof-panel-vs-gutter/',
    ],
    [
        'kicker' => __('Ready to look', 'standard'),
        'title'  => __('Find Your Machine', 'standard'),
        'text'   => __('Answer a few questions and we will point you to the machine that fits your business.', 'standard'),
        'cta'    => __('Take the quiz', 'standard'),
        'url'    => '/choose-your-machine/',
    ],
    [
        'kicker' => __('Talk it through', 'standard'),
        'title'  => __('Talk to a Specialist', 'standard'),
        'text'   => __('Tell us about your business and get a straight answer on machine, price, and lead time.', 'standard'),
        'cta'    => __('Get in touch', 'standard'),
        'url'    => '/contact/',
    ],
];
?>

<section class="section bg-blue-50 border-t border-blue-200" aria-labelledby="playlist-after-title">
    <div class="container section-content">

        <div class="section-header-left max-w-2xl">
            <p class="section-eyebrow text-blue-600"><?php esc_html_e('When you’re done watching', 'standard'); ?></p>
            <div class="section-divider"></div>
            <h2 id="playlist-after-title" class="section-title">
                <?php esc_html_e('Take the Next Step', 'standard'); ?>
            </h2>
        </div>

        <div class="grid gap-px border border-blue-200 bg-blue-200 md:grid-cols-3">
            <?php foreach ($next_steps as $step) : ?>
                <div class="flex flex-col gap-4 bg-blue-50 p-6 lg:p-8">
                    <p class="font-mono text-xs uppercase tracking-mono-label text-blue-600">
                        <?php echo esc_html($step['kicker']); ?>
                    </p>
                    <h3 class="font-sans text-xl font-medium tracking-tight text-balance text-blue-900">
                        <?php echo esc_html($step['title']); ?>
                    </h3>
                    <p class="text-base text-blue-600 text-pretty">
                        <?php echo esc_html($step['text']); ?>
                    </p>
                    <a
                        href="<?php echo esc_url(\Standard\Url\internal($step['url'])); ?>"
                        class="group mt-auto inline-flex min-h-11 items-center gap-2 pt-2 font-mono text-sm uppercase tracking-mono-label text-blue-700 transition-colors hover:text-blue-500 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                    >
                        <?php echo esc_html($step['cta']); ?>
                        <?php icon('arrow-right', ['class' => 'w-4 h-4 transition-transform group-hover:translate-x-0.5 motion-reduce:transition-none motion-reduce:group-hover:translate-x-0']); ?>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>
