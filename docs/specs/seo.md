# Behavior spec: seo

<!-- admiral:auto — appended on land, newest first. Read top-down for current behavior. -->

## Remove retired product categories from Yoast XML sitemap — #114
*Landed 2026-07-18 · type: bugfix*

- Exclude only /product-category/roof-wall-panel-machines/, /product-category/gutter-machines/, and /product-category/accessories-add-on-equipment/ from product_cat-sitemap.xml
- Keep the product_cat taxonomy sitemap and all active product categories intact
- Preserve the existing 301 redirects to /roof-wall-panel-machines/, /seamless-gutter-machines/, and /machines/
- Resolve term IDs safely from stable slugs rather than hardcoding environment-specific IDs
- Add focused regression coverage for the exclusion behavior
- Verify rendered local sitemap omits all three retired URLs
- npm run build and PHP lint pass

## Make WooCommerce/theme code authoritative for Product schema while keeping Schema Pro active for FAQ blocks and useful VideoObject output.

After issue 107 lands, add scripts/db/051 to draft only the generic published Schema Pro Product rule. Resolve it by stable title, aiosrs-schema post type, product schema type, and product|all targeting. Purge only Schema Pro optimized cache rows containing that mapped Product output or empty Product-rule arrays; do not remove FAQPage or VideoObject data. Update app/inc/machine-schema.php so manufacturer references the canonical home #organization identity instead of creating an unlinked differently named Organization. Verify the theme fallback defines the same ID. — #108
*Landed 2026-07-18 · type: bugfix*

- Schema Pro stays active
- Generic Schema Pro Product rule is disabled with a guarded replayable DB migration
- Product cache rows are purged without touching FAQ or Video schema
- Theme machine manufacturer references the canonical organization ID
- WooCommerce and theme Product output remain authoritative
- npm run build passes
- Migration dry-run proves scope and idempotency

## Retire only the invalid homepage Schema Pro custom graph while keeping Schema Pro and all FAQ blocks/rules active.

Implement a replayable scripts/db/050 migration using the safe patterns in scripts 041 and 042: resolve Home Page Custom Schema by title and aiosrs-schema type, verify special-front targeting plus unique graph signatures (#home-featured-machines, #customer-reviews, #brand-video), draft it, then purge only the front-page wp_schema_pro_optimized_structured_data row when those signatures match. DRY_RUN must default on, re-runs must no-op, and the direct docker exec/wp eval-file runner workaround must be preserved.

Include the existing investigation report from /Users/jrodriguez/Development/kinsta/public/newtech/wp-content/themes/amt-ntm/docs/audits/schema-pro-conflict-investigation.md in this task branch. Do not modify plugin files, FAQ rules, FAQ blocks, Article rules, Video rules, Product rules, or WordPress content. — #107
*Landed 2026-07-18 · type: bugfix*

- Schema Pro stays active
- Home Page Custom Schema is drafted only when guarded signatures and target location match
- Homepage cache is purged only when it contains the targeted graph
- Migration is idempotent and dry-run safe
- Existing audit report is included
- npm run build passes
- Migration dry-run proves the intended record and cache scope

# Behavior spec: seo

<!-- shogun:auto — appended on land, newest first. Read top-down for current behavior. -->

## SEO: breadcrumbs + BreadcrumbList schema on WooCommerce products and pillar pages — #51
*Landed 2026-07-02 · type: feature*

- SEO: breadcrumbs + BreadcrumbList schema on WooCommerce products and pillar pages

## SEO: theme fallback for meta description/OG/canonical + Organization/LocalBusiness/WebSite schema + FAQ schema tag-strip fix — #50
*Landed 2026-07-02 · type: feature*

- SEO: theme fallback for meta description/OG/canonical + Organization/LocalBusiness/WebSite schema + FAQ schema tag-strip fix
