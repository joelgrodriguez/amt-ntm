# 05 — Pages to Reuse (do NOT touch their URLs)

These are existing, published pages and posts that the new mega menu links to. **Their slugs and URLs are sacred.** Changing them breaks SEO and inbound traffic.

You can edit *content* on any of these (if asked), but you cannot:
- Change the slug
- Delete the page
- Move it under a different parent if that changes the URL
- Redirect it without explicit user approval

If a page below needs slight content tweaks to fit its new role in the IA (for example, adding a CTA at the bottom that points into one of the new action paths), that's fine. But default to **no content changes** unless explicitly asked.

## Anchor / hub pages

| URL | Title | Used by |
|---|---|---|
| `/machines/` | Portable Roof & Wall Panel Machines and Seamless Gutter Machines | Choose your machine — anchor of "See all machines" |
| `/roof-wall-panel-machines/` | NewTech Machinery Roof & Wall Panel Machines | Choose your machine — "See all machines" |
| `/seamless-gutter-machines/` | New Tech Machinery Seamless Gutter Machines | Choose your machine — "See all machines" |
| `/profiles/` | Profiles | Choose your machine — "See all machines" |
| `/upgrades/` | NTM Accessories | Choose your machine — "See all machines" |
| `/learning-center/` | Learning Center | Get started — "Explore" |
| `/service-hub/` | Service Hub | Get owner support — anchor of "Get support now" |
| `/owner-resources/` | Resources | Get owner support — secondary link |
| `/ntm-knowledge-base/` | NTM Knowledge Base | Get owner support — "Get support now" |
| `/manuals/` | NTM Machine Manuals | Get owner support — anchor of "Operate" |
| `/configurator/` | Configurator | How to buy — expert shortcut |
| `/contact/` | Contact New Tech Machinery | How to buy — anchor of "Talk or configure" + persistent header CTA |

## Quizzes (the guided lane)

| URL | Title | Used by |
|---|---|---|
| `/portable-rollforming-machine-readiness-assessment/` | Panel Machine Readiness Quiz | Get started — anchor of "See if it fits" |
| `/roof-panel-machine-assessment-quiz/` | Which roof panel machine is right for me? | Choose your machine — anchor of "Help me choose" |
| `/portable-gutter-machine-selection-guide/` | Portable Gutter Machine Selection Guide | Choose your machine — "Help me choose" |
| `/what-coil-width-should-you-use/` | What coil width should you use? | Choose your machine — "Help me choose" |

## Calculators & tools

| URL | Title | Used by |
|---|---|---|
| `/portable-rollforming-profit-calculator/` | Portable Rollforming Profit Calculator | Get started — "See if it fits" |
| `/portable-rollforming-calculator/` | Portable Rollforming Calculator | Resource (secondary) |
| `/ntm-coil-width-calculator/` | NTM Coil Width Calculator | Resource (secondary) |

## Explainer articles (Learning Center posts)

| URL | Title | Used by |
|---|---|---|
| `/portable-rollforming-machine-equipment-types-uses/` | What Is a Portable Rollforming Machine? | Get started — anchor of "Start here" |
| `/portable-rollforming-misconceptions/` | Top 5 misconceptions | Get started — "Start here" |
| `/portable-rollforming-vs-factory-panel-suppliers/` | Portable rollforming vs factory panel suppliers | Get started — "See if it fits" |

## Buying & pricing content

| URL | Title | Used by |
|---|---|---|
| `/portable-roof-panel-rollforming-machine-cost/` | Panel Machine Cost (2026) | How to buy — anchor of "Understand the deal" |
| `/gutter-machine-cost-what-to-look-for/` | Gutter Machine Cost (2026) | How to buy — "Understand the deal" |
| `/leasing-financing/` | Financing | How to buy — "Understand the deal" |
| `/how-to-build-and-finance-your-ntm-rollformer-all-on-one-site/` | Build & finance walkthrough | How to buy — "Understand the deal" |
| `/how-to-finance-an-ntm-portable-gutter-machine-a-short-guide/` | Finance a portable gutter machine | Secondary |
| `/getting-a-portable-rollforming-machine-quote/` | What to know before quoting | How to buy — "Get a quote" |
| `/how-to-get-a-quote-for-an-ntm-rollforming-machine/` | How to get a quote | How to buy — "Get a quote" |

## Comparison content

| URL | Title | Used by |
|---|---|---|
| `/portable-roof-panel-machines-ssq-ii-vs-ssr/` | SSQII vs SSR | Choose your machine — "Compare" |
| `/comparison-ntms-ssr-ssh-and-ssq-ii-portable-rollformers/` | SSR / SSH / SSQII | Choose your machine — "Compare" |
| `/ssq3-multi-pro/` | SSQ3 MultiPro | Choose your machine — "Compare" |

## Service & training content

| URL | Title | Used by |
|---|---|---|
| `/service-training/` | Sign up for a training session | Get owner support — "Operate" |
| `/what-to-expect-portable-rollforming-machine-training-session/` | What to expect in training | Get owner support — "Operate" |
| `/what-to-expect-when-an-ntm-service-tech-visits-your-jobsite/` | What to expect from a service visit | Secondary |
| `/warranty-registration/` | Warranty Registration | Get owner support — "Operate" |
| `/common-problems-with-ntm-portable-rollforming-machines-and-how-to-solve-them/` | Common problems & solutions | Get owner support — anchor of "Troubleshoot & buy again" |
| `/the-top-five-questions-the-ntm-service-department-receives/` | Top 5 service questions | Get owner support — "Troubleshoot & buy again" |
| `/ways-to-prevent-voiding-machine-warranty/` | Prevent voiding your warranty | Get owner support — "Troubleshoot & buy again" |

## Product / machine pages

The site has 76 products (WooCommerce) and configurator sub-pages for each major machine. The IA does **not** change any of these URLs.

- `/configurator/ssq3-multi-pro/` (and the page also lives at `/ssq3-multi-pro/`)
- `/configurator/ssh/`
- `/configurator/ssr/`
- `/configurator/ssqii/`
- `/configurator/wav/`
- `/configurator/machii/`
- `/configurator/5vc/`
- Plus the legacy product pages: `/machii/`, `/uniq-control-system/`, etc.

The full product list lives in `inventory/product.csv`. The full page list lives in `inventory/page.csv`.

## Custom post type archives

Don't touch the archive URLs for these CPTs:

- `/videos/` (or wherever the `video` archive lives)
- `/profiles/`
- `/manuals/`
- `/literature/`
- `/footprints/`

Confirm the archive permalinks against `app/inc/post-types.php` before changing any nav link that points at them.

---

## The full inventory

For comprehensive lookup, see:

- `inventory/page.csv` — 83 published pages
- `inventory/post.csv` — 251 published Learning Center posts
- `inventory/product.csv` — 76 published products
- `inventory/video.csv` — 222 published videos
- `inventory/profile.csv` — 35 profiles
- `inventory/manual.csv` — 28 manuals
- `inventory/literature.csv` — 26 brochures
- `inventory/resource.csv` — 16 calculators/tools
- `inventory/download.csv` — 15 PDFs
- `inventory/pricesheet.csv` — 8 pricesheets
- `inventory/footprint.csv` — 7 footprints
- `inventory/cutlist.csv` — 1 cutlist

Each CSV has `ID, post_title, post_name, post_date, post_parent`. The slug is `post_name`. Combine with the post type's archive base to get the full URL.
