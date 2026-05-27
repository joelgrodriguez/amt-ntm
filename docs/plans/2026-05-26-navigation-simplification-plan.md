# Navigation Simplification Plan

**Date:** 2026-05-26
**Status:** Draft — for stakeholder review
**Author:** Claude (search worktree)
**Decision needed:** Approve overall direction before any code/URL changes.

---

## TL;DR

NewTech Machinery's primary navigation has two problems:

1. **"Resources" and "Learning Center" need clearer boundaries.** Resources can be a broad utility/library destination — a page, PDF, finance page, tool, or outside gated HubSpot destination. Downloads are narrower: downloadable/gated assets such as PDFs or outbound gated links.
2. **Profiles, footprints, manuals, and literature are machine attributes mis-shelved as "learning."** They belong with the machine the user is buying or owning, not in an educational browse experience.

The fix is two phases:

- **Quick Wins (1–2 days, no URL changes):** Clarify the top-level nav labels and move machine-attribute content into the Machines mega menu. Align mobile and desktop nav.
- **URL Restructure (2–4 weeks, with 301 redirects):** Move machine-specific CPT permalinks under `/machines/`. Surface per-machine resource bundles on each machine product page.

Both phases reduce primary nav clutter, give users one mental model, and connect the buyer journey from awareness → consideration → decision → ownership without forcing branch-hopping.

---

## Current State (the diagnosis)

### Primary navigation today

**Desktop** (`app/inc/desktop-nav.php`):

| L1 item | Type | Sub-items |
|---|---|---|
| Machines | Mega (tabbed) | Roof & Wall, Seamless Gutter, Accessories |
| Profiles | Mega (tabbed) | R&W Profiles, Gutter Profiles, Clip Relief / Rib Rollers |
| Resources | Link | (catalog page — owner-resources) |
| Learning Center | Mega (tabbed) | Articles, Videos, Downloads |
| Service & Support | Link | (service hub) |
| Utility rail | — | Service & Repair, Build & Finance, Contact |

**Mobile** (`app/inc/mobile-nav.php`):

| Position | Item |
|---|---|
| Top | Machines, Profiles, Learning Center, Service & Support |
| Featured CTA | "Find your machine" |
| Contact CTA | Contact us |
| Bottom | Service & Repair, Resources |

Mobile and desktop are not aligned. Resources is L1 on desktop but buried at the bottom on mobile. Profiles is L1 on both but mobile loses the tabbed structure.

### Where the content actually lives

Five "content" post types currently sit under or near the Learning Center URL space:

| Post type | Current URL pattern | Intent | Should live |
|---|---|---|---|
| `post` (articles) | `/category/...` | Educational | Learning Center ✅ |
| `video` | `/video/<slug>/` | Educational | Learning Center ✅ |
| `download` | `/download/<slug>/` | Downloadable/gated asset — PDF or outbound gated HubSpot destination | Learning Center / Resources |
| `resource` | `/resource/<slug>/` | Broad utility/library item — can be a page-like destination, PDF, finance page, tool, or curated link | Resources |
| `literature` | `/literature/<slug>/` | Machine-specific PDF | Machines |
| `manual` | `/manual/<slug>/` | Machine-specific PDF | Machines |
| `footprint` | `/learning-center/footprint/<slug>/` | Machine spec | Machines |
| `profile` | `/profile/<slug>/` | What machines produce | Machines |

The owner-resources page (`/resources/`) ends with a "Visit the Learning Center" closer (`app/page-owner-resources.php` line 86–131). That is a useful clue: Resources and Learning Center should be siblings with distinct jobs, not duplicates. Resources should be a curated utility library; Learning Center should teach and guide.

### Why this hurts users

A prospect researching "do I want a Mach II 5 or a Mach II 6?":

1. Goes to Machines → Seamless Gutter → picks a candidate.
2. Wants to see what profiles it produces. Has to back out to **Profiles** (separate L1).
3. Wants to see the footprint to plan their trailer. Has to dig into **Learning Center → Footprints**.
4. Wants to download the spec sheet. Goes to **Resources**? Or **Learning Center → Downloads**? Or **Manuals** (which lives where?).
5. Bounces.

Today the IA forces users to know our internal post-type taxonomy. That's backwards.

---

## Proposed End State

### The mental model in one sentence

> **Machines** = anything tied to a specific machine.
> **Learning Center** = educational content not tied to one machine.
> **Service & Support** = humans helping owners.
> Everything else is a utility link.

### The new primary nav

```
┌─────────────────────────────────────────────────────────────────┐
│ NEWTECH MACHINERY — PRIMARY NAV                                 │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  MACHINES ▾         LEARNING CENTER ▾    RESOURCES ▾            │
│  ├ Roof & Wall      ├ Articles           ├ Downloads            │
│  ├ Seamless Gutter  ├ Videos             ├ Financing            │
│  ├ Accessories      ├ Buying Guides      ├ Literature           │
│  ├ Profiles         └ FAQs               └ Helpful Tools/Links  │
│  ├ Footprints       (learn and decide)    (utility library)      │
│  ├ Manuals                                                      │
│  └ UNIQ Control     SERVICE & SUPPORT ▾                         │
│                     ├ Service & Repair                          │
│                     ├ Training                                  │
│                     └ Contact Support                           │
│                                                                 │
│  Utility rail:  Build & Finance   |   Contact (highlighted)     │
└─────────────────────────────────────────────────────────────────┘
```

Five loosely defined L1 items become **four clearly defined L1 items** plus utility: Machines, Learning Center, Resources, Service & Support. Mobile mirrors desktop exactly.

### The buyer journey it enables

```
AWARENESS         →   CONSIDERATION         →   DECISION              →   OWNERSHIP
"What's a             "Roof & Wall vs           "Which Mach II             "How do I run it?"
 rollformer?"          Gutter?"                  is mine?"
─────────────         ─────────────             ─────────────              ─────────────
Learning Center  →    Machines / R&W        →   Machine product page  →   Machine page:
articles & videos     Machines / Gutter         + Profiles for it          ├ Profiles
                      comparison tables         + Footprint                ├ Footprint
                      customer stories          + Build & Finance          ├ Manual
                                                                           └ Accessories
                                                                           + Service Hub
```

Once a user enters the Machines branch in consideration, **they never have to leave it** through ownership. Today they bounce between three top-level destinations to get the same information.

---

## Phase 1: Quick Wins (1–2 days, no URL changes, no redirects)

Pure navigation/UX changes. URLs unchanged. Low risk. Ships in one worktree.

### QW1. Redefine "Resources" instead of deleting it
- **Why:** Resources is useful if it means "utility library," not "another learning center." It can include PDFs, financing pages, gated HubSpot links, calculators/tools, literature, and other high-intent destinations.
- **Edit:** `app/inc/desktop-nav.php` — keep Resources as an L1 item, but make its destination/panel clearly utility-driven: Downloads, Financing, Literature, Helpful Tools/Links.
- **Edit:** `app/inc/mobile-nav.php` — promote Resources into the same L1 order as desktop instead of burying it in the bottom group.
- **Copy rule:** Learning Center teaches. Resources helps users act.

### QW2. Add a "For Owners" group inside the Machines mega menu
- **Why:** Profiles, Footprints, Manuals are machine attributes. Surface them where users are already thinking about machines.
- **Edit:** `app/inc/desktop-nav.php` `machines` item — add a secondary group below the existing tabs containing: Profiles, Footprints, Manuals, UNIQ Control.
- **Edit:** `app/templates/parts/mega-menu.php` to render the secondary group as a footer row inside the mega panel.

### QW3. Align mobile nav with desktop
- **Why:** Users shouldn't get a different mental model on mobile.
- **New mobile L1:** Machines (panel, with same "For Owners" sub-items), Learning Center, Resources, Service & Support.
- **Bottom rail:** Contact only or Contact + Build & Finance.
- **Edit:** `app/inc/mobile-nav.php`.

### QW4. Tighten Learning Center to learning content
- **Why:** Learning Center should answer "how do I understand, compare, and decide?" Resources should answer "where do I get the thing or next action?"
- **Keep in Learning Center:** Articles, Videos, Buying Guides, FAQs.
- **Move out of Learning Center nav:** Manuals, Footprints, Profiles, machine-specific Literature.
- **Keep Downloads available from Resources:** downloads can still be surfaced in Learning Center contextually, but the primary nav label should not make Downloads the whole Learning Center.

### QW5. Make "you are here" work across the new Machines branch
- **Why:** `is_current_item()` already exists. Use it.
- **Edit:** `app/inc/desktop-nav.php` line 173–180 — extend the Machines `current_paths` to include `/profiles/`, `/manuals/`, `/footprints/`, `/manual/`, `/profile/`, `/literature/`, `/uniq-control-system/`.

**Phase 1 outcome:** Same number of main destinations if needed, but each has a clean job. Same mental model on mobile and desktop. Zero SEO impact. Ships in one PR.

---

## Phase 2: URL Restructure (2–4 weeks, with 301 redirects)

This is the structural fix. Higher value, higher coordination cost.

### MT1. Move CPT permalinks under `/machines/`

| From | To | Notes |
|---|---|---|
| `/profile/<slug>/` | `/machines/profiles/<slug>/` | 301 |
| `/profiles/` | `/machines/profiles/` | 301; update WP page slug |
| `/learning-center/footprint/<slug>/` | `/machines/footprints/<slug>/` | 301 |
| `/manual/<slug>/` | `/machines/manuals/<slug>/` | 301 |
| `/literature/<slug>/` | `/machines/literature/<slug>/` | 301 |

**Implementation:** CPTs are registered outside the theme (no `register_post_type` calls in `app/`). Two options:

- **Option A (recommended):** Update CPT `rewrite` slugs in the plugin/CPT UI registration. Flush rewrites. Add bulk 301s for old paths.
- **Option B (safer fallback):** Leave CPT slugs alone. Add 301 rewrite rules following the existing pattern in `app/inc/footprints.php`. Less clean but zero plugin risk.

Recommend Option A — the redirect maintenance burden is one-time, the URL clarity is permanent.

### MT2. Per-machine resource bundles on product pages
- **Why:** The URL move is meaningless if `/product/mach-ii-5-gutter/` doesn't surface that machine's profiles, footprint, manual, literature directly on the page.
- **Existing scaffolding:** `app/inc/related-posts.php`, `app/inc/machine-product-data.php`.
- **Build:** A "Everything for this machine" module that pulls related CPTs by machine taxonomy.

### MT3. Catalog landing pages
- `/machines/profiles/`, `/machines/footprints/`, `/machines/manuals/` remain as browseable catalogs.
- Existing templates (`page-profiles.php`, `page-footprints.php`, `page-manuals.php`) work as-is. Just update the WP page slugs.

### MT4. Redirect inventory and SEO checklist
- Create `app/inc/redirects.php` to centralize the 301 map.
- Pull the full URL list from GSC for each affected CPT before launch.
- Submit updated sitemap to GSC after launch.
- Monitor GSC for 404 spikes for 30 days post-launch.
- Internal link audit: anywhere the theme hard-codes `/profile/`, `/manual/`, etc., update to new path.

---

## Phase 3: Longer-Term Polish (quarter-scope)

- **LT1.** Faceted search across all machine resources on the machine product page (one search box, results grouped by type — profiles, footprint, manual, literature, videos).
- **LT2.** Soft personalization: once a user views a machine, show a "Own this machine? See manuals + profiles" CTA on subsequent visits. Cookie-based.
- **LT3.** Define a formal Resources content model instead of treating it as a dumping ground: resource type = PDF, page, gated HubSpot link, finance/tool, external link, or machine literature. Downloads stay narrower: asset/link records users can download or request.
- **LT4.** Buyer-stage landing pages as a fourth Learning Center tab: "Buying Guide" → `/why-portable/`, `/compare/`, `/build-finance/`.

---

## Risks and Open Questions

| Risk | Mitigation |
|---|---|
| 301 redirects affect SEO | Full GSC URL inventory before launch; monitor 30 days post-launch |
| Users have bookmarks to `/profile/<slug>/` etc. | 301s handle this; no broken links |
| Plugin (CPT UI?) owns the CPT slugs | Coordinate with whoever maintains the CPT registration; or use Option B |
| Editors trained on current taxonomy | One-page editor doc + Slack announcement before launch |
| Internal hard-coded links in theme | Grep audit before launch; centralize in URL helpers |

### Open questions for stakeholders

1. **Who owns CPT registration?** A plugin, CPT UI, or a custom mu-plugin? This determines Option A vs B for MT1.
2. **What belongs in Resources vs Downloads?** Proposed rule: Resources can be broad destinations; Downloads are asset/link records for downloadable or gated files.
3. **SEO sign-off needed before URL moves.** Who's the SEO owner? They should review the redirect map before Phase 2 ships.
4. **Marketing campaigns linking to current URLs?** Any active ad campaigns or email sends pointing at `/profile/`, `/manual/`, etc.? Coordinate launch timing.

---

## Recommended Sequencing

```
Week 1:        Phase 1 (Quick Wins)            ── ships immediately
Week 2:        Stakeholder review of this doc  ── approve Phase 2 scope
Week 3:        GSC URL inventory + redirect map drafted
Week 4–5:      Phase 2 build (URL moves, redirects, per-machine bundles)
Week 6:        Phase 2 launch + 30-day monitoring
Quarter 2:     Phase 3 polish work
```

---

## Stakeholder One-Pager Pitch

> **Problem:** Our nav has 5 top-level items, but the boundaries are fuzzy. Resources and Learning Center overlap, while profiles, manuals, and footprints — which are machine attributes — are buried in the wrong place.
>
> **Fix:** Keep Resources if it has a clear job: utility library and action-oriented destinations. Machines is the home for everything tied to a specific machine. Learning Center is for education and buyer guidance. One mental model on mobile and desktop.
>
> **Quick wins:** 1–2 days, no URL changes, no SEO risk.
>
> **Structural fix:** 2–4 weeks, with proper 301 redirects, gives users a connected buyer journey from awareness through ownership without forcing them to branch-hop.
>
> **Outcome:** Users find what they need on the first try. Sales conversations get easier because the path to "see this machine + see what it makes + see how big it is + see how to run it" is one branch deep.
