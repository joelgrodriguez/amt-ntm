# Behavior spec: woo

<!-- shogun:auto — appended on land, newest first. Read top-down for current behavior. -->

## Fix product-category archives rendering unstyled WooCommerce defaults — #69
*Landed 2026-07-06 · type: bugfix*

- 301-redirect /product-category/roof-wall-panel-machines/ and /product-category/gutter-machines/ to the designed landing pages (page-roof-wall-panel-machines.php, page-seamless-gutter-machines.php) via a git-owned template_redirect hook in app/inc so it survives fresh prod DB pulls
- handle every other public product_cat archive generically (redirect to /machines/ or an equivalent designed destination) so no product-category URL falls through to raw Woo markup (theme disables all WC styles in app/inc/woo/setup.php:35 and has no taxonomy-product_cat.php)
- update footer links in app/footer.php to point at the landing pages directly
- preserve existing entries in db/redirects.json
- npm run build passes

## Fix configurator build/quote URL: SSR (and others) emit full product slug instead of short configurator slug; SSQ3 works — #67
*Landed 2026-07-02 · type: bugfix*

- Fix configurator build/quote URL: SSR (and others) emit full product slug instead of short configurator slug; SSQ3 works
