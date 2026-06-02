<?php
/**
 * Start Here — Owner Voices
 *
 * A short, static strip of owner quotes hand-picked to the start-a-
 * business frame (people who jumped into the metal trade and grew).
 * Deliberately NOT the home page's autoplay slider: this is three fixed
 * quotes, no JS, so it reinforces the page without cloning that surface.
 * Sits right after the business case, where "is this real for someone
 * like me" doubt peaks, and answers it with named, located peers.
 *
 * Portraits are the public marketing assets on the production CDN, the
 * same source the home page testimonial strip uses.
 *
 * @package Standard
 *
 * @usage Start Here (page-start-here.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$cdn = 'https://newtechmachinery.com/wp-content/uploads/2025/06';

// Three owners whose stories are specifically about starting/growing a
// business, not generic praise. Garay names the start-a-business move
// outright; Andrews built Classic Metals (the proof link in the-case);
// Cisneros is the gutter owner whose install photo runs earlier on the page.
$voices = [
    [
        'quote'    => __('If you’re trying to jump into the metal business, contact New Tech Machinery. They’re going to give you the information that you need, and they’re going to help you grow your business.', 'standard'),
        'name'     => 'Danaik Garay',
        'company'  => 'Alsteel Metal Manufacturing',
        'location' => 'Fort Myers, Florida',
        'slug'     => 'Danaik-1',
    ],
    [
        'quote'    => __('What attracted me to New Tech Machinery was the quality of the machine and the ease of switching out dies. If you do have a problem, there’s a sales team you can call.', 'standard'),
        'name'     => 'Todd Andrews',
        'company'  => 'Classic Metals Inc.',
        'location' => 'Chester, South Carolina',
        'slug'     => 'Todd',
    ],
    [
        'quote'    => __('New Tech Machinery has always been the top-of-the-line machine for gutters, so I wanted something I can rely on.', 'standard'),
        'name'     => 'Abel Cisneros',
        'company'  => 'C&S Rain Gutters',
        'location' => 'Greeley, Colorado',
        'slug'     => 'Abel',
    ],
];
?>

<section class="section bg-blue-900 text-white" aria-labelledby="start-here-voices-title">
    <div class="container section-content">

        <div class="section-header-left max-w-2xl">
            <p class="section-eyebrow text-blue-300"><?php esc_html_e('From the field', 'standard'); ?></p>
            <div class="section-divider"></div>
            <h2 id="start-here-voices-title" class="section-title text-white">
                <?php esc_html_e('People Who Started Where You Are', 'standard'); ?>
            </h2>
        </div>

        <ul class="grid gap-px border border-blue-800 bg-blue-800 md:grid-cols-3" role="list">
            <?php foreach ($voices as $voice) : ?>
                <li>
                    <blockquote class="flex h-full flex-col gap-6 bg-blue-900 p-6 lg:p-8">
                        <p class="font-sans text-lg text-blue-100 text-pretty lg:text-xl">
                            &ldquo;<?php echo esc_html($voice['quote']); ?>&rdquo;
                        </p>
                        <footer class="mt-auto flex items-center gap-4 border-t border-blue-800 pt-5">
                            <img
                                src="<?php echo esc_url($cdn . '/' . $voice['slug'] . '-150x150.png'); ?>"
                                srcset="<?php echo esc_url($cdn . '/' . $voice['slug'] . '-150x150.png'); ?> 150w, <?php echo esc_url($cdn . '/' . $voice['slug'] . '-300x300.png'); ?> 300w"
                                sizes="56px"
                                alt=""
                                role="presentation"
                                width="56"
                                height="56"
                                class="h-14 w-14 shrink-0 object-cover"
                                loading="lazy"
                                decoding="async"
                            >
                            <cite class="not-italic grid gap-0.5">
                                <span class="font-mono text-sm font-medium uppercase tracking-mono-meta text-white">
                                    <?php echo esc_html($voice['name']); ?>
                                </span>
                                <span class="font-sans text-sm text-blue-300">
                                    <?php echo esc_html($voice['company']); ?>
                                </span>
                                <span class="font-mono text-[11px] uppercase tracking-mono-meta text-blue-300">
                                    <?php echo esc_html($voice['location']); ?>
                                </span>
                            </cite>
                        </footer>
                    </blockquote>
                </li>
            <?php endforeach; ?>
        </ul>

        <div>
            <a
                href="<?php echo esc_url(\Standard\Url\internal('/learning-center/category/testimonials/')); ?>"
                class="group inline-flex items-center gap-2 font-mono text-xs uppercase tracking-mono-label text-blue-300 transition-colors hover:text-white"
            >
                <?php esc_html_e('Read more owner stories', 'standard'); ?>
                <span class="transition-transform group-hover:translate-x-0.5" aria-hidden="true">
                    <?php icon('arrow-right', ['class' => 'w-4 h-4']); ?>
                </span>
            </a>
        </div>

    </div>
</section>
