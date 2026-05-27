# Buyer Journey Navigation Flow Plan

**Date:** 2026-05-27  
**Status:** Draft — companion to navigation simplification plan  
**Purpose:** Define what users see, where links send them, and how the site moves buyers from first visit to configurator/contact.

---

## The Big Problem

The current site has many good pieces, but the journey is not fully disciplined.

The homepage already has an intended flow in `app/front-page.php`:

```text
Capture → Route → Sell → Educate → Close
```

That is a good structure. The issue is that the links and nav do not always reinforce one clean next step. Users can move forward, sideways, or backward into overlapping content hubs.

The fix is to design the site around **three buyer states**:

```text
1. I know what I need.
2. I am comparing options.
3. I am ready to price/talk.
```

Every major page should route those three states clearly.

---

## Recommended Primary Flow

```text
Homepage
├─ User knows category
│  ├─ Roof & Wall Panel Machines
│  │  ├─ Product page
│  │  │  ├─ Configurator
│  │  │  └─ Contact
│  │  └─ Machine resources: profiles, footprint, manuals, accessories
│  └─ Seamless Gutter Machines
│     ├─ Product page
│     │  ├─ Configurator
│     │  └─ Contact
│     └─ Machine resources: profiles, footprint, manuals, accessories
│
├─ User is unsure
│  ├─ Machine Quiz / Finder
│  ├─ Machines comparison page
│  └─ Talk to a Specialist
│
└─ User is learning
   ├─ Learning Center article/video/buying guide
   ├─ Related machine category
   ├─ Product page
   └─ Configurator or Contact
```

This is the flow stakeholders should see. Everything in nav, homepage, mobile menu, machine pages, and Learning Center should support this.

---

## Homepage Recommendations

### Current homepage structure

Evidence: `app/front-page.php`.

```text
Hero slider
Explore All Machines
Machine Quiz
Why Own
Flagships
Social Proof
Learning Center
Decision Tools
Three-Step Plan
Final CTA
```

This is mostly right. It already follows buyer psychology: route first, persuade second, educate third, close last.

### What should visually happen on homepage

The homepage should have **three obvious doors above the fold / early on page**:

```text
Find your machine
├─ Roof & Wall Panel Machines
├─ Seamless Gutter Machines
└─ Not sure? Take the quiz / Talk to a specialist
```

Current issue: the hero slider sends users to whatever slide CTA exists. The Explore Machines section is doing the clearer routing job. That means the hero may be beautiful, but the actual choice architecture happens lower.

### Recommended homepage adjustment

Add or emphasize a compact routing band immediately after the hero, before deeper product cards:

```text
What are you trying to do?
[Make roof/wall panels] [Make seamless gutters] [Not sure — help me choose]
```

Link targets:

| Visual door | Link |
|---|---|
| Make roof/wall panels | `/roof-wall-panel-machines/` |
| Make seamless gutters | `/seamless-gutter-machines/` |
| Not sure — help me choose | `/machines/#which-machine` or quiz |

Do not make the first choice “Resources” or “Learning Center.” Those are support paths, not primary buyer doors.

---

## Desktop Navigation Recommendations

### Recommended desktop nav

```text
Machines
Learning Center
Resources
Service & Support

Utility: Build & Finance | Contact
```

### Machines mega menu

This should be the strongest, most visual menu.

```text
Machines
├─ Choose a Machine Type
│  ├─ Roof & Wall Panel Machines
│  ├─ Seamless Gutter Machines
│  └─ Accessories & Upgrades
│
├─ Machine Resources
│  ├─ Profiles
│  ├─ Footprints
│  ├─ Manuals
│  └─ Literature / Brochures
│
└─ Primary CTA
   ├─ Find your machine
   └─ Build & Finance / Configurator
```

Current issue: `app/templates/parts/mega-menu.php` only renders the Machines mega panel right now, despite nav data existing for other mega items. That is fine if intentional, but then Machines has to carry the buyer journey properly.

### Learning Center menu

```text
Learning Center
├─ Buying Guides
├─ Articles
├─ Videos
└─ FAQs
```

Purpose: help the user understand the purchase.

Every Learning Center item should have clear “next step” links back to relevant machine categories:

```text
Article/video → Related machines → Product page → Configurator/contact
```

### Resources menu

```text
Resources
├─ Downloads
├─ Profit calculator
├─ Financing
├─ Literature / brochures
└─ Helpful tools
```

Purpose: action/library links. Not education.

### Service & Support menu

```text
Service & Support
├─ Service & Repair
├─ Training
├─ Manuals
├─ Contact Support
└─ Owner resources
```

Purpose: owner help.

Manuals can appear in both Machines and Service because they serve two user states:

- buyer validating a machine
- owner needing help

That duplication is fine if labels are consistent.

---

## Mobile Menu Recommendations

Mobile should not be a compressed desktop sitemap. It should be a decision tree.

### Recommended mobile L1

```text
What do you need?

[Find a Machine]
[Compare / Learn]
[Resources]
[Service & Support]

Sticky bottom actions:
[Build & Quote] [Contact]
```

### Find a Machine panel

```text
Find a Machine
├─ Roof & Wall Panel Machines
├─ Seamless Gutter Machines
├─ Accessories & Upgrades
├─ Not sure? Take the quiz
└─ View all machines

Machine resources
├─ Profiles
├─ Footprints
├─ Manuals
└─ Literature
```

### Compare / Learn panel

```text
Compare / Learn
├─ Buying guides
├─ Articles
├─ Videos
├─ FAQs
└─ Machine comparison
```

### Resources panel

```text
Resources
├─ Downloads
├─ Profit calculator
├─ Financing
├─ Literature / brochures
└─ Helpful tools
```

### Service & Support panel

```text
Service & Support
├─ Service & Repair
├─ Training
├─ Manuals
└─ Contact Support
```

Current mobile issue: `app/inc/mobile-nav.php` currently has Machines, Profiles, Learning Center, Service & Support, with Resources buried at the bottom. That does not match the buyer decision tree.

---

## Machine Category Page Flow

Applies to:

- `/roof-wall-panel-machines/`
- `/seamless-gutter-machines/`

Current flow is generally strong:

```text
Hero → Product grid → comparison/supporting content → CTA
```

Recommended category-page flow:

```text
Hero
├─ Primary CTA: See Machines
└─ Secondary CTA: Help me choose / Talk to specialist

Machine lineup
├─ Product cards
└─ Compare models

Decision support
├─ Which machine is right for me?
├─ Profiles this category makes
├─ Common accessories
└─ Financing / ROI proof

Final CTA
├─ Build & Quote
└─ Talk to Specialist
```

Current issue: category hero CTAs point to `#product-grid`, which is good, but there should also be a secondary path for unsure users.

Recommended secondary CTA:

- Roof/wall: “Take the machine quiz”
- Gutter: “Talk to a gutter specialist” or “Compare gutter machines”

---

## Product Page Flow

Current product pages are the strongest part of the journey.

Evidence:

- Hero has `Build & Quote` and `See Specs`.
- Sticky subnav includes Overview, Fit, Profiles, Accessories, Footprint, Specs, Resources, FAQ, Case Study, plus Build CTA.
- Final CTA points to Configurator or Contact.

Recommended product-page flow:

```text
Product page
├─ Hero
│  ├─ Build & Quote
│  └─ See Specs
│
├─ Sticky subnav
│  ├─ Overview
│  ├─ Fit
│  ├─ Profiles
│  ├─ Accessories
│  ├─ Footprint
│  ├─ Specs
│  ├─ Resources
│  └─ Build
│
├─ Validation content
│  ├─ Is this for me?
│  ├─ What profiles does it make?
│  ├─ What accessories fit it?
│  ├─ What is the footprint?
│  └─ Manual/brochure/resources
│
└─ Close
   ├─ Open Configurator
   └─ Talk to Specialist
```

This is good. Do not overcomplicate it.

Recommended improvement: rename product-page “Resources” subnav to **Manuals & Downloads** or **Docs** if it contains manuals/brochures. “Resources” is too broad and collides with the global Resources nav.

---

## Link Structure Rules

Use these rules everywhere.

### 1. Every page gets one primary next step

| Page type | Primary next step | Secondary next step |
|---|---|---|
| Homepage | Choose machine type | Quiz/contact |
| Machines landing | Pick machine/category | Quiz/contact |
| Category page | Pick product | Compare/contact |
| Product page | Configurator | Contact |
| Learning article | Related machine/category | Download/contact |
| Resource/download | Related machine/category | Contact |
| Service page | Contact support | Manuals/training |

### 2. Avoid generic CTA labels

Bad:

```text
Learn More
View All
Resources
```

Better:

```text
Compare roof panel machines
See gutter machines
Build & quote this machine
Download the manual
Talk to a machine specialist
```

### 3. Do not send buying users sideways unless it answers a decision question

If a user is on a product page, do not push them back to generic Learning Center unless the content is directly relevant.

Better:

```text
Product page → specific profile/manual/footprint → back to product/configurator
```

Not:

```text
Product page → Learning Center archive → random content grid
```

### 4. Keep configurator/contact visible at decision moments

Configurator/contact should appear:

- in header utility
- in product hero
- in sticky product subnav
- after comparison tables
- in final CTA

It should not dominate early educational content, but once a user is on a machine/product page, it should always be nearby.

---

## Visual Journey Diagram

```text
┌──────────────────────────────────────────────────────────────────┐
│ HOMEPAGE                                                         │
│                                                                  │
│  Hero                                                            │
│  ↓                                                               │
│  What are you trying to make?                                    │
│  [Roof/Wall Panels] [Seamless Gutters] [Help Me Choose]          │
└──────────────┬──────────────────────┬────────────────────────────┘
               │                      │
               ↓                      ↓
┌─────────────────────────┐  ┌─────────────────────────┐
│ ROOF/WALL CATEGORY      │  │ GUTTER CATEGORY         │
│                         │  │                         │
│ See machines            │  │ See machines            │
│ Compare models          │  │ Compare models          │
│ Profiles made           │  │ Profiles made           │
└──────────────┬──────────┘  └──────────────┬──────────┘
               │                            │
               ↓                            ↓
┌──────────────────────────────────────────────────────────────────┐
│ PRODUCT PAGE                                                     │
│                                                                  │
│ Build & Quote     See Specs                                      │
│                                                                  │
│ Overview → Fit → Profiles → Accessories → Footprint → Docs       │
│                                                                  │
│ [Open Configurator]  [Talk to Specialist]                        │
└──────────────┬───────────────────────────────────────────────────┘
               │
               ↓
┌─────────────────────────┐        ┌──────────────────────────────┐
│ CONFIGURATOR            │   or   │ CONTACT / SPECIALIST         │
│ Build / quote / finance │        │ Guided sales path             │
└─────────────────────────┘        └──────────────────────────────┘
```

---

## Practical Implementation Plan

### Quick visual/link fixes

1. Add a clearer homepage routing band:
   - Roof/Wall
   - Gutter
   - Help me choose

2. Update mobile menu into decision-tree language:
   - Find a Machine
   - Compare / Learn
   - Resources
   - Service & Support

3. Add machine-resource links inside Machines mega menu:
   - Profiles
   - Footprints
   - Manuals
   - Literature

4. Add secondary “Help me choose” CTA on category heroes.

5. Rename broad labels where useful:
   - Product-page “Resources” → “Docs” or “Manuals & Downloads”
   - Generic “View All” → specific labels like “See all gutter machines”

### Medium-term fixes

1. Build per-machine resource bundles.
2. Add related-machine CTAs to Learning Center posts and videos.
3. Add related-machine CTAs to downloads/resources.
4. Create a proper “Compare machines” or “Find your machine” page if the quiz alone is too roof-panel-specific.

### Long-term fixes

1. Move machine-specific URLs under `/machines/`.
2. Consolidate overlapping resources/download/literature content types.
3. Build a machine finder that handles both roof/wall and gutter buyers.

---

## My Strong Recommendation

Do not start with redirects. Start with the journey.

The fastest meaningful improvement is:

```text
Homepage routing band
+ mobile decision-tree menu
+ Machines mega menu resource links
+ category-page secondary CTA
+ product-page Docs label cleanup
```

That gives stakeholders a visible improvement immediately and proves the new journey before touching URLs.
