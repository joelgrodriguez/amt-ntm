# Behavior spec: seo

<!-- admiral:auto — appended on land, newest first. Read top-down for current behavior. -->

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
