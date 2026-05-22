<?php
/**
 * Template Name: Lead Form Landing
 *
 * Page template for focused marketing pages with a configurable HubSpot form.
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

    $post_id = get_the_ID();
    $hero = \Standard\PageTemplates\get_hero_data($post_id);
    $has_hero = $hero['has_content'] || $hero['has_video'];
    $legacy_slug = get_page_template_slug($post_id);
    $is_meta_form = $legacy_slug === 'page-form-meta.php';
    $is_contact = is_page('contact');
    $form_id = \Standard\PageTemplates\get_page_form_id($post_id);
    $form_eyebrow = \Standard\PageTemplates\get_label($post_id, ['form_eyebrow'], __('Next Step', 'standard'));
    $form_title = \Standard\PageTemplates\get_label(
        $post_id,
        ['form_title', 'lead_form_title'],
        $is_meta_form ? __('Connect with NTM', 'standard') : __('Contact New Tech Machinery', 'standard')
    );
    $form_description = \Standard\PageTemplates\get_label(
        $post_id,
        ['form_description', 'lead_form_description'],
        __('Send the details and a rollforming specialist will follow up.', 'standard')
    );
?>

<main id="primary" class="bg-white">
    <?php
    if ($has_hero) {
        get_template_part('templates/parts/page-video-hero', null, [
            'hero' => $hero,
            'section_id' => 'lead-form-hero',
        ]);
    }
    ?>

    <section class="section" aria-labelledby="lead-form-content-title">
        <div class="container">
            <div class="grid gap-12 lg:grid-cols-[minmax(0,1fr)_minmax(320px,420px)] lg:gap-16 lg:items-start">
                <article id="post-<?php the_ID(); ?>" <?php post_class('grid gap-8 min-w-0 order-2 lg:order-none lg:col-start-1 lg:row-start-1'); ?>>
                    <?php if (!$has_hero) : ?>
                        <header class="section-header-left max-w-3xl">
                            <p class="section-eyebrow"><?php esc_html_e('New Tech Machinery', 'standard'); ?></p>
                            <h1 id="lead-form-content-title" class="font-sans text-4xl md:text-5xl lg:text-6xl font-medium tracking-tight text-blue-900 leading-none">
                                <?php
                                if ($is_contact) {
                                    esc_html_e('Where NTM lives.', 'standard');
                                } else {
                                    the_title();
                                }
                                ?>
                            </h1>
                        </header>
                    <?php else : ?>
                        <h2 id="lead-form-content-title" class="sr-only"><?php the_title(); ?></h2>
                    <?php endif; ?>

                    <?php if ($is_contact) : ?>
                        <p class="text-lg text-blue-600 leading-relaxed max-w-2xl m-0">
                            <?php esc_html_e('Talk to the people who invented portable rollforming. Find us by phone, by form, or in person.', 'standard'); ?>
                        </p>

                        <?php get_template_part('templates/parts/contact-locations'); ?>

                        <div class="grid gap-4 mt-4">
                            <p class="section-eyebrow"><?php esc_html_e('Common questions', 'standard'); ?></p>
                            <?php
                            get_template_part('templates/parts/contact-faq-list', null, [
                                'faqs' => \Standard\ContactData\get_faq_items(),
                            ]);
                            ?>
                            <p class="text-blue-600 m-0 pt-2">
                                <?php
                                printf(
                                    /* translators: 1: full FAQ page link, 2: support center link. */
                                    esc_html__('More questions? %1$s. Already own a machine? %2$s.', 'standard'),
                                    '<a href="' . esc_url(home_url('/faq/')) . '" class="text-blue-500 font-medium hover:text-blue-700">'
                                        . esc_html__('Visit the full FAQ', 'standard')
                                        . '</a>',
                                    '<a href="https://support.newtechmachinery.com/" target="_blank" rel="noreferrer noopener" class="text-blue-500 font-medium hover:text-blue-700">'
                                        . esc_html__('Visit the Support Center', 'standard')
                                        . '</a>'
                                );
                                ?>
                            </p>
                        </div>
                    <?php else : ?>
                        <div class="prose prose-lg max-w-none prose-headings:font-medium prose-headings:tracking-tight prose-headings:text-blue-900 prose-p:text-blue-600 prose-li:text-blue-600 prose-strong:text-blue-900 prose-a:text-blue-500">
                            <?php the_content(); ?>
                        </div>
                    <?php endif; ?>
                </article>

                <aside class="border-t border-blue-500 border-x-0 border-b-0 bg-blue-50 p-6 md:p-8 lg:sticky lg:top-24 order-1 lg:order-none lg:col-start-2 lg:row-start-1" aria-label="<?php echo $is_contact ? esc_attr__('Call or message NTM', 'standard') : esc_attr($form_title); ?>">
                    <div class="grid gap-6">
                        <?php if ($is_contact) : ?>
                            <header class="grid gap-3">
                                <p class="section-eyebrow"><?php esc_html_e('Call NTM', 'standard'); ?></p>
                                <a
                                    href="tel:+13032940538"
                                    class="font-mono font-medium text-2xl md:text-3xl text-blue-900 hover:text-blue-500 no-underline inline-flex items-center gap-3 m-0 transition-colors duration-200"
                                >
                                    <?php icon('phone', ['class' => 'w-5 h-5 md:w-6 md:h-6 text-blue-500 shrink-0']); ?>
                                    <span>303.294.0538</span>
                                </a>
                                <p class="font-mono text-xs uppercase tracking-widest text-blue-700 inline-flex items-center gap-2 m-0">
                                    <span class="inline-block w-1.5 h-1.5 bg-red shrink-0" aria-hidden="true"></span>
                                    <?php esc_html_e('Or send the details below. Response within 1 business day.', 'standard'); ?>
                                </p>
                            </header>
                        <?php else : ?>
                            <header class="grid gap-3">
                                <p class="section-eyebrow"><?php echo esc_html($form_eyebrow); ?></p>
                                <h2 id="lead-form-title" class="font-sans text-2xl md:text-3xl font-medium tracking-tight text-blue-900">
                                    <?php echo esc_html($form_title); ?>
                                </h2>
                                <p class="text-blue-600">
                                    <?php echo esc_html($form_description); ?>
                                </p>
                            </header>
                        <?php endif; ?>

                        <?php
                        $form_args = [
                            'form_id' => $form_id,
                            'target_id' => 'lead-form-' . $post_id,
                        ];

                        if ($is_contact) {
                            $form_args['noscript_html'] = '<p class="text-sm text-blue-600 m-0">'
                                . esc_html__('Enable JavaScript to load the form, or call NTM Sales at ', 'standard')
                                . '<a href="tel:+13032940538" class="font-mono text-blue-700 hover:text-blue-500">303.294.0538</a>.'
                                . '</p>';
                        }

                        echo \Standard\HubSpot\render_form($form_args);
                        ?>
                    </div>
                </aside>
            </div>
        </div>
    </section>
</main>

<?php
endwhile;

get_footer();
