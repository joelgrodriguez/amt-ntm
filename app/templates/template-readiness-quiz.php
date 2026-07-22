<?php
/**
 * Template Name: Readiness Quiz
 *
 * Panel Machine Readiness Assessment. Renders the quiz shell (intro →
 * questions → lead capture → results); all interaction is driven by
 * app/resources/js/modules/ReadinessQuiz.js against the data-quiz-* markup
 * contract below. Results unlock only after the HubSpot lead form is submitted.
 *
 * Replaces the dead Abacus.AI iframe that previously lived in this page's
 * content (issue #94). Logic documented in docs/specs/readiness-quiz-spec.md.
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

const READINESS_QUIZ_FORM_ID = '21d8e65b-52f3-4fb2-9fb7-c1463b90d843';

get_header();

while (have_posts()) :
    the_post();
    $post_id = get_the_ID();
    ?>

    <main id="primary" class="readiness-quiz-page">
        <section class="section">
            <div class="container section-content max-w-3xl">

                <div
                    class="readiness-quiz"
                    data-readiness-quiz
                >
                    <!-- Progress -->
                    <div class="quiz-progress" aria-hidden="false">
                        <div class="quiz-progress__head">
                            <p class="quiz-progress__label" data-quiz-progress-label>
                                <?php esc_html_e('Question 1', 'standard'); ?>
                            </p>
                            <span class="quiz-progress__pct" data-quiz-progress-pct>0%</span>
                        </div>
                        <div class="quiz-progress__track">
                            <div class="quiz-progress__fill" data-quiz-progress style="width:0%"></div>
                        </div>
                    </div>

                    <div class="quiz-card" data-quiz-card>
                        <!-- Card-anchored previous-question button (JS shows from Q2) -->
                        <button type="button" class="quiz-back" data-quiz-back hidden aria-label="<?php esc_attr_e('Previous question', 'standard'); ?>">
                            <?php icon('arrow-left', ['class' => 'w-4 h-4', 'aria-hidden' => 'true']); ?>
                        </button>

                        <!-- Intro -->
                        <div class="quiz-intro" data-quiz-intro>
                            <p class="section-eyebrow"><?php esc_html_e('Panel Machine Readiness', 'standard'); ?></p>
                            <h1 class="quiz-intro__title">
                                <?php esc_html_e('Is your business ready for a portable rollforming machine?', 'standard'); ?>
                            </h1>
                            <p class="quiz-intro__lede">
                                <?php esc_html_e('Answer a few questions about your operation and we’ll estimate your readiness and recommend the machine that fits.', 'standard'); ?>
                            </p>
                            <button type="button" class="btn btn-primary" data-quiz-start>
                                <?php esc_html_e('Start the assessment', 'standard'); ?>
                            </button>
                        </div>

                        <!-- Questions (rendered by JS) -->
                        <div class="quiz-questions" data-quiz-questions hidden></div>

                        <!-- Results (rendered by JS after lead form submit) -->
                        <div class="quiz-results" data-quiz-results hidden></div>
                    </div>

                    <?php
                    // Pre-rendered machine product cards (one per recommendation
                    // key). The recommendation is chosen client-side, so we render
                    // all three server-side via the canonical card and let the JS
                    // reveal + open-in-new-tab the matched one. Keys match
                    // MACHINES in ReadinessQuiz.js.
                    $rec_cards = [
                        'SSQ3' => 'ssq3-multipro',
                        'SSH'  => 'ssh-roof-panel-machine',
                        'SSR'  => 'ssr-multipro-jr-roof-panel-machine',
                    ];
                    ?>
                    <div class="quiz-rec-cards" data-quiz-rec-cards hidden>
                        <?php foreach ($rec_cards as $key => $slug) :
                            $rec_post = get_page_by_path($slug, OBJECT, 'product');
                            if (!$rec_post) {
                                continue;
                            }
                            ?>
                            <div class="quiz-rec-card" data-quiz-rec-card="<?php echo esc_attr($key); ?>" hidden>
                                <?php
                                get_template_part('templates/parts/card-product', null, [
                                    'product' => \Standard\Search\get_product_card_data((int) $rec_post->ID),
                                ]);
                                ?>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Lead capture (required gate before results; primary panel while gated) -->
                    <div class="quiz-lead" data-quiz-lead hidden>
                        <div class="quiz-lead__intro">
                            <p class="quiz-lead__eyebrow" data-quiz-lead-eyebrow>
                                <?php esc_html_e('Assessment complete', 'standard'); ?>
                            </p>
                            <h2 class="quiz-lead__title" data-quiz-lead-title>
                                <?php esc_html_e('Unlock your readiness results', 'standard'); ?>
                            </h2>
                            <p class="quiz-lead__desc" data-quiz-lead-desc>
                                <?php esc_html_e('Your score and machine recommendation are ready. Share your details to unlock them — an NTM specialist can also follow up with pricing, availability, and next steps.', 'standard'); ?>
                            </p>
                        </div>
                        <?php
                        echo \Standard\HubSpot\render_form([
                            'form_id'   => READINESS_QUIZ_FORM_ID,
                            'target_id' => 'readiness-quiz-form-' . $post_id,
                            'noscript_html' => '<p class="text-sm text-blue-600 m-0">'
                                . esc_html__('Enable JavaScript to load the form, or call NTM Sales at ', 'standard')
                                . '<a href="tel:+13032940538" class="font-mono text-blue-700 hover:text-blue-500">303.294.0538</a>.'
                                . '</p>',
                        ]);
                        ?>
                        <button type="button" class="quiz-restart" data-quiz-restart>
                            <?php esc_html_e('Retake the assessment', 'standard'); ?>
                        </button>
                    </div>
                </div>

            </div>
        </section>
    </main>

    <?php
endwhile;

get_footer();
