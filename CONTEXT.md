# Context

Shared language and system boundaries for the `amt-ntm` theme. Read this before
your first change; `PRODUCT.md` covers intent, this covers the machinery.

Claims are traceable to code. Unsettled items are marked **Unknown**.

## Actors

| Actor | Wants | Enters at |
|---|---|---|
| **Buyer** — contractor/crew owner, five-to-six-figure decision | Specs, price, proof, then a quote | Home, machine pages, Learning Center |
| **Owner** — already runs an NTM machine | Manuals, profiles, troubleshooting, service request | `/service-hub/` |
| **Content team** | Edit page copy without touching PHP | XLSX/CSV round-trip (`npm run content:export`) |
| **Agent** — you | Land one scoped task without breaking the others | `admiral task ready` |

Distributors and dealers are a real audience (`PRODUCT.md`) but share the same
technical surfaces.

## Vocabulary

| Term | In this codebase |
|---|---|
| **Machine** | A rollforming machine. Modelled twice: WooCommerce `product` (permalink, image, category, tags) *and* PHP data in `app/data/machines/` (narrative, specs, **price**). Always both. |
| **Machine key** | Filename stem under `app/data/machines/` (e.g. `ssq3-multipro`). Canonical internal ID. |
| **Profile** | ⚠️ **Three meanings, none a person.** (1) `profile` CPT — panel profile spec sheet (PDF + machines); (2) `profiles` product category — roller tooling sold as products; (3) `profiles` in machine data — pointer or count, not content. CPT UI copy ("Read this profile", user icon) is misleading. |
| **Dormant** | Pulled from the lineup, still supported (`'dormant' => true`). Sales hide it; Service Hub includes it. |
| **Flagship** | Rich product template only: `ssq3-multipro`, `mach-ii-combo-gutter`. Everything else uses `single-machine-default.php`. |
| **Panel** | Formed metal output. Roof/wall vs seamless gutter is the top category split. |
| **Coil / gauge / shear** | Core spec nouns. Gauge always pairs with a material; shear is the cutoff (hydraulic or manual). |
| **Footprint** | Scaled dimensional floorplan (CPT + ACF on product). Archive 301s to `/machines/footprints/`. |
| **Literature** | Sales PDFs; machine data links as `resources.brochure`. |
| **Pricesheet** | Dealer/list pricing PDFs. **Hidden from search** — fails closed in `app/inc/search.php`. |
| **Cutlist** | Cut-length reference; excluded from search; Service Hub only. |
| **Pillar / roll-up page** | Designed landing above the catalog. Product-category *archives* are retired and 301 away. |
| **Configurator** | External Corbel build-and-quote. Reached by short configurator slug ≠ product slug. |
| **Build & Quote** | Configurator CTA label. |
| **Ironclad Support / Portability DNA** | Reusable proof strips (`get_ironclad_pillars()`, `get_portability_pillars()`). |
| **Service Hub** | `/service-hub/` — owner support, scoped search, machine directory. |
| **Learning Center (LC)** | `/learning-center/` — posts page and content library. |
| **Knowledgebase** | Troubleshooting CPT with **no archive**; surfaces under `/service-hub/<machine>/`. |
| **Department** | `content_department` taxonomy; Service Hub filters on `service-repair`. |
| **Machine tag** | Product tag linking content back to a machine — the cross-sell join key. |
| **Alias map** | Reconciliation in `machine-product-data.php` when machine key / Woo slug / profile tag disagree. |
| **Replayable script** | Numbered idempotent `scripts/db/NNN-*.sh`. Legitimate DB change path. |
| **Fresh prod pull** | Local DB replaced with production. Wipes anything not in git. |

Trailers (TR12/TR23) are not machines (`app/inc/trailer-data.php`). Product marks
in copy (QCPP, QWIKSwap™, EZE CHANGE, etc.) have no glossary — treat
`docs/ntm-machines.md` as reference, not contract.

**Unknown:** a few accessory-map machine keys lack data files; legacy vs DB-only
is unverified.

## Core entities

Three storage substrates — knowing which owns what is the load-bearing fact:

| Substrate | Owns | Survives fresh prod pull? |
|---|---|---|
| `app/data/machines/*.php` (git) | Narrative, specs, FAQ, **price**, schema | **Yes** |
| WooCommerce products (DB) | Permalink, name, image, SKU, category, tags | No |
| CPT posts (DB, CPT-UI) | Content library | No |

**Price gotcha.** Machines have no Woo price. Cards and schema read the data
file. Accessories and trailers *do* carry Woo prices — machine-specific rule.

`app/inc/machine-product-data.php` is the seam: any of machine key, Woo slug, or
profile tag → resolved data/link. Four slug namespaces (data key, Woo slug,
profile tag, configurator slug) — assume none match. Alias coverage is partial
by design; missing literature degrades instead of 404ing.

**Content registration:** `knowledgebase` and `content_department` are
code-registered so they survive release. Other owner-facing CPTs (`profile`,
`manual`, `video`, …) are DB-side (CPT-UI). `post-types.php` is display config
only — it registers nothing. New structured content that must survive release
belongs in theme code.

**Product categories:** `roof-wall-panel-machines`, `gutter-machines`,
`profiles`, `accessories-add-on-equipment`. Machine categories get the machine
template; accessories get `single-accessory.php`; dual membership is treated as
machine.

## Ownership boundaries

Layer rules: `docs/architecture-boundaries.md`. The ones that bite:

- **`app/` is the theme.** WordPress activates `amt-ntm/app`, not the repo root.
- **Bootstrap is `app/functions.php`.** Constants + ordered `inc/*` loads. No logic.
- **PHP:** `declare(strict_types=1)`, `Standard` namespace (sub-namespaces per module).
- **Navigation is hardcoded PHP** — do not register WP menu locations.
- **Templates compose, JS behaves** — modules under `app/resources/js/modules/`.
- **Tailwind-first; mobile-first only.** Base = mobile; scale up with `sm:`/`md:`/`lg:`. Never `max-*:`.

## External systems

| System | Role |
|---|---|
| **WooCommerce** | Catalog only — products, categories, tags. Overrides in `app/templates/woo/` (`app/woocommerce/` is empty placeholder). |
| **HubSpot** | Every form and CRM (`app/inc/hubspot.php`). |
| **Corbel** | External configurator; per-machine `configurator_slug`. |
| **Yoast** | Prod titles/meta/schema; `app/inc/seo.php` is the no-plugin fallback. |
| **Schema Pro** | Kept only for FAQ + VideoObject; Product/graph paths disabled. |
| **Redirection** | DB redirects; export `db/redirects.json`, replay via `scripts/db/`. |
| **CPT-UI / ACF** | DB-side CPTs and fields; ACF calls are `function_exists`-guarded. |
| **Relevanssi** | Full-page search weights (`app/inc/search.php`). |
| **Wistia / YouTube / Vimeo** | Only allowed video hosts (`app/inc/video.php`). |
| **DevKinsta / Kinsta** | Local vs staging deploy; live is a manual MyKinsta push. |

Fonts are self-hosted Noto — no font CDN.

## Invariants

Break one and something silently regresses, usually at release.

1. **The DB is not in git.** Durable DB changes need a replayable file in the same task (`scripts/db/`, `db/redirects.json`, or `db/acf-cptui/`).
2. **Migrations are idempotent, resolve by slug not post ID, default `DRY_RUN=1`.** Hardcoded IDs are landmines after a fresh pull.
3. **A slug change is always two files** — slug script *and* old→new 301 in `db/redirects.json`.
4. **Machine price lives in `app/data/machines/<key>.php`.** Never hardcode price in a template.
5. **Any two-segment path under `/machines/` is a Woo product permalink.** New pages under `/machines/` must be one segment deep (#38).
6. **Theme is the only Product schema emitter** on machine pages. Do not gate `machine-schema.php` behind an SEO-plugin check.
7. **No product-category archive renders raw Woo markup** — archives redirect.
8. **Cross-environment URLs go through `Url\canonical()`.** Never emit a raw `newtechmachinery.com` URL in a template (#53).
9. **`app/safelist.txt` is for DB-emitted class names only**, not core block styles.
10. **Pricesheets never surface in search** (fails closed).
11. **Rewrite rules flush on version bump**, not every load — bump when adding a virtual route.
12. **Financing rates, safety, and certification claims are reviewed copy** (`docs/legal/`).
13. **Deck and presenter notes change together** (`docs/presentation/slides.html` + `notes.html`, index-aligned).
14. **`npm run build` must pass before review.**
15. **Release strips dev-only paths** listed in `scripts/release/dev-tooling-paths.txt`.

## Sources of truth

| Question | Authority |
|---|---|
| Machine price, spec, media? | `app/data/machines/<key>.php` |
| Products, categories, tags? | WooCommerce DB (captured via `scripts/db/`) |
| Current area behavior? | `docs/specs/<area>.md` — **never hand-edit** |
| Repo structure? | `docs/architecture/map.json` |
| Product flows? | `docs/architecture/flows.json` |
| Visual system? | `DESIGN.md` |
| Product purpose? | `PRODUCT.md` |
| DB persistence? | `docs/superpowers/specs/db-persistence-strategy.md` |
| Cross-cutting *why*? | `docs/decisions/README.md` |
| Pending work? | GitHub issues via `admiral task ready` — not `plans/` or `TODO.md` |
| Navigation? | `app/inc/desktop-nav.php`, `app/inc/mobile-nav.php` |

## Known ambiguities

Do not resolve by assertion — resolve and record.

- **Do migrations run against production at release, or is prod fixed separately?** Undecided. Scripts are environment-agnostic on purpose.
- **`db-persistence-strategy.md` is stale** on the redirects import script (it exists) and redirect count (372). Trust the files; fix the doc when next in that area.
- **`app/woocommerce/` is an empty placeholder**; real overrides are in `app/templates/woo/`. Reserved vs dead scaffolding is unverified.
- **ACF/CPT-UI export verb is unverified** for installed versions. Field types are inferred from consumers.
- **Is there a `machine` taxonomy?** Search allowlists one; nothing in code registers it; real queries use `post_tag`. Needs DB inspection.
- **No analytics code in the theme.** If it runs, it is plugin or host injection.
- **No authoritative plugin list** in repo — external systems table is a floor, not a ceiling.
- **`pricesheet` / `cutlist` have no single templates** — fall-through vs intentional is unverified.
- **Schema Pro keep-or-kill is open** (FAQ + Video only today).
- **Issue `## Rationale` is often left blank on land** — blank ≠ no reason.
- **No data-model flag for manual language** (some are Spanish).
- **Spec log has duplication** (`docs/specs/db.md` / `database.md`) — append-only, never hand-edit.
