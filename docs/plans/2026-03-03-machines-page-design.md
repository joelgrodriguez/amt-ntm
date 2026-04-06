# Machines Page Design

## Overview

A dedicated `/machines` page showcasing NTM's full product lineup, inspired by Toyota's trucks landing page. Features the SSQ3 flagship with a spotlight section, UNIQ technology highlight, full machine lineup grid, and specs comparison table.

## Data Source

Hardcoded arrays from `ntm-machines.md` research brief in `app/inc/machines-data.php`. No WooCommerce dependency.

## Architecture

Follows the front-page pattern: `page-machines.php` orchestrates template parts loaded via `get_template_part()`. Section templates live in `app/templates/pages/machines/`.

## Page Sections

### 1. Hero Banner

- Full-width image with dark overlay
- Headline: "The World's Finest Portable Rollforming Equipment"
- Subtext: "Machines built for the metal construction industry since 1991"
- CTA: "Explore the Lineup" (anchor scroll to lineup)

### 2. Brand Statement

- Centered text block
- Headline: "Built for the Jobsite"
- Body: On-site fabrication value prop — eliminates factory delays, transportation costs, damage. 40+ countries, all seven continents.

### 3. Machine Lineup Grid — "Machines for Every Project"

- Responsive grid: 1-col mobile, 2-col tablet, 3-col desktop
- All 7 machines as cards (image, name, tagline, CTA)
- Grouped: Roof Panel Machines | Wall Panel | Gutter

### 4. SSQ3 Feature Spotlight

- Split layout: large image left, content right
- Eyebrow: "NEW — Flagship"
- Title: "SSQ3™ MultiPro Roof Panel Machine"
- Key improvements bullet list (sealed covers, RFID sensors, strobe light, LEDs, slug funnel, bottom guarding)
- CTA button

### 5. Full-Bleed Image Break

- Full-width dramatic jobsite/lifestyle photo
- Optional caption overlay
- Visual breathing room

### 6. UNIQ® Technology Spotlight

- Dark/charcoal background
- Eyebrow: "Technology"
- Title: "UNIQ® Automatic Control System"
- Feature highlights: touchscreen, cut list upload, troubleshooting, CE-compliant safety
- Availability: "Standard on WAV · Optional on SSQ II & SSQ3"

### 7. Key Differentiators — 3-card grid

- "On-Site Fabrication" — eliminates delays and damage
- "Up to 16 Profiles" — maximum versatility
- "Retrofit-Friendly" — innovations fit existing equipment

### 8. Comparison Table

- Responsive table (horizontal scroll on mobile)
- Columns: Machine, Profiles, Speed (FPM), Power Options, Shear, Best For
- All 7 machines
- SSQ3 row highlighted as flagship

### 9. Final CTA — "Ready to Roll?"

- Centered section with contact/inquiry CTA buttons

## Files

| File | Purpose |
|------|---------|
| `app/page-machines.php` | Page template orchestrator |
| `app/inc/machines-data.php` | Hardcoded machine data arrays |
| `app/templates/pages/machines/hero.php` | Hero banner |
| `app/templates/pages/machines/brand-statement.php` | Brand value prop |
| `app/templates/pages/machines/lineup-grid.php` | Machine cards grid |
| `app/templates/pages/machines/ssq3-spotlight.php` | SSQ3 feature section |
| `app/templates/pages/machines/image-break.php` | Full-bleed photo |
| `app/templates/pages/machines/uniq-spotlight.php` | UNIQ technology section |
| `app/templates/pages/machines/differentiators.php` | 3-card differentiators |
| `app/templates/pages/machines/comparison-table.php` | Specs comparison table |
| `app/templates/pages/machines/final-cta.php` | Final CTA |

## Design Tokens Used

- Colors: primary (#0078C2), secondary (#F7951D), dark (#18181b)
- Fonts: IBM Plex Sans/Serif/Mono
- Section spacing: `.section` (py-16 md:py-20 lg:py-24)
- Components: `.btn-primary`, `.btn-secondary`, `.section-header`, `.section-eyebrow`, `.section-title`
- Patterns: `.pattern-dot-grid`, `.pattern-square-grid`
- Animations: `.reveal`, `.stagger`
