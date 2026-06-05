<?php
/**
 * Finance Center — Talk to a specialist
 *
 * The human path, moved out of the old sticky right rail and given its own
 * closing section after every self-serve option. Left: copy, a short "what
 * to expect" list, and the phone number. Right: the HubSpot lead form in a
 * white panel on the dark surface. Self-serve leads; this is the catch-all.
 *
 * @package Standard
 *
 * @usage Finance Center (page-finance-center.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$post_id = get_the_ID();
$form_id = \Standard\PageTemplates\get_page_form_id($post_id);

$expect = [
    __('A straight answer on which financing path fits your deal', 'standard'),
    __('Help mapping Section 179 to your purchase timing', 'standard'),
    __('A warm handoff to the right lender, not a sales pitch', 'standard'),
];

$noscript_html = '<p class="text-sm text-blue-200 m-0">'
    . esc_html__('Enable JavaScript to load the form, or call NTM Sales at ', 'standard')
    . '<a href="tel:+13032940538" class="font-mono text-white hover:text-blue-200">303.294.0538</a>.'
    . '</p>';
?>

<section class="section bg-blue-900 text-white" aria-labelledby="finance-specialist-title">
    <div class="container">
        <div class="grid gap-12 lg:grid-cols-[minmax(0,1fr)_minmax(380px,520px)] lg:gap-16 lg:items-start">

            <div class="grid gap-8 content-start">
                <div class="section-header-left">
                    <p class="section-eyebrow text-blue-300"><?php esc_html_e('Talk it through', 'standard'); ?></p>
                    <div class="section-divider"></div>
                    <h2 id="finance-specialist-title" class="section-title text-white">
                        <?php esc_html_e('Not sure which path is yours?', 'standard'); ?>
                    </h2>
                    <p class="section-subtitle text-blue-200 max-w-xl text-pretty">
                        <?php esc_html_e('Send the details and a rollforming specialist will follow up with the financing route that fits your numbers, your timing, and the machine you’re after.', 'standard'); ?>
                    </p>
                </div>

                <div class="grid gap-4">
                    <p class="font-mono text-xs uppercase tracking-mono-label text-blue-400">
                        <?php esc_html_e('What to expect', 'standard'); ?>
                    </p>
                    <ul class="grid gap-3" role="list">
                        <?php foreach ($expect as $item) : ?>
                            <li class="flex items-start gap-3 text-blue-200">
                                <?php icon('check', ['class' => 'w-5 h-5 text-blue-400 shrink-0 mt-0.5']); ?>
                                <span><?php echo esc_html($item); ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="grid gap-2 border-t border-blue-800 pt-6">
                    <p class="font-mono text-xs uppercase tracking-mono-label text-blue-400 m-0">
                        <?php esc_html_e('Or call NTM Sales', 'standard'); ?>
                    </p>
                    <a
                        href="tel:+13032940538"
                        class="font-mono font-medium text-2xl md:text-3xl text-white hover:text-blue-200 no-underline inline-flex items-center gap-3 m-0 transition-colors duration-200"
                    >
                        <?php icon('phone', ['class' => 'w-5 h-5 md:w-6 md:h-6 text-blue-400 shrink-0']); ?>
                        <span>303.294.0538</span>
                    </a>
                </div>
            </div>

            <aside class="bg-white p-6 md:p-8" aria-label="<?php esc_attr_e('Contact a rollforming specialist', 'standard'); ?>">
                <div class="grid gap-6">
                    <header class="grid gap-2">
                        <p class="section-eyebrow"><?php esc_html_e('Next step', 'standard'); ?></p>
                        <h3 class="font-sans text-xl md:text-2xl font-medium tracking-tight text-blue-900">
                            <?php esc_html_e('Send your details', 'standard'); ?>
                        </h3>
                    </header>
                    <?php
                    echo \Standard\HubSpot\render_form([
                        'form_id'       => $form_id,
                        'target_id'     => 'finance-lead-form-' . $post_id,
                        'noscript_html' => $noscript_html,
                    ]);
                    ?>
                </div>
            </aside>

        </div>
    </div>
</section>
