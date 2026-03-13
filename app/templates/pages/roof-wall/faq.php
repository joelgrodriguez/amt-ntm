<?php
/**
 * Roof & Wall Panel Machines — FAQ Accordion
 *
 * Category-specific FAQ with accordion. Same markup as machines/faq-accordion.php
 * so Accordion.js auto-initializes via querySelectorAll.
 *
 * @package Standard
 *
 * @usage Roof & Wall Panel Machines (page-roof-wall-panel-machines.php)
 * @see js/modules/Accordion.js
 */

declare(strict_types=1);

use function Standard\MachinesData\get_roof_wall_faq_items;

$content = [
    'eyebrow' => __('FAQ', 'standard'),
    'title'   => __('Roof & Wall Panel Machine Questions', 'standard'),
    'image'   => content_url('/uploads/2023/05/Machine-lifted-onto-rooftop-2048x1536.jpg'),
];

$faqs = get_roof_wall_faq_items();
?>

<section class="section bg-light" aria-labelledby="roof-wall-faq-title">
    <div class="container">
        <div class="grid gap-12 md:grid-cols-2 md:gap-12 lg:gap-16 md:items-start">

            <div class="grid gap-8 content-start">
                <div class="section-header-left">
                    <p class="section-eyebrow">
                        <?php echo esc_html($content['eyebrow']); ?>
                    </p>
                    <div class="section-divider"></div>
                    <h2 id="roof-wall-faq-title" class="section-title">
                        <?php echo esc_html($content['title']); ?>
                    </h2>
                </div>

                <div data-accordion>
                    <?php foreach ($faqs as $i => $faq) : ?>
                        <div
                            class="border-t border-slate-200 last:border-b"
                            data-accordion-item
                        >
                            <button
                                type="button"
                                class="cds-accordion-trigger flex items-center justify-between gap-4 w-full py-4 text-left text-sm font-semibold text-slate-900 hover:bg-slate-100 transition-colors duration-150 cursor-pointer"
                                data-accordion-trigger
                                aria-expanded="<?php echo $i === 0 ? 'true' : 'false'; ?>"
                            >
                                <span class="leading-snug">
                                    <?php echo esc_html($faq['question']); ?>
                                </span>
                                <span class="cds-accordion-icon shrink-0 text-slate-500 transition-transform duration-200 ease-out">
                                    <?php icon('chevron-down', ['class' => 'w-5 h-5']); ?>
                                </span>
                            </button>
                            <div
                                class="max-h-0 overflow-hidden transition-all duration-300 ease-in-out"
                                data-accordion-content
                            >
                                <p class="pb-6 pr-8 text-sm text-slate-600 leading-relaxed border-l-2 border-primary pl-4">
                                    <?php echo esc_html($faq['answer']); ?>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="hidden md:block lg:sticky lg:top-24">
                <img
                    src="<?php echo esc_url($content['image']); ?>"
                    alt="<?php echo esc_attr__('NTM machine being lifted onto a rooftop', 'standard'); ?>"
                    class="w-full h-[300px] lg:h-[600px] xl:h-[700px] object-cover"
                    loading="lazy"
                >
            </div>

        </div>
    </div>
</section>
