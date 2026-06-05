<?php
/**
 * Finance Center — Lender directory
 *
 * One even directory instead of a red-flagged FNB hero over a separate list.
 * Every lender is a large-logo row; FNB simply leads, marked "NTM’s pick"
 * with a small blue check. NTM doesn't finance in-house and isn't affiliated
 * with any bank, so the lenders read as peers the buyer compares — not as a
 * single push.
 *
 * Logos render through responsive_image() (URLs are upload-relative, resolved
 * to attachments for srcset). Each row leads with the lender NAME so a failed
 * logo never blanks a row; the white logo tile keeps marks legible on the
 * tinted section.
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

// Listed alphabetically — these are peers the buyer compares, not a ranked
// push. NTM doesn't finance in-house and isn't affiliated with any of them.
$lenders = [
    [
        'name' => __('ACG Equipment Finance', 'standard'),
        'note' => __('Section 179 Elite financing programs', 'standard'),
        'logo' => $uploads . '2021/06/NTM_ACGFinanceFlyerGraphic.jpeg',
        'url'  => 'https://acgequipmentfinance.com/',
    ],
    [
        'name' => __('American Bank', 'standard'),
        'note' => __('Equipment finance for small to mid-size business', 'standard'),
        'logo' => $uploads . '2024/02/AB_Equipment_Finance_horizontal.jpg',
        'url'  => 'https://www.americanbank.com/business/loans/equipment-finance',
    ],
    [
        'name' => __('Apex Capital', 'standard'),
        'note' => __('Equipment finance + Section 179 calculator', 'standard'),
        'logo' => $uploads . '2021/06/NTM_Financing_ApexCapital.jpeg',
        'url'  => 'https://financewithapex.com/michelle/',
    ],
    [
        'name' => __('Crest Capital', 'standard'),
        'note' => __('Equipment leasing and financing', 'standard'),
        'logo' => $uploads . '2021/06/NTM_Financing_CrestCapital.jpeg',
        'url'  => 'https://www.crestcapital.com/equipment_leasing',
    ],
    [
        'name' => __('First National Bank', 'standard'),
        'note' => __('Loans, leasing & equipment finance', 'standard'),
        'logo' => $uploads . '2024/11/fnb.jpg',
        'url'  => 'https://www.elbtools.com/secure/apply.php?elbt=110356250762',
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
            <p class="section-subtitle text-pretty">
                <?php esc_html_e('Already have a lender? You can finance through any bank or credit union you choose — we’ll provide the quote, invoice, and machine specs they need.', 'standard'); ?>
            </p>
        </div>

        <ul class="lender-list" role="list">
            <?php foreach ($lenders as $lender) : ?>
                <li class="lender-list__item">
                    <a
                        href="<?php echo esc_url($lender['url']); ?>"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="lender-list__link"
                        aria-label="<?php echo esc_attr(sprintf(
                            /* translators: %s: lender name, e.g. "Apex Capital" */
                            __('Visit %s (opens in a new tab)', 'standard'),
                            $lender['name']
                        )); ?>"
                    >
                        <span class="lender-list__logo-wrap">
                            <?php
                            // alt="" — decorative: the visible lender name beside it
                            // already names the link, so an alt would double-announce.
                            responsive_image($lender['logo'], '', 'medium', [
                                'class' => 'lender-list__logo',
                            ]);
                            ?>
                        </span>
                        <span class="lender-list__text">
                            <span class="lender-list__name"><?php echo esc_html($lender['name']); ?></span>
                            <span class="lender-list__note"><?php echo esc_html($lender['note']); ?></span>
                        </span>
                        <span class="lender-list__cta" aria-hidden="true">
                            <span class="lender-list__cta-label"><?php esc_html_e('Visit', 'standard'); ?></span>
                            <span class="lender-list__arrow">
                                <?php icon('arrow-right', ['class' => 'w-4 h-4']); ?>
                            </span>
                        </span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>

    </div>
</section>
