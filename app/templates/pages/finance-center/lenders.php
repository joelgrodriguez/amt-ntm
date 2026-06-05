<?php
/**
 * Finance Center — Lender directory
 *
 * Replaces the legacy three-logo column blocks with one clean directory.
 * First National Bank leads as NTM's preferred lender in a wider feature
 * row; the third-party lenders (Apex, American Bank, Crest, ACG) follow as
 * a hairline-ruled list. Every row leads with the lender NAME so it stays
 * legible even if a logo fails — the logo is enhancement, not the row.
 *
 * Logos render through responsive_image(): URLs are upload-relative
 * (home_url-based) so they survive the local↔prod domain swap, and the
 * helper resolves each to its WP attachment for proper srcset/sizes.
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

$uploads = trailingslashit(home_url('/wp-content/uploads'));

$preferred = [
    'name'  => __('First National Bank', 'standard'),
    'tag'   => __('Preferred lender', 'standard'),
    'copy'  => __('NTM’s recommended financing partner. Loans, leasing, and equipment finance programs built for capital purchases like yours.', 'standard'),
    'logo'  => $uploads . '2024/11/fnb.jpg',
    'apply' => 'https://www.elbtools.com/secure/apply.php?elbt=110356250762',
    'learn' => 'https://www.fnb-online.com/business/loans-leasing/equipment-financing',
];

$lenders = [
    [
        'name' => __('Apex Capital', 'standard'),
        'note' => __('Equipment finance + Section 179 calculator', 'standard'),
        'logo' => $uploads . '2021/06/NTM_Financing_ApexCapital.jpeg',
        'url'  => 'https://financewithapex.com/michelle/',
    ],
    [
        'name' => __('American Bank', 'standard'),
        'note' => __('Equipment finance for small to mid-size businesses', 'standard'),
        'logo' => $uploads . '2024/02/AB_Equipment_Finance_horizontal.jpg',
        'url'  => 'https://www.americanbank.com/business/loans/equipment-finance',
    ],
    [
        'name' => __('Crest Capital', 'standard'),
        'note' => __('Equipment leasing and financing', 'standard'),
        'logo' => $uploads . '2021/06/NTM_Financing_CrestCapital.jpeg',
        'url'  => 'https://www.crestcapital.com/equipment_leasing',
    ],
    [
        'name' => __('ACG Equipment Finance', 'standard'),
        'note' => __('Section 179 Elite financing programs', 'standard'),
        'logo' => $uploads . '2021/06/NTM_ACGFinanceFlyerGraphic.jpeg',
        'url'  => 'https://acgequipmentfinance.com/',
    ],
];
?>

<section id="lenders" class="section bg-blue-50 scroll-mt-24" aria-labelledby="lenders-title">
    <div class="container section-content">

        <div class="section-header-left max-w-2xl">
            <p class="section-eyebrow"><?php esc_html_e('Work with a lender', 'standard'); ?></p>
            <div class="section-divider"></div>
            <h2 id="lenders-title" class="section-title">
                <?php esc_html_e('The lenders we work with.', 'standard'); ?>
            </h2>
            <p class="section-subtitle text-pretty">
                <?php esc_html_e('NTM doesn’t finance machines in-house, and we’re not affiliated with any bank. These are the partners contractors have used to fund their machines. Compare terms and talk to whichever fits your situation.', 'standard'); ?>
            </p>
        </div>

        <div class="grid gap-8">

            <article class="lender-feature">
                <div class="lender-feature__brand">
                    <p class="lender-feature__tag">
                        <span class="lender-feature__tag-dot" aria-hidden="true"></span>
                        <?php echo esc_html($preferred['tag']); ?>
                    </p>
                    <span class="lender-feature__logo-frame">
                        <?php
                        responsive_image($preferred['logo'], $preferred['name'], 'medium', [
                            'class'  => 'lender-feature__logo',
                            'width'  => '248',
                            'height' => '79',
                        ]);
                        ?>
                    </span>
                </div>
                <div class="lender-feature__body">
                    <h3 class="lender-feature__name"><?php echo esc_html($preferred['name']); ?></h3>
                    <p class="lender-feature__copy text-pretty"><?php echo esc_html($preferred['copy']); ?></p>
                    <div class="lender-feature__actions">
                        <a
                            href="<?php echo esc_url($preferred['apply']); ?>"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="btn btn-primary"
                        >
                            <?php esc_html_e('Apply with FNB', 'standard'); ?>
                            <?php icon('arrow-right', ['class' => 'w-5 h-5']); ?>
                        </a>
                        <a
                            href="<?php echo esc_url($preferred['learn']); ?>"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex min-h-11 items-center gap-1.5 font-mono text-xs uppercase tracking-mono-label text-blue-500 transition-colors hover:text-blue-700"
                        >
                            <?php esc_html_e('FNB equipment financing', 'standard'); ?>
                            <?php icon('arrow-right', ['class' => 'w-3.5 h-3.5']); ?>
                        </a>
                    </div>
                </div>
            </article>

            <div>
                <p class="font-mono text-xs uppercase tracking-mono-label text-blue-500 mb-4">
                    <?php esc_html_e('Third-party options', 'standard'); ?>
                </p>
                <ul class="lender-list" role="list">
                    <?php foreach ($lenders as $lender) : ?>
                        <li class="lender-list__item">
                            <a
                                href="<?php echo esc_url($lender['url']); ?>"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="lender-list__link"
                            >
                                <span class="lender-list__logo-wrap">
                                    <?php
                                    responsive_image($lender['logo'], $lender['name'] . ' logo', 'medium', [
                                        'class' => 'lender-list__logo',
                                    ]);
                                    ?>
                                </span>
                                <span class="lender-list__text">
                                    <span class="lender-list__name"><?php echo esc_html($lender['name']); ?></span>
                                    <span class="lender-list__note"><?php echo esc_html($lender['note']); ?></span>
                                </span>
                                <span class="lender-list__arrow" aria-hidden="true">
                                    <?php icon('arrow-right', ['class' => 'w-4 h-4']); ?>
                                </span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

        </div>

    </div>
</section>
