#!/usr/bin/env bash
#
# Seed the editable Gutenberg content for the SSQ3 custom wrap campaign page.
# The page stays private until the marketing team approves the copy and adds
# final wrap photography.

set -euo pipefail

wp() {
  command wp --skip-themes --skip-plugins "$@"
}

page_slug='custom-wrap-option'
page_id="$(wp post list --post_type=page --name="${page_slug}" --post_status=publish,private,draft,pending,future --posts_per_page=1 --field=ID)"

if [[ -z "${page_id}" ]]; then
  echo "ERROR: page /${page_slug}/ was not found" >&2
  exit 1
fi

content_file="$(mktemp)"
trap 'rm -f "${content_file}"' EXIT

cat > "${content_file}" <<'BLOCKS'
<!-- wp:group {"metadata":{"name":"Campaign Hero"},"align":"full","className":"custom-wrap-section","backgroundColor":"blue-900","textColor":"white","style":{"elements":{"link":{"color":{"text":"var:preset|color|white"}}}},"layout":{"type":"default"}} -->
<div class="wp-block-group alignfull custom-wrap-section has-white-color has-blue-900-background-color has-text-color has-background has-link-color"><!-- wp:columns {"verticalAlignment":"center","className":"container mx-auto"} -->
<div class="wp-block-columns are-vertically-aligned-center container mx-auto"><!-- wp:column {"verticalAlignment":"center","width":"46%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:46%"><!-- wp:paragraph {"textColor":"blue-300","fontSize":"caption","fontFamily":"mono"} -->
<p class="has-blue-300-color has-text-color has-mono-font-family has-caption-font-size"><strong>NEW FOR THE SSQ3™ MULTIPRO</strong></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":1,"textColor":"white","fontSize":"display"} -->
<h1 class="wp-block-heading has-white-color has-text-color has-display-font-size">Make Your SSQ3 Unmistakably Yours</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"textColor":"blue-100","fontSize":"lg"} -->
<p class="has-blue-100-color has-text-color has-lg-font-size">Turn your new SSQ3 into a bold extension of your business with a custom wrap built around your brand.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"red","textColor":"white","style":{"border":{"radius":"0px"},"elements":{"link":{"color":{"text":"var:preset|color|white"}}}}} -->
<div class="wp-block-button"><a class="wp-block-button__link has-white-color has-red-background-color has-text-color has-background has-link-color wp-element-button" href="/configurator/ssq3-multi-pro/" style="border-radius:0px">Request a Custom-Wrap Quote</a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-outline","style":{"border":{"radius":"0px"}}} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="/machines/roof-wall-panel-machines/ssq3-multipro/" style="border-radius:0px">Explore the SSQ3</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->

<!-- wp:paragraph {"textColor":"blue-300","fontSize":"sm"} -->
<p class="has-blue-300-color has-text-color has-sm-font-size">Available on new SSQ3 orders for an additional price. Design approval and lead time apply.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","width":"54%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:54%"><!-- wp:image {"id":20979,"sizeSlug":"full","linkDestination":"none","style":{"border":{"color":"#26384b","width":"1px"}}} -->
<figure class="wp-block-image size-full has-custom-border"><img src="/wp-content/uploads/2026/06/ssq3-operator-at-controls.jpg" alt="Operator working at an SSQ3 MultiPro roof and wall panel machine" class="has-border-color wp-image-20979" style="border-color:#26384b;border-width:1px"/></figure>
<!-- /wp:image --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Brand Benefits"},"align":"full","className":"custom-wrap-section","backgroundColor":"blue-50","layout":{"type":"default"}} -->
<div class="wp-block-group alignfull custom-wrap-section has-blue-50-background-color has-background"><!-- wp:group {"className":"container mx-auto","layout":{"type":"default"}} -->
<div class="wp-block-group container mx-auto"><!-- wp:paragraph {"align":"center","textColor":"blue-500","fontSize":"caption","fontFamily":"mono"} -->
<p class="has-text-align-center has-blue-500-color has-text-color has-mono-font-family has-caption-font-size"><strong>MORE THAN A COLOR CHANGE</strong></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"textAlign":"center","fontSize":"heading-lg"} -->
<h2 class="wp-block-heading has-text-align-center has-heading-lg-font-size">Put Your Reputation on the Machine That Builds It</h2>
<!-- /wp:heading -->

<!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:html -->
<svg aria-hidden="true" viewBox="0 0 48 48" width="48" height="48" fill="none" stroke="#0070B8" stroke-width="2"><path d="M4 24s7-10 20-10 20 10 20 10-7 10-20 10S4 24 4 24Z"/><circle cx="24" cy="24" r="5"/></svg>
<!-- /wp:html -->

<!-- wp:heading {"level":3,"fontSize":"heading-sm"} -->
<h3 class="wp-block-heading has-heading-sm-font-size">Own the Jobsite</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Give crews, customers, and future customers a machine they can identify from across the site.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:html -->
<svg aria-hidden="true" viewBox="0 0 48 48" width="48" height="48" fill="none" stroke="#0070B8" stroke-width="2"><path d="M24 4 41 11v12c0 10-7 17-17 21C14 40 7 33 7 23V11l17-7Z"/><path d="m16 24 5 5 11-12"/></svg>
<!-- /wp:html -->

<!-- wp:heading {"level":3,"fontSize":"heading-sm"} -->
<h3 class="wp-block-heading has-heading-sm-font-size">Look Ready for Bigger Work</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Carry a consistent brand from your trucks and team to the equipment producing your panels.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:html -->
<svg aria-hidden="true" viewBox="0 0 48 48" width="48" height="48" fill="none" stroke="#0070B8" stroke-width="2"><path d="M8 35V13l24-7v28"/><path d="M8 30h29a5 5 0 0 1 0 10H13a5 5 0 0 1-5-5Z"/><path d="M32 14h8v20"/></svg>
<!-- /wp:html -->

<!-- wp:heading {"level":3,"fontSize":"heading-sm"} -->
<h3 class="wp-block-heading has-heading-sm-font-size">Create a Lasting Impression</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Make every delivery, setup, and production run another chance for people to remember your company.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Visual Showcase"},"align":"full","className":"custom-wrap-section","layout":{"type":"default"}} -->
<div class="wp-block-group alignfull custom-wrap-section"><!-- wp:columns {"verticalAlignment":"center","className":"container mx-auto"} -->
<div class="wp-block-columns are-vertically-aligned-center container mx-auto"><!-- wp:column {"verticalAlignment":"center","width":"58%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:58%"><!-- wp:image {"id":20977,"sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full"><img src="/wp-content/uploads/2026/06/ssq3-machine-side-loaded-coils.jpg" alt="Side view of an SSQ3 MultiPro with loaded coils" class="wp-image-20977"/></figure>
<!-- /wp:image --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","width":"42%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:42%"><!-- wp:paragraph {"textColor":"blue-500","fontSize":"caption","fontFamily":"mono"} -->
<p class="has-blue-500-color has-text-color has-mono-font-family has-caption-font-size"><strong>DESIGNED AROUND YOUR COMPANY</strong></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"fontSize":"heading"} -->
<h2 class="wp-block-heading has-heading-font-size">A Flagship Machine Should Carry a Flagship Look</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>The SSQ3 is built for contractors who want more capability from one portable platform. The custom-wrap option adds a visual finish shaped around your brand.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Share your brand direction with an NTM specialist. Our team will confirm available coverage, artwork needs, design approval steps, pricing, and timing before your order moves forward.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><a href="/machines/roof-wall-panel-machines/ssq3-multipro/"><strong>Explore the SSQ3 MultiPro →</strong></a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"How It Works"},"align":"full","className":"custom-wrap-section","backgroundColor":"blue-800","textColor":"white","style":{"elements":{"link":{"color":{"text":"var:preset|color|white"}}}},"layout":{"type":"default"}} -->
<div class="wp-block-group alignfull custom-wrap-section has-white-color has-blue-800-background-color has-text-color has-background has-link-color"><!-- wp:group {"className":"container mx-auto","layout":{"type":"default"}} -->
<div class="wp-block-group container mx-auto"><!-- wp:paragraph {"align":"center","textColor":"blue-200","fontSize":"caption","fontFamily":"mono"} -->
<p class="has-text-align-center has-blue-200-color has-text-color has-mono-font-family has-caption-font-size"><strong>FROM IDEA TO FINISHED MACHINE</strong></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"textAlign":"center","textColor":"white","fontSize":"heading-lg"} -->
<h2 class="wp-block-heading has-text-align-center has-white-color has-text-color has-heading-lg-font-size">Three Steps to Your Custom SSQ3</h2>
<!-- /wp:heading -->

<!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:paragraph {"textColor":"blue-300","fontSize":"caption","fontFamily":"mono"} -->
<p class="has-blue-300-color has-text-color has-mono-font-family has-caption-font-size"><strong>01 / START</strong></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3,"textColor":"white","fontSize":"heading-sm"} -->
<h3 class="wp-block-heading has-white-color has-text-color has-heading-sm-font-size">Configure Your SSQ3</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"textColor":"blue-100"} -->
<p class="has-blue-100-color has-text-color">Tell us about the profiles, options, and production needs behind your machine.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:paragraph {"textColor":"blue-300","fontSize":"caption","fontFamily":"mono"} -->
<p class="has-blue-300-color has-text-color has-mono-font-family has-caption-font-size"><strong>02 / CREATE</strong></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3,"textColor":"white","fontSize":"heading-sm"} -->
<h3 class="wp-block-heading has-white-color has-text-color has-heading-sm-font-size">Plan the Wrap</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"textColor":"blue-100"} -->
<p class="has-blue-100-color has-text-color">Provide your logo and brand direction. We will confirm requirements and the approval process.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:paragraph {"textColor":"blue-300","fontSize":"caption","fontFamily":"mono"} -->
<p class="has-blue-300-color has-text-color has-mono-font-family has-caption-font-size"><strong>03 / APPROVE</strong></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3,"textColor":"white","fontSize":"heading-sm"} -->
<h3 class="wp-block-heading has-white-color has-text-color has-heading-sm-font-size">Review the Details</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"textColor":"blue-100"} -->
<p class="has-blue-100-color has-text-color">Approve the design, added price, and schedule before production begins.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Frequently Asked Questions"},"align":"full","className":"custom-wrap-section","layout":{"type":"default"}} -->
<div class="wp-block-group alignfull custom-wrap-section"><!-- wp:group {"className":"container mx-auto","layout":{"type":"default"}} -->
<div class="wp-block-group container mx-auto"><!-- wp:heading {"textAlign":"center","fontSize":"heading-lg"} -->
<h2 class="wp-block-heading has-text-align-center has-heading-lg-font-size">Custom Wrap Questions</h2>
<!-- /wp:heading -->

<!-- wp:group {"className":"max-w-4xl mx-auto","layout":{"type":"default"}} -->
<div class="wp-block-group max-w-4xl mx-auto"><!-- wp:details {"className":"accordion"} -->
<details class="wp-block-details accordion"><summary><strong>Is the custom wrap included in the SSQ3 price?</strong><span class="accordion__icon" aria-hidden="true">⌄</span></summary><!-- wp:group {"className":"accordion__body","layout":{"type":"default"}} -->
<div class="wp-block-group accordion__body"><!-- wp:paragraph -->
<p>No. A custom wrap is an additional-cost option. Your specialist will provide pricing after the wrap scope and design needs are clear.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></details>
<!-- /wp:details -->

<!-- wp:details {"className":"accordion"} -->
<details class="wp-block-details accordion"><summary><strong>What artwork will I need to provide?</strong><span class="accordion__icon" aria-hidden="true">⌄</span></summary><!-- wp:group {"className":"accordion__body","layout":{"type":"default"}} -->
<div class="wp-block-group accordion__body"><!-- wp:paragraph -->
<p>Start with your logo, brand colors, and any examples that show the look you want. The team will confirm final file and artwork requirements.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></details>
<!-- /wp:details -->

<!-- wp:details {"className":"accordion"} -->
<details class="wp-block-details accordion"><summary><strong>Will a custom wrap affect delivery timing?</strong><span class="accordion__icon" aria-hidden="true">⌄</span></summary><!-- wp:group {"className":"accordion__body","layout":{"type":"default"}} -->
<div class="wp-block-group accordion__body"><!-- wp:paragraph -->
<p>It may. Timing depends on design approval, production scheduling, and your full machine configuration. Ask your specialist for the current schedule.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></details>
<!-- /wp:details -->

<!-- wp:details {"className":"accordion"} -->
<details class="wp-block-details accordion"><summary><strong>Can I add a wrap to an SSQ3 I already own?</strong><span class="accordion__icon" aria-hidden="true">⌄</span></summary><!-- wp:group {"className":"accordion__body","layout":{"type":"default"}} -->
<div class="wp-block-group accordion__body"><!-- wp:paragraph -->
<p>This page describes the custom-wrap option for new SSQ3 orders. Contact NTM to discuss your existing machine and available options.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></details>
<!-- /wp:details --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Final Call to Action"},"align":"full","className":"custom-wrap-section","backgroundColor":"blue-50","layout":{"type":"constrained","contentSize":"820px"}} -->
<div class="wp-block-group alignfull custom-wrap-section has-blue-50-background-color has-background"><!-- wp:heading {"textAlign":"center","fontSize":"heading-lg"} -->
<h2 class="wp-block-heading has-text-align-center has-heading-lg-font-size">Put Your Brand to Work on Every Jobsite</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","fontSize":"lg"} -->
<p class="has-text-align-center has-lg-font-size">Configure your machine and tell an NTM specialist you are interested in the custom-wrap option.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"red","textColor":"white","style":{"border":{"radius":"0px"},"elements":{"link":{"color":{"text":"var:preset|color|white"}}}}} -->
<div class="wp-block-button"><a class="wp-block-button__link has-white-color has-red-background-color has-text-color has-background has-link-color wp-element-button" href="/configurator/ssq3-multi-pro/" style="border-radius:0px">Request a Custom-Wrap Quote</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->

<!-- wp:paragraph {"align":"center","textColor":"blue-600","fontSize":"sm"} -->
<p class="has-text-align-center has-blue-600-color has-text-color has-sm-font-size">No design is final until you approve it. Additional pricing and lead time apply.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->
BLOCKS

wp post update "${page_id}" \
  --post_content="$(cat "${content_file}")" \
  --post_excerpt='Turn your SSQ3 MultiPro into a moving statement for your business with a custom wrap built around your brand.' >/dev/null

wp post meta update "${page_id}" _thumbnail_id 20979 >/dev/null
wp post meta update "${page_id}" _wp_page_template templates/template-full-width.php >/dev/null

echo "Updated page ${page_id} (/${page_slug}/) with editable Gutenberg content."
echo "Template is $(wp post meta get "${page_id}" _wp_page_template)."
echo "Status remains $(wp post get "${page_id}" --field=post_status)."
