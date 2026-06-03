<?php
/**
 * First-Time Buyer Playlist — Chapters
 *
 * The spine of the page: six ordered chapters that walk a first-time
 * buyer from "what is this" to "what happens after I buy." Each chapter
 * is a numbered section, and the numbers are honest here because this is
 * a real watch-in-order sequence, not decorative scaffolding.
 *
 * Each card is resolved from a live video post ID at render time, so the
 * thumbnail, title, and link always match the database. Cards link to
 * the existing single-video page (single-video.php), which renders both
 * the YouTube and Wistia sources in the library. We never embed a player
 * here, so the page stays fast and the source mix is single-video's
 * problem, not ours.
 *
 * IDs are the source of truth. If a video is unpublished or deleted, its
 * card is skipped silently rather than rendering a dead tile.
 *
 * @package Standard
 *
 * @usage First-Time Buyer Playlist (page-first-time-buyer-playlist.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

// Ordered curriculum. Each chapter is a beat in the buyer's journey;
// the video IDs are hand-picked, live `video` CPT posts.
$chapters = [
    [
        'kicker'  => __('Understand the machine', 'standard'),
        'title'   => __('What Is a Portable Rollformer?', 'standard'),
        'intro'   => __('Two minutes to understand what these machines actually do, and the myths to clear out of your head before you spend a dollar.', 'standard'),
        'video_ids' => [1631, 5615],
    ],
    [
        'kicker'  => __('Is it a real business?', 'standard'),
        'title'   => __('See the Business, Not Just the Machine', 'standard'),
        'intro'   => __('Owners who started with no roofing background, and the math behind rolling your own panels instead of buying them.', 'standard'),
        'video_ids' => [1657, 16190, 20022],
    ],
    [
        'kicker'  => __('Will it pay off?', 'standard'),
        'title'   => __('Run the Numbers', 'standard'),
        'intro'   => __('How to estimate what a machine earns back, and how owners pay for one. We point you to the money pages for the full breakdown.', 'standard'),
        'video_ids' => [17310, 5790],
    ],
    [
        'kicker'  => __('Roof panels or gutters?', 'standard'),
        'title'   => __('Pick Your Lane', 'standard'),
        'intro'   => __('The two families of NTM machines make different products for different crews. These help you find which side of the fork is yours.', 'standard'),
        'video_ids' => [16356, 15821],
    ],
    [
        'kicker'  => __('Which machine, and what to ask', 'standard'),
        'title'   => __('Buy Without Regret', 'standard'),
        'intro'   => __('What to know before you buy, the mistakes that cause buyer’s remorse, and what buying from NTM actually involves.', 'standard'),
        'video_ids' => [18901, 18002, 4564],
    ],
    [
        'kicker'  => __('After you buy', 'standard'),
        'title'   => __('Your First Week as an Owner', 'standard'),
        'intro'   => __('What shows up, what to do first, and how to get running. The curve is shorter than first-timers expect.', 'standard'),
        'video_ids' => [1678, 10465],
    ],
];

/**
 * Render one video card from a live post ID.
 *
 * Resolves the thumbnail, title, and permalink at render time so the
 * card never drifts from the database. Returns nothing and prints
 * nothing if the post is missing or not a published video.
 *
 * @param int $id Video post ID.
 */
$render_video_card = static function (int $id): void {
    $post = get_post($id);
    if (!$post instanceof \WP_Post || $post->post_status !== 'publish' || $post->post_type !== 'video') {
        return;
    }

    $permalink = get_permalink($id);
    $title     = get_the_title($id);
    $thumb     = get_the_post_thumbnail_url($id, 'medium_large');
    ?>
    <a
        href="<?php echo esc_url($permalink); ?>"
        class="reveal group flex flex-col overflow-hidden border border-blue-200 bg-white transition-colors hover:border-blue-400 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
    >
        <div class="relative aspect-video overflow-hidden bg-blue-100">
            <?php if ($thumb) : ?>
                <img
                    src="<?php echo esc_url($thumb); ?>"
                    alt=""
                    class="h-full w-full object-cover transition-transform duration-500 ease-out group-hover:scale-[1.03] motion-reduce:transition-none motion-reduce:group-hover:scale-100"
                    loading="lazy" decoding="async"
                >
            <?php endif; ?>
            <span class="absolute inset-0 flex items-center justify-center" aria-hidden="true">
                <span class="flex h-14 w-14 items-center justify-center rounded-full bg-blue-900/90 text-white ring-1 ring-white/25 backdrop-blur-sm transition-colors group-hover:bg-blue-500">
                    <?php icon('play', ['class' => 'w-6 h-6']); ?>
                </span>
            </span>
        </div>
        <div class="flex flex-1 flex-col gap-2 p-5">
            <h3 class="font-sans text-base font-medium tracking-tight text-balance text-blue-900 transition-colors group-hover:text-blue-500 lg:text-lg">
                <?php echo esc_html($title); ?>
            </h3>
            <span class="mt-auto inline-flex items-center gap-1.5 pt-2 font-mono text-xs uppercase tracking-mono-meta text-blue-400">
                <?php esc_html_e('Watch', 'standard'); ?>
                <?php icon('arrow-right', ['class' => 'w-3.5 h-3.5']); ?>
            </span>
        </div>
    </a>
    <?php
};
?>

<?php
// Each chapter renders as its own full-width band. The bands alternate
// blue-50 / white so a long six-chapter scroll has a visible "new beat"
// boundary at every chapter instead of one undifferentiated column.
// Alternation tracks a rendered counter (not the array index), so a
// skipped chapter never leaves two same-tone bands adjacent. Starting on
// blue-50 makes the final chapter land on white, so the step into the
// blue-50 "after" rail below reads as a real tone change rather than two
// blue-50 bands butting together.
$rendered = 0;
foreach ($chapters as $i => $chapter) :
    $number = $i + 1;
    // Resolve the chapter's cards once so an all-missing chapter can be
    // skipped rather than render an empty grid.
    $live_ids = array_values(array_filter($chapter['video_ids'], static function (int $id): bool {
        $post = get_post($id);
        return $post instanceof \WP_Post && $post->post_status === 'publish' && $post->post_type === 'video';
    }));
    if (empty($live_ids)) {
        continue;
    }
    $band = ($rendered % 2 === 0) ? 'bg-blue-50' : 'bg-white';
    $rendered++;
?>
    <section
        id="chapter-<?php echo esc_attr((string) $number); ?>"
        class="section scroll-mt-24 <?php echo esc_attr($band); ?>"
        aria-labelledby="chapter-<?php echo esc_attr((string) $number); ?>-title"
    >
        <div class="container section-content">

            <div class="section-header-left max-w-2xl">
                <p class="section-eyebrow">
                    <?php
                    /* translators: %02d: chapter number, zero-padded. */
                    printf(esc_html__('Chapter %02d · %s', 'standard'), (int) $number, esc_html($chapter['kicker']));
                    ?>
                </p>
                <div class="section-divider"></div>
                <h2
                    id="chapter-<?php echo esc_attr((string) $number); ?>-title"
                    class="font-sans text-2xl font-medium tracking-tight text-balance text-blue-900 lg:text-3xl"
                >
                    <?php echo esc_html($chapter['title']); ?>
                </h2>
                <p class="section-subtitle text-pretty">
                    <?php echo esc_html($chapter['intro']); ?>
                </p>
            </div>

            <div class="stagger grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <?php foreach ($live_ids as $id) {
                    $render_video_card((int) $id);
                } ?>
            </div>

        </div>
    </section>
<?php endforeach; ?>
