<?php
/**
 * Template Name: NTM — Finance Center
 *
 * The money page. A serious shopper has decided the machine pays for itself
 * and now needs to answer one question: how do I pay for it? This page is
 * the NTM Finance Center — it explains every path to financing a portable
 * rollforming machine (Corbel online application, Section 179 tax savings,
 * First National Bank as preferred lender, and the third-party lender
 * directory), and it routes the reader into the configurator's
 * build → quote → finance flow where all three happen in one place.
 *
 * Replaces the legacy page-form.php rendering of /machines/leasing-financing/,
 * which dumped the whole thing as a single Gutenberg prose column beside a
 * sticky HubSpot form. Here the self-serve paths lead; the specialist form
 * is the human catch-all at the end.
 *
 * Built from the same shared section grammar as Start Here and the machine
 * product pages (hairline ledger, mono-editorial labels, single red accent),
 * so it reads as the same company that built the machines it finances.
 * SEO/AEO weighted: one visible <h1>, FAQPage schema, curated internal links.
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
?>

<main id="primary" class="finance-center">

    <?php get_template_part('templates/pages/finance-center/hero'); ?>

    <?php get_template_part('templates/pages/finance-center/paths'); ?>

    <?php get_template_part('templates/pages/finance-center/configurator'); ?>

    <?php get_template_part('templates/pages/finance-center/corbel'); ?>

    <?php get_template_part('templates/pages/finance-center/section-179'); ?>

    <?php get_template_part('templates/pages/finance-center/lenders'); ?>

    <?php get_template_part('templates/pages/finance-center/faq'); ?>

    <?php get_template_part('templates/pages/finance-center/specialist-cta'); ?>

    <?php get_template_part('templates/pages/finance-center/disclaimer'); ?>

</main>

<?php
endwhile;

get_footer();
