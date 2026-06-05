<?php
/**
 * Finance Center — Disclaimer + further reading
 *
 * The honest footnote, kept quiet but present: NTM doesn't provide in-house
 * financing and isn't affiliated with any bank; agreements are between the
 * buyer and the lender. Followed by two learning-center deep-dives for the
 * reader who wants to go further before they apply.
 *
 * @package Standard
 *
 * @usage Finance Center (page-finance-center.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$reading = [
    [
        'label' => __('How to finance an NTM portable rollforming machine', 'standard'),
        'url'   => '/learning-center/how-to-finance-an-ntm-portable-rollforming-machine/',
    ],
    [
        'label' => __('Section 179: maximize savings on a portable rollformer', 'standard'),
        'url'   => '/learning-center/section-179-tax-deduction-maximize-savings-on-a-portable-rollformer/',
    ],
];
?>

<section class="section-compact bg-blue-50 border-t border-blue-200" aria-label="<?php esc_attr_e('Financing disclaimer and further reading', 'standard'); ?>">
    <div class="container">
        <div class="grid gap-10 lg:grid-cols-[minmax(0,1fr)_auto] lg:gap-16 lg:items-start">

            <div class="grid gap-3 max-w-3xl">
                <p class="font-mono text-xs uppercase tracking-mono-label text-blue-500">
                    <?php esc_html_e('Please note', 'standard'); ?>
                </p>
                <p class="text-sm text-blue-600 text-pretty m-0">
                    <?php esc_html_e('New Tech Machinery does not provide in-house financing and is not affiliated with any bank or financing company. Financing agreements are between the purchaser and the lender. Read the paperwork thoroughly and ask your loan agent about rates, terms, and conditions before you sign.', 'standard'); ?>
                </p>
            </div>

            <nav class="grid gap-3 content-start" aria-label="<?php esc_attr_e('Further reading on financing', 'standard'); ?>">
                <p class="font-mono text-xs uppercase tracking-mono-label text-blue-500">
                    <?php esc_html_e('Read more', 'standard'); ?>
                </p>
                <?php foreach ($reading as $item) : ?>
                    <a
                        href="<?php echo esc_url(\Standard\Url\internal($item['url'])); ?>"
                        class="inline-flex items-center gap-2 text-sm text-blue-700 hover:text-blue-500 transition-colors max-w-md"
                    >
                        <?php icon('arrow-right', ['class' => 'w-4 h-4 shrink-0 text-blue-400']); ?>
                        <span><?php echo esc_html($item['label']); ?></span>
                    </a>
                <?php endforeach; ?>
            </nav>

        </div>
    </div>
</section>
