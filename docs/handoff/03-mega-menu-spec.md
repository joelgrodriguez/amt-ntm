# 03 — Mega Menu Spec

This is the authoritative structure for the new mega menu. Every link below either points to an **existing URL** (do not change the slug) or a **new URL** marked `NEW`. New URLs are suggested — confirm with the user before creating slugs that differ from these.

## Configurator placement rule (read this first)

The configurator is a **bottom-of-funnel** destination. Visitors should only arrive there after they already know which machine they want. In this mega menu:

- The configurator appears **once**, inside the *How to buy → Talk or configure* group, labeled "Configure your machine (Expert shortcut)"
- It is NOT an anchor item in any group
- It is NOT a primary CTA anywhere in the nav
- It is NOT linked from any *Choose your machine* group (browsing and configuring are different jobs)
- The persistent header CTA is "Talk to a specialist" (→ `/contact/`), not "Configure your machine"

If you find yourself adding a configurator link to a second location, stop and re-read this section.

## Header layout (left → right)

| Slot | Content | Notes |
|---|---|---|
| Logo | NTM logo | Existing asset, unchanged |
| Top nav | 4 labels (see below) | Labels only, no click target |
| Right rail | "Talk to a specialist" CTA | Persistent, links to `/contact/` |

## Top-level labels (in order)

1. **Get started** — green
2. **Choose your machine** — blue
3. **How to buy** — orange
4. **Get owner support** — aqua

Each opens a mega flyout. Layout per flyout: a 4-column grid. Column 1 is an intro / section description. Columns 2–4 are the three groups.

---

## 1. Get started (green)

**Intro column**
- Title: "Get started"
- Body: "New to portable rollforming? Learn what NTM does, decide if it fits your business, and pick a direction."
- Secondary link: "First-time buyer playlist →" (NEW — see `04-pages-to-build.md`)

**Group · Start here**
- Anchor: **What is an NTM machine?** → `/portable-rollforming-machine-equipment-types-uses/`
- How portable rollforming works → `/portable-rollforming-machine-equipment-types-uses/` (same article — confirm before duplicating)
- Roof panel vs gutter machines → `/roof-panel-vs-gutter/` **NEW**
- Top 5 misconceptions → `/portable-rollforming-misconceptions/`

**Group · See if it fits**
- Anchor: **Are you ready to manufacture?** (Quiz) → `/portable-rollforming-machine-readiness-assessment/`
- Profit calculator → `/portable-rollforming-profit-calculator/`
- Portable vs factory panel suppliers → `/portable-rollforming-vs-factory-panel-suppliers/`

**Group · Start or grow your business**
- Anchor: **Start your rollforming business** → `/start-here/` **NEW**
- First-time buyer playlist → `/first-time-buyer-playlist/`
- Learning Center → `/learning-center/`

---

## 2. Choose your machine (blue)

**Intro column**
- Title: "Choose your machine"
- Body: "Browse the catalog directly, or take a short quiz if you're not sure where to start. Both lanes live here."
- Secondary link: "See all machines →" → `/machines/`

**Group · See all machines** ← browse lane, sits leftmost
- Anchor: **All NTM machines** → `/machines/`
- Roof & wall panel machines → `/roof-wall-panel-machines/`
- Seamless gutter machines → `/seamless-gutter-machines/`
- Profiles archive → `/profiles/`
- Accessories & upgrades → `/upgrades/`

**Group · Help me choose** ← guided lane
- Anchor: **Which roof panel machine?** (Quiz) → `/roof-panel-machine-assessment-quiz/`
- Portable gutter machine selection guide (Quiz) → `/portable-gutter-machine-selection-guide/`
- What coil width should you use? (Quiz) → `/what-coil-width-should-you-use/`
- Machine chooser landing → `/choose-your-machine/` **NEW**

**Group · Compare**
- Anchor: **SSQ3 / SSQII / SSR / SSH unified table** → `/compare-roof-panel-machines/` **NEW**
- SSQII vs SSR → `/portable-roof-panel-machines-ssq-ii-vs-ssr/`
- SSR / SSH / SSQII → `/comparison-ntms-ssr-ssh-and-ssq-ii-portable-rollformers/`
- SSQ3 MultiPro → `/ssq3-multi-pro/`

> **Critical:** `/machines/` is the loudest item in the flyout. The browse lane sits **left of** the guided lane and gets the anchor styling. We are not hiding the catalog from browsers.

---

## 3. How to buy (orange)

**Intro column**
- Title: "How to buy"
- Body: "Price, financing, quote, sales contact. Configurator stays — as the expert shortcut at the end, not the front door."
- Secondary link: "Request a quote →" → `/contact/?form=quote`

**Group · Get a quote**
- Anchor: **Request a quote** → `/contact/?form=quote`
- What to know before quoting → `/getting-a-portable-rollforming-machine-quote/`
- How to get a quote on an NTM machine → `/how-to-get-a-quote-for-an-ntm-rollforming-machine/`
- NTM machine quote checklist → `/ntm-machine-quote-checklist-thank-you/` (confirm slug — looks like a thank-you page; may need a clean URL)

**Group · Understand the deal**
- Anchor: **Panel machine cost (2026)** → `/portable-roof-panel-rollforming-machine-cost/`
- Gutter machine cost (2026) → `/gutter-machine-cost-what-to-look-for/`
- Financing & leasing → `/leasing-financing/`
- Build & finance walkthrough → `/how-to-build-and-finance-your-ntm-rollformer-all-on-one-site/`

**Group · Talk or configure**
- Anchor: **Talk to a specialist** → `/contact/`
- Configure your machine (Expert shortcut) → `/configurator/`
- How buying from NTM works → `/how-buying-works/` **NEW**

---

## 4. Get owner support (aqua)

**Intro column**
- Title: "Get owner support"
- Body: "One front door for owners: open a service request, find a manual, register warranty, request parts, or buy another machine."
- Secondary link: "Open a service request →" → `/service-hub/`

**Group · Get support now**
- Anchor: **Open a service request** → `/service-hub/`
- Service Hub → `/service-hub/`
- NTM Knowledge Base → `/ntm-knowledge-base/`
- Owner support landing → `/owner-support/` **NEW**

**Group · Operate**
- Anchor: **Machine manuals** → `/manuals/`
- Request training → `/service-training/`
- What to expect in training → `/what-to-expect-portable-rollforming-machine-training-session/`
- Warranty registration → `/warranty-registration/`
- Parts request → `/request-parts/` **NEW**

**Group · Troubleshoot & buy again**
- Anchor: **Common problems & fixes** → `/common-problems-with-ntm-portable-rollforming-machines-and-how-to-solve-them/`
- Top 5 service questions → `/the-top-five-questions-the-ntm-service-department-receives/`
- Prevent voiding your warranty → `/ways-to-prevent-voiding-machine-warranty/`
- Add a machine → `/add-a-machine/` **NEW**

---

## How to implement this in code

### 1. Update `app/inc/desktop-nav.php`

Replace the entire `items` array returned by `get_desktop_nav()` with the four-item structure above. Keep the existing shape (`kind`, `id`, `label`, `type`, `tabs` or `groups`, `view_all_url`, `current_paths`). You may need to introduce a new `type` like `tabbed-groups` or `flyout-groups` that matches the new layout (intro column + 3 groups).

The current `type` values are `tabbed-products`, `tabbed-profiles`, `tabbed-content`. Pick a new type name that fits the new layout. Don't reuse those types — they're tied to old layouts in `mega-menu.php`.

### 2. Update `app/templates/parts/mega-menu.php`

The existing template handles `tabbed-products`, `tabbed-profiles`, `tabbed-content`. Add a new branch (e.g., `flyout-groups`) that renders:
- A left intro column with title + body + secondary CTA
- A 3-column grid of groups
- Each group has a header label, an anchor item, and a list of secondary links
- Anchor items get a larger / color-tinted treatment

The temporary code that filters `items` to only render the `machines` panel must be removed — once the four new panels exist, all four should render.

### 3. Update `app/inc/mobile-nav.php` and the mobile-menu template parts

The mobile menu should mirror the same four-section structure. Each section expands to show its three groups and links. Use the existing mobile-menu component — don't redesign it.

### 4. Header markup (`app/header.php`)

Confirm the persistent "Talk to a specialist" CTA renders in the header right rail. If it already exists, leave it. If it doesn't, add it using existing button styles.

### 5. Active-state highlighting

The existing `is_current_item()` logic in `desktop-nav.php` should still work. Make sure each top-level item has `current_paths` listing every URL under it (e.g., the Choose your machine item lists `/machines/`, `/roof-wall-panel-machines/`, `/seamless-gutter-machines/`, all product slugs, etc.). The full URL list is in `05-pages-to-reuse.md`.

---

## What you do NOT do

- Do not change the visual design of the mega-menu container, fonts, padding, or animation. Use what's there.
- Do not add new CSS files. Add minimal class names if needed and reuse existing utilities.
- Do not change URLs of any existing page.
- Do not delete the configurator. It stays as a secondary link inside the *How to buy → Talk or configure* group (the group's anchor is "Talk to a specialist", not the configurator). See the "Configurator placement rule" at the top of this file.
