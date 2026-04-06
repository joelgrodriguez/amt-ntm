# Machine Product Landing Page — Design Analysis & Plan

**Date:** 2026-03-13
**Direction:** Hybrid — Toyota structure + Rivian polish
**Scope:** WooCommerce single product template for **machine pages only** (not accessories)
**References:** Toyota Tundra, Rivian R1T, current NTM SSQ3 MultiPro page

---

## Part 1: Competitive Analysis — Toyota Tundra & Rivian R1T

### 1.1 Marketing Philosophy

Both Toyota and Rivian treat their product pages as **immersive brand experiences**, not spec sheets. The core philosophy shared by both:

| Principle | Toyota Tundra | Rivian R1T |
|---|---|---|
| **Lead with emotion, not specs** | "Go out and grind. All day, every day." — lifestyle headline, no specs in the hero | Full-bleed cinematic video hero with no text overlay — pure aspiration |
| **Specs earn attention** | Key specs (437 HP, 12,000 lbs towing) appear *after* the emotional hook, presented as proof of the promise | Specs are nested deep, presented in a comparison table at the bottom |
| **Show, don't tell** | Large contextual photography — truck in mud, on job sites, towing | Full-width video, adventure photography, animated illustrations |
| **Progressive disclosure** | Surface the headline benefit → expand on demand | Minimal text, large visual blocks → details revealed by scrolling/clicking |

**Key insight:** Both brands follow a **narrative arc** — they don't dump information. The page tells a story: *desire → proof → configuration → action*.

---

### 1.2 Content Strategy Breakdown

#### Toyota Tundra — "The Working Hero's Journey"

**Narrative structure:**
1. **Hero** — Aspirational lifestyle headline + hero image gallery (5 angles/colors)
2. **Key specs strip** — 4 numbers in a horizontal bar (HP, torque, towing, payload) — social proof through hard data
3. **Trim selector** — Interactive carousel showing all 11 trims with price + MPG, swipeable
4. **Feature storytelling** — Each feature category gets its own visual section:
   - "Equipped for the day, and the day off" (bed & cargo)
   - "Make a power play" (powertrain)
   - Deep photography + short punchy copy per feature
5. **Exploded vehicle breakdown** — Visual sections isolating truck subsystems: body/bed, powertrain/drivetrain, chassis/frame, interior cabin. Each presented as a "chapter" with its own hero image showing that subsystem and supporting feature details
6. **Interior experience** — Gallery-style section with large cabin shots
7. **Technology & safety** — Grid of tech features with icons
8. **Bottom-funnel CTAs** — Three cards: Payment Options / Reviews & Awards / Build & Configure

**What Toyota does well:**
- **Trim comparison is front and center** — users can compare at a glance without leaving the page
- **Every section has a CTA** — "Build," "Explore," "See More" buttons are woven throughout, not just at the bottom
- **Social proof embedded naturally** — KBB Best Resale Value award, not a separate testimonials section
- **Price transparency** — MSRP shown per trim, payment estimator available
- **Progressive complexity** — casual browsers get the headline story; serious buyers can drill deep
- **Vehicle decomposition** — Breaking the truck into its subsystems (body, drivetrain, chassis) makes a complex product digestible. Each subsystem becomes its own mini-story

#### Rivian R1T — "The Premium Tech Experience"

**Narrative structure:**
1. **Hero** — Full-bleed cinematic video, zero text — pure brand immersion
2. **Value propositions** — 4 horizontal tabs: "Adventure-ready," "Performance," "Range," "Interior" — each with a subtitle
3. **Adventure gallery** — Large lifestyle imagery grid showing the truck in varied environments
4. **Technology deep-dive** — Animated/illustrated section showing unique features (Gear Tunnel, camp kitchen)
5. **Blueprint / technical schematic** — Dark background section with a technical line-drawing of the vehicle showing dimensions, wheelbase, ground clearance, and key measurements. Rendered in a monoline engineering-drawing style (light strokes on dark bg) that feels like you're looking at the actual engineering spec sheet. This is the "we're serious engineers" moment
6. **Software & ecosystem** — Dark background section with app screenshots and illustrations
7. **Interactive 360° rotator** — Users can click/drag to rotate the vehicle, viewing it from any angle. Smooth CSS/JS-driven rotation through pre-rendered frames
8. **Interior panorama** — 360-style cabin visualization
9. **Model comparison** — "Find the R1T that's right for you" — side-by-side table of 4 configurations with key specs
10. **FAQ accordion** — Addresses purchase concerns
11. **Sticky footer CTA** — "Never miss an adventure — get updates from Rivian"

**What Rivian does well:**
- **Visual-first, copy-second** — minimal text, maximum imagery. Every section is a visual statement
- **Dark/light alternation** — creates visual rhythm and prevents fatigue
- **The blueprint section** — communicates engineering credibility through aesthetics. The monoline technical drawing says "this was designed by real engineers" without words
- **The 360° rotator** — lets users explore the product on their own terms, creating engagement and ownership
- **The comparison table is the decision tool** — not a spec dump, but a curated selection guide
- **FAQ addresses objections** — proactively handles "but what about..." concerns
- **Sticky CTA** — always one action available without scrolling

---

### 1.3 Design Choices

#### Layout & Visual Hierarchy

| Element | Toyota | Rivian |
|---|---|---|
| **Hero treatment** | Image gallery with overlay text | Full-bleed video, no text |
| **Section spacing** | Moderate — dense information | Generous — breathing room between sections |
| **Background rhythm** | White → light gray alternation | White → dark gray/black alternation |
| **Typography** | Bold sans-serif headlines, body serif accents | Clean sans-serif throughout, large headlines |
| **Image style** | Professional product photography, contextual lifestyle | Cinematic, editorial, aspirational |
| **CTA style** | Red accent buttons (brand color), text links | Green accent buttons, minimal |
| **Information density** | High — lots of specs, features, options visible | Low — curated, progressive disclosure |
| **Navigation aid** | Sticky trim selector | Sticky footer CTA |
| **Color palette** | Neutral + red accent | Earth tones + forest green accent |
| **Grid structure** | 2-3 column feature grids | Full-width sections, asymmetric layouts |

#### Shared Design Patterns Worth Noting

1. **Full-width hero with no sidebar** — the product IS the page
2. **Horizontal key-stats bar** — 3-5 headline numbers immediately after the hero
3. **Alternating section backgrounds** — creates visual chapters
4. **Large contextual photography** — the product in use, not on a white background
5. **Comparison/configuration section** — helps users self-select
6. **Sticky navigation element** — always accessible CTA or trim selector
7. **Social proof woven in** — awards, stats, not a separate block
8. **FAQ or objection handling** — near the bottom, before final CTA
9. **Product decomposition** — both brands break the vehicle into subsystems to tell focused stories
10. **360° / multi-angle viewing** — both offer interactive or gallery-based rotation of the product

---

## Part 2: Current NTM Product Page Analysis

### 2.1 What the SSQ3 MultiPro Page Communicates

The current NTM product page is a **spec-first technical document**. Its structure:

1. Product title + tagline
2. Price range
3. Feature bullet list (16 items)
4. Standard features list
5. Dimensions & specifications tables
6. Performance specifications
7. Materials formed table
8. Coil specifications
9. Power options table
10. Warranty & patents
11. Profile options (16 profiles with descriptions)
12. Accessories & add-on equipment (20+ items with prices)
13. Resources (manuals, brochures)
14. Related products

### 2.2 Strengths of the Current Approach

- **Comprehensive** — a contractor researching this machine can find every spec they need
- **Profile options are well-organized** — the 16 profiles are clearly categorized
- **Accessories section drives AOV** — upsell opportunity is present
- **Resources section builds trust** — manuals and brochures show professionalism

### 2.3 Problems to Solve

| Issue | Impact |
|---|---|
| **No emotional hook** | A contractor lands on this page and sees a wall of specs. There's no story about how this machine will change their business |
| **No outcome/ROI framing** | The page doesn't answer "How much money will I make with this?" — the #1 question for a $121K purchase |
| **No visual storytelling** | The page reads like a PDF datasheet, not a premium product experience |
| **No progressive disclosure** | All information is dumped at once — overwhelming for browsers, fine for researchers |
| **No social proof** | No customer testimonials, case studies, or "X machines deployed" data |
| **No comparison/decision help** | If someone is choosing between the SSQ3 and SSH, there's no guidance on this page |
| **Weak CTAs** | "Build & Finance" and "Contact for Quote" are buried; no persistent CTA |
| **Accessories feel like an afterthought** | They're a flat list at the bottom rather than curated recommendations |
| **No video** | For a $121K machine, buyers want to see it in action — forming panels, on a job site |

---

## Part 3: WooCommerce Template Strategy

### The Three Approaches

#### Approach A: Hook Manipulation (remove/add actions in functions.php)

Strip WooCommerce's default single-product hooks and inject custom template parts:

```php
add_action('wp', function (): void {
    if (!is_singular('product') || !has_term('roof-wall-panel-machines', 'product_cat')) {
        return;
    }
    // Remove all default hooks
    remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20);
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
    // ... 10+ more remove_action calls
    // Then add custom hooks
    add_action('woocommerce_before_single_product_summary', function () {
        get_template_part('app/templates/pages/product/hero');
    }, 10);
});
```

| Pros | Cons |
|---|---|
| No template versioning burden | 15+ remove/add calls — hard to reason about |
| Survives WooCommerce updates | Can't restructure wrapper HTML (`<div class="product">`, summary columns) |
| Granular per-section control | Priority conflicts with plugins |
| | Debugging requires tracing hook execution order |

**Verdict:** Good for minor tweaks. Not viable for a fundamentally different layout.

#### Approach B: Template Override (woocommerce/ directory in theme)

Copy WooCommerce templates into `theme/woocommerce/` and modify them:

```
app/woocommerce/
  single-product.php            ← Overrides WooCommerce's single-product.php
  content-single-product.php    ← Overrides the content template
```

| Pros | Cons |
|---|---|
| Full control over HTML | Must track `@version` tags — WooCommerce updates break overrides |
| Stays within WooCommerce's template loader | Still fires WooCommerce hooks unless you strip them |
| Category routing via `wc_get_template_part()` | Two systems to maintain (WooCommerce templates + theme templates) |

**Verdict:** Middle ground. Works but carries maintenance debt from version tracking.

#### Approach C: `template_include` Filter (Recommended)

Bypass WooCommerce's template system entirely for machine products. Use WordPress's `template_include` filter to swap the template file before it loads:

```php
add_filter('template_include', function (string $template): string {
    if (!is_singular('product')) {
        return $template;
    }

    $machine_cats = ['roof-wall-panel-machines', 'gutter-machines'];
    if (has_term($machine_cats, 'product_cat')) {
        $custom = get_theme_file_path('app/templates/pages/product/single-machine.php');
        if (file_exists($custom)) {
            return $custom;
        }
    }

    return $template; // accessories get default WooCommerce template
}, 99); // priority 99 = runs after WooCommerce's own template_include (priority 10)
```

| Pros | Cons |
|---|---|
| Zero WooCommerce template files to maintain | Must manually call `wc_setup_product_data()` if outside the loop |
| Complete HTML control — no wrapper divs, no hook baggage | Loses WooCommerce's built-in structured data hook (easy to re-add) |
| Fits existing theme architecture (`app/templates/pages/`) | Must explicitly opt-in to any WooCommerce features you want |
| No version tracking, no hook conflicts | |
| Works with Vite + Tailwind pipeline naturally | |

**Verdict: Best fit for this project.** The NTM theme already has a custom template architecture. This approach lets machine product pages be full custom templates (like `page-machines.php`) while accessories keep WooCommerce defaults untouched.

### Decision: **Approach C — `template_include` filter**

**Why:**
1. **SEO preserved** — Same URL, same product post, same canonical. WooCommerce product data (`$product = wc_get_product()`) is still available.
2. **No template versioning** — Zero `@version` maintenance. WooCommerce can update freely.
3. **Matches existing patterns** — The theme already uses `page-machines.php`, `page-roof-wall-panel-machines.php` etc. as custom page templates. This is the same pattern extended to products.
4. **Clean separation** — Accessories use default WooCommerce. Machines get a completely custom experience. No conditionals scattered across hooks.
5. **All content migrates** — Specs, profiles, accessories all render from the new template. Nothing is lost.

### What we keep from WooCommerce

Even with a fully custom template, we still get:
- **`$product` global** — `wc_get_product(get_the_ID())` gives us price, SKU, attributes, gallery, everything
- **Product structured data** — Re-add with one line: `do_action('woocommerce_single_product_summary')` calls `WC_Structured_Data::generate_product_data()` at priority 60. Or we generate our own JSON-LD.
- **Cart/checkout integration** — Add-to-cart still works if we include `woocommerce_template_single_add_to_cart()`
- **Breadcrumbs** — `woocommerce_breadcrumb()` still callable
- **Analytics/tracking** — WooCommerce product view tracking fires on `is_singular('product')` regardless of template

---

### File Architecture

```
app/
├── inc/
│   ├── products.php                    ← Existing: product queries
│   ├── machines-data.php               ← Existing: machine catalog data
│   ├── machine-product-data.php        ← NEW: per-machine landing page content
│   └── machine-product-template.php    ← NEW: template_include filter + helpers
│
├── templates/pages/product/
│   ├── single-machine.php              ← Master template (calls get_header, sections, get_footer)
│   ├── hero.php                        ← Full-bleed hero (image now, video-ready)
│   ├── stats-bar.php                   ← 4-stat proof bar
│   ├── machine-breakdown.php           ← Subsystem sections (forming, frame, power, brain)
│   ├── blueprint.php                   ← Rivian-style dark schematic
│   ├── gallery.php                     ← Multi-angle gallery (v1) / rotator (v2)
│   ├── profile-selector.php            ← Panel profile grid with filters
│   ├── social-proof.php                ← Testimonials + stats
│   ├── comparison.php                  ← Side-by-side machine comparison
│   ├── accessories.php                 ← Curated upsell grid
│   ├── specs-accordion.php             ← Collapsible full specs (SEO-safe)
│   ├── resources.php                   ← Downloads, manuals, support links
│   ├── final-cta.php                   ← 3-card CTA section
│   └── sticky-cta.php                  ← Fixed bottom bar (JS-driven)
│
├── resources/js/modules/
│   ├── StickyProductCTA.js             ← IntersectionObserver show/hide
│   ├── SpecsAccordion.js               ← Accordion toggle (or use <details>)
│   └── ProductRotator.js               ← Future: drag-to-rotate image sequence
│
├── assets/
│   └── blueprints/                     ← SVG schematics per machine
│       ├── ssq3-machine.svg
│       ├── ssq3-trailer.svg
│       └── ...
│
└── functions.php                       ← Add: require 'inc/machine-product-template.php'
```

### Master Template Structure

`app/templates/pages/product/single-machine.php`:

```php
<?php
declare(strict_types=1);

use function Standard\MachineProduct\get_machine_data;

get_header();

$product = wc_get_product(get_the_ID());
$machine = get_machine_data($product->get_slug());

if (!$machine) {
    // Fallback: render default WooCommerce content
    wc_get_template_part('content', 'single-product');
    get_footer();
    return;
}
?>

<article <?php post_class('machine-product'); ?>>
    <?php
    get_template_part('app/templates/pages/product/hero', null, compact('product', 'machine'));
    get_template_part('app/templates/pages/product/stats-bar', null, compact('machine'));
    get_template_part('app/templates/pages/product/machine-breakdown', null, compact('machine'));
    get_template_part('app/templates/pages/product/blueprint', null, compact('machine'));
    get_template_part('app/templates/pages/product/gallery', null, compact('product', 'machine'));
    get_template_part('app/templates/pages/product/profile-selector', null, compact('machine'));
    get_template_part('app/templates/pages/product/social-proof', null, compact('machine'));
    get_template_part('app/templates/pages/product/comparison', null, compact('product', 'machine'));
    get_template_part('app/templates/pages/product/accessories', null, compact('product', 'machine'));
    get_template_part('app/templates/pages/product/specs-accordion', null, compact('product', 'machine'));
    get_template_part('app/templates/pages/product/resources', null, compact('machine'));
    get_template_part('app/templates/pages/product/final-cta', null, compact('product', 'machine'));
    get_template_part('app/templates/pages/product/sticky-cta', null, compact('product', 'machine'));
    ?>
</article>

<?php
// Preserve WooCommerce structured data (JSON-LD for Google)
do_action('woocommerce_after_single_product');
get_footer();
```

### Router Filter

`app/inc/machine-product-template.php`:

```php
<?php
declare(strict_types=1);

namespace Standard\MachineProduct;

/**
 * Route machine products to custom template.
 * Accessories and all other products use default WooCommerce.
 */
add_filter('template_include', function (string $template): string {
    if (!is_singular('product')) {
        return $template;
    }

    $machine_cats = ['roof-wall-panel-machines', 'gutter-machines'];

    if (has_term($machine_cats, 'product_cat')) {
        $custom = get_theme_file_path('app/templates/pages/product/single-machine.php');
        if (file_exists($custom)) {
            return $custom;
        }
    }

    return $template;
}, 99);
```

### Data Layer

`app/inc/machine-product-data.php` follows the existing `machines-data.php` pattern — a flat PHP array keyed by product slug. Each section of the template reads from this array. WooCommerce product data (price, gallery, SKU) comes from `$product = wc_get_product()`.

```php
<?php
declare(strict_types=1);

namespace Standard\MachineProduct;

function get_machine_data(string $slug): ?array {
    $machines = get_all_machine_data();
    return $machines[$slug] ?? null;
}

function get_all_machine_data(): array {
    return [
        'ssq3-multipro' => [
            'hero_headline'  => 'Produce 16 Panel Profiles On-Site. One Machine.',
            'hero_subtitle'  => 'The most advanced portable roof and wall panel machine we have ever built.',
            'hero_video'     => null, // future: URL to mp4
            'stats'          => [
                ['value' => '16',          'label' => 'Panel Profiles'],
                ['value' => '25 min',      'label' => 'Tooling Changeover'],
                ['value' => '75 ft/min',   'label' => 'Max Speed'],
                ['value' => '$2.25/sq ft', 'label' => 'Avg. Savings'],
            ],
            'breakdown'      => [ /* subsystem arrays */ ],
            'blueprint_svg'  => 'ssq3-machine',
            'profiles'       => [ /* profile arrays */ ],
            'testimonials'   => [ /* quote arrays */ ],
            'compare_with'   => ['ssh-multipro', 'ssr-multipro-jr'],
            'best_for'       => 'High-volume commercial & residential',
            'accessories'    => ['unq-ssq3-a', 'dr1-ssq3', 'tr12-d'],
        ],
        // ... other machines
    ];
}
```

### JS Modules — Keep It Simple

**StickyProductCTA.js** — `IntersectionObserver` on hero + final-cta to toggle a fixed bottom bar. ~30 lines.

**SpecsAccordion.js** — Use native `<details>/<summary>` elements with CSS transitions. Zero JS needed for basic open/close. Only add JS if we want "open one, close others" behavior.

**ProductRotator.js** — Deferred to Phase 4. Not needed for v1 (multi-angle gallery is just thumbnails + main image swap, doable in CSS or 10 lines of JS).

---

## Part 4: Proposed Page Sections — Hybrid Direction

### Section 1: Hero — Video-Ready with Image Fallback

**Borrows from:** Toyota's lifestyle headline + Rivian's cinematic visual

**Content:**
- Full-width background: looping video (when available) or hero image (initial build)
- Outcome-focused headline: e.g., "Produce 16 Panel Profiles On-Site. One Machine."
- Supporting subtitle with key differentiator
- Price starting point (transparency, like Toyota)
- Primary CTA: "Build & Quote" + Secondary: "Watch It Run"

**Design:**
- Full-bleed image/video with dark overlay gradient from bottom
- White text on image, left-aligned
- No sidebar — product owns the full viewport
- Video implementation: HTML5 `<video>` with poster image fallback, autoplay muted loop
- Image fallback is the default build; video is a progressive enhancement via a WooCommerce custom field (video URL)

---

### Section 2: Key Stats Strip — "The Proof Bar"

**Borrows from:** Toyota's 4-stat horizontal bar

**Content (example for SSQ3):**
- **16** Panel Profiles
- **25 min** Tooling Changeover
- **75 ft/min** Production Speed
- **$2.25/sq ft** Savings vs. Pre-Fab

**Design:**
- Horizontal bar, full-width, dark background (NTM brand dark)
- Large numbers (display font), small labels below
- 4 columns on desktop, 2×2 grid on mobile
- Subtle entrance animation on scroll

---

### Section 3: Machine Breakdown — "Inside the Machine" (Toyota-Inspired)

**Borrows from:** Toyota's exploded vehicle subsystem breakdown

This is the NTM equivalent of Toyota breaking down the Tundra into body, drivetrain, chassis, and interior. For a rollforming machine, the subsystems are:

#### A. "The Forming System" (equivalent: drivetrain)
- Roller stations, forming dies, shear system
- Headline: "Precision Forming, Panel After Panel"
- Key specs: 16 polyurethane drive rollers, hardened tool steel shear blades
- Hero image: close-up of rollers forming a panel

#### B. "The Frame" (equivalent: chassis)
- Welded tubular steel frame, powder-coated covers
- Headline: "Built to Take a Beating"
- Key specs: weight, footprint dimensions
- Hero image: frame/structure shot showing build quality

#### C. "The Power Pack" (equivalent: engine)
- Quick-Change Power-Pack (gas or electric)
- Headline: "Gas or Electric. Your Call."
- Key specs: HP, speed per power option
- Hero image: power pack unit, swap-in-action

#### D. "The Brain" (equivalent: interior/tech)
- UNIQ Automatic Control System or manual controls
- Headline: "Smart Controls, Simple Operation"
- Key specs: push-button run/jog, diagnostics, RFID sensors
- Hero image: control panel / UNIQ system screen

**Design:**
- Each subsystem gets a full-width section with alternating image-left/image-right layout
- Large contextual photography (not product-on-white)
- Dark/light alternating backgrounds for visual rhythm
- Each section: headline + 2-3 sentences + 3-4 bullet specs + contextual image
- Subtle scroll-reveal animations

---

### Section 4: Blueprint / Footprint — "Engineering Schematic" (Rivian-Inspired)

**Borrows from:** Rivian's dark-background technical line-drawing section

**Concept:**
NTM already has machine footprint PDFs with detailed dimension drawings. This section adapts that engineering document aesthetic into an interactive web section.

**Content:**
- Technical line drawing of the machine showing key dimensions:
  - Overall length (with/without angled slitter)
  - Width
  - Height (with/without overhead rack)
  - Weight
  - On-trailer dimensions
- Annotation callouts pointing to key components
- Toggle between "Machine Only" and "On Trailer" views

**Design:**
- Dark background (near-black, like Rivian's blueprint section)
- Monoline stroke illustration style — light lines on dark background
- Engineering drawing aesthetic: thin strokes, dimension lines with arrows, measurement callouts
- Monospace or technical font for dimension labels
- Subtle glow or highlight on hover for interactive callouts

**Source material:**
- NTM's existing footprint PDFs can be traced/recreated as SVGs
- Alternatively: photograph/scan the PDF, clean up, and overlay interactive hotspots
- Best approach: recreate as a clean SVG with CSS-animated callouts — fully scalable, accessible, and on-brand

**Implementation options:**
1. **Static SVG with CSS animations** (recommended for v1) — Single SVG with dimension annotations, fade-in on scroll. Lightweight, no JS dependency
2. **Interactive SVG with JS hotspots** (v2) — Click/tap on machine components to reveal specs. Uses a small JS module
3. **Toggle between views** (v2) — JS toggle switching between machine-only and on-trailer SVGs with crossfade

---

### Section 5: Product Rotator — 360° Machine View (Toyota + Rivian-Inspired)

**Borrows from:** Toyota's multi-angle gallery + Rivian's interactive rotation

**Concept:**
Allow users to rotate the machine and view it from multiple angles, creating engagement and letting them inspect the product visually.

**How automotive brands achieve this:**

There are 3 approaches, ranging from simple to complex:

#### Approach A: "Photo Sequence Rotator" (Recommended)
**How it works:** Photograph the machine from 24-36 angles (every 10-15°) on a consistent background. A JS module cycles through frames on drag/swipe.

**Technical implementation:**
- 24-36 images of the machine shot on a turntable or by walking around it at consistent height
- Images named sequentially: `machine-001.jpg` through `machine-036.jpg`
- JS module: tracks mouse drag / touch swipe → maps horizontal movement to frame index → swaps `<img>` src
- Preload all frames on section enter for smooth playback
- Fallback: if JS disabled, shows a static hero angle

**Pros:** Proven technique (used by Toyota, IKEA, many e-commerce). Looks great. Achievable with a camera and a turntable/consistent setup.
**Cons:** Requires a photo shoot per machine (24-36 shots). ~2-5MB total image payload (lazy-loaded).

**JS module sketch:**
```javascript
// app/resources/js/modules/ProductRotator.js
export default class ProductRotator {
  constructor(el) {
    this.el = el;
    this.frames = JSON.parse(el.dataset.frames); // array of image URLs
    this.currentFrame = 0;
    this.img = el.querySelector('img');
    this.bindEvents();
    this.preloadFrames();
  }

  bindEvents() {
    let startX, isDragging = false;
    this.el.addEventListener('pointerdown', (e) => { startX = e.clientX; isDragging = true; });
    this.el.addEventListener('pointermove', (e) => {
      if (!isDragging) return;
      const delta = e.clientX - startX;
      if (Math.abs(delta) > 10) {
        this.currentFrame = (this.currentFrame + (delta > 0 ? 1 : -1) + this.frames.length) % this.frames.length;
        this.img.src = this.frames[this.currentFrame];
        startX = e.clientX;
      }
    });
    this.el.addEventListener('pointerup', () => { isDragging = false; });
  }

  preloadFrames() {
    this.frames.forEach(src => { new Image().src = src; });
  }
}
```

#### Approach B: "3D Model Viewer" (Future/Premium)
**How it works:** A WebGL 3D model rendered in the browser using Three.js or `<model-viewer>` (Google's web component).

**Technical:** Requires a 3D model file (`.glb` / `.gltf`). NTM may have CAD files that can be exported. Google's `<model-viewer>` component makes this surprisingly simple:
```html
<model-viewer src="/assets/models/ssq3.glb" camera-controls auto-rotate></model-viewer>
```

**Pros:** True 360° + zoom + any angle. Most immersive experience. Single asset instead of 36 photos.
**Cons:** Requires 3D model creation from CAD. Larger file size (5-15MB). More complex to optimize. May feel "too tech" for the audience.

#### Approach C: "Multi-Angle Gallery" (Simplest)
**How it works:** 4-6 static images from key angles (front, side, rear, ¾ view, top-down, detail). User clicks thumbnails to switch views.

**Pros:** Simplest to implement. Works with existing photography. No JS module needed.
**Cons:** Not as engaging as true rotation. Fewer "wow" moments.

**Recommendation:** Start with **Approach C** (multi-angle gallery) using existing photography. Graduate to **Approach A** (photo sequence rotator) when dedicated machine photography is available. Consider **Approach B** (3D viewer) as a future premium feature if NTM has CAD files.

---

### Section 6: Profile Selector — "Your Panels, Your Way"

**Borrows from:** Toyota's trim selector + Rivian's comparison table

**Content:**
- Interactive grid or carousel of all profile options for this machine
- Each profile shows: name, cross-section diagram, seam type, typical application
- Filter by category: Mechanical Seam / Snap-Lock / Flanged / Specialty
- Selecting a profile highlights it and shows panel width range + rib options

**Design:**
- Tabbed or filtered interface
- Profile cross-section SVGs as visual identifiers (from existing brochure artwork)
- Clean grid layout, selectable cards
- Highlight selected profile with expanded detail panel

---

### Section 7: Social Proof — "Trusted by Contractors Nationwide"

**Borrows from:** Toyota's embedded awards + Rivian's dark-section treatment

**Content:**
- 2-3 customer testimonials with name, company, location
- "X machines deployed" or "X years in the field" stat
- Customer ROI callout: "Contractor X paid off their SSQ3 in Y months"
- Link to full case study / ROI article

**Design:**
- Dark background section (visual rhythm break)
- Large quote marks, customer photo if available
- Stats as oversized numbers
- Single-row horizontal layout on desktop

---

### Section 8: Machine Comparison — "Which Machine Is Right for You?"

**Borrows from:** Rivian's "Find the R1T that's right for you" side-by-side table

**Content:**
- Side-by-side comparison of related machines (e.g., SSQ3 vs. SSH vs. SSR Jr.)
- Key differentiators: number of profiles, max speed, weight, price range
- "Best for" label on each (e.g., "Best for high-volume commercial" / "Best for residential startups")
- CTA per machine: "Explore" / "Build & Quote"

**Design:**
- Clean comparison table or card grid
- Highlight the current machine with a "You're viewing" badge
- Only compare machines in the same category (roof panel machines, not gutter machines)
- Responsive: cards stack on mobile, table scrolls horizontally on tablet

---

### Section 9: Accessories & Equipment — "Complete Your Setup"

**Borrows from:** Toyota's curated options approach

**Content:**
- Curated "Most Popular" accessories (trailer, reel stand, UNIQ control system)
- Organized by purpose: Coil Handling / Control / Processing / Transport
- Each with image, short description, price
- "Add to Quote" functionality

**Design:**
- Horizontal scrolling cards or compact grid
- Category tabs
- Prices visible

---

### Section 10: Specifications Accordion — "Full Technical Details"

**Purpose:** Keep the comprehensive spec data that serious buyers need, but don't lead with it. This is where all the existing WooCommerce product content lives — reorganized but fully preserved for SEO.

**Content:**
- Collapsible accordion sections:
  - Machine Dimensions
  - Performance Specs
  - Materials Formed
  - Coil Specifications
  - Power Options
  - Warranty & Patents

**Design:**
- Accordion/collapsible — default closed, but crawlable (content in DOM, hidden via CSS)
- Clean table formatting inside each section
- "Download Full Spec Sheet" CTA at the top
- All text is real HTML (not images) — preserves SEO indexability

**SEO note:** This section is critical. The current page ranks because of its comprehensive spec content. By keeping all specs in the accordion (visible in DOM, just collapsed), search engines still index everything. Structured data (`Product` schema) should also be maintained.

---

### Section 11: Resources & Support

**Content:**
- Machine manual download
- Product brochure download
- Video library link
- Service & training link
- Leasing & financing info

**Design:**
- Clean icon + label grid
- Grouped: Documentation / Video / Support

---

### Section 12: Final CTA — "Ready to Get Started?"

**Borrows from:** Toyota's three-card bottom CTA + Rivian's sticky footer

**Content:**
- Three options presented as cards:
  1. **Build & Finance** — configure your machine + see payment options
  2. **Request a Quote** — talk to a specialist
  3. **See It In Action** — schedule a demo or watch video

**Design:**
- Full-width section with contrasting background
- Three equal cards with icon, headline, short copy, CTA button

---

### Section 13: Sticky CTA Bar

**Borrows from:** Rivian's persistent footer CTA

**Behavior:**
- Hidden initially
- Appears after user scrolls past the hero section
- Compact bar at bottom of viewport: machine name + price + "Build & Quote" button
- Disappears when the Final CTA section is in view (no doubling up)

**Implementation:**
- JS module: `StickyProductCTA.js`
- Uses `IntersectionObserver` on hero and final-CTA sections to toggle visibility
- CSS: fixed bottom, translucent dark background, minimal height (~60px)

---

## Part 5: Design Identity — "High-Tech Industrial"

### The NTM Brand Position

NTM sits at the intersection of **industrial manufacturing** and **high-tech innovation**. The page design should communicate both:

| Industrial Credibility | Tech Innovation |
|---|---|
| Blueprint/footprint section (engineering drawings) | Smooth scroll animations |
| Machine breakdown (subsystems like Toyota's drivetrain) | Interactive product rotator |
| Dark, serious color palette | Clean typography, generous whitespace |
| Spec tables with real data | Progressive disclosure (accordion, tabs) |
| Steel/metal texture accents | Video hero, animated stats |
| Monospace/technical fonts for specs | Modern sans-serif for headlines |

### Visual Language

- **Primary backgrounds:** White sections + dark (charcoal/near-black) sections alternating
- **Accent:** NTM brand blue/orange for CTAs and highlights
- **Typography:** Bold sans-serif headlines (existing theme fonts), monospace for spec values and blueprint annotations
- **Photography style:** Job-site contextual, not sterile studio. Machines in action, operators at work
- **Blueprint style:** Monoline SVG illustrations, light strokes on dark background, dimension callouts with leader lines
- **Spacing:** Rivian-generous. Let sections breathe. This is a $100K+ product — the page should feel premium, not cramped

---

## Part 6: Data Architecture

### Where machine page data lives

**Option A: WooCommerce custom fields (ACF or native meta)**
- Store hero image/video, stats, feature sections, testimonials as product meta
- Pro: Content editors can update via WooCommerce product editor
- Con: Complex field setup, harder to version control

**Option B: PHP data file** (recommended for v1, matching existing pattern)
- Create `app/inc/machine-product-data.php` following the `machines-data.php` pattern
- Hardcoded content arrays per machine slug
- Pro: Fast, version-controlled, matches existing theme architecture
- Con: Requires developer to update content

**Recommendation:** Start with **Option B** for v1 (matches existing `machines-data.php` pattern, fast to build). Migrate to **Option A** (ACF fields) in v2 when content editors need self-serve updates.

### Data structure per machine

```php
$machines['ssq3-multipro'] = [
    // Hero
    'hero_headline'  => 'Produce 16 Panel Profiles On-Site. One Machine.',
    'hero_subtitle'  => 'Our newest residential/commercial multi-profile roof and wall panel machine.',
    'hero_image'     => 'https://...',        // required
    'hero_video'     => 'https://...',        // optional, progressive enhancement
    'price_range'    => '$121K – $137K',

    // Stats bar (4 items)
    'stats' => [
        ['value' => '16',          'label' => 'Panel Profiles'],
        ['value' => '25 min',      'label' => 'Tooling Changeover'],
        ['value' => '75 ft/min',   'label' => 'Max Speed'],
        ['value' => '$2.25/sq ft', 'label' => 'Avg. Savings'],
    ],

    // Machine breakdown (subsystems)
    'breakdown' => [
        [
            'id'       => 'forming-system',
            'title'    => 'The Forming System',
            'headline' => 'Precision Forming, Panel After Panel',
            'copy'     => '...',
            'specs'    => ['16 polyurethane drive rollers', '...'],
            'image'    => 'https://...',
        ],
        // ... power-pack, frame, brain
    ],

    // Blueprint
    'blueprint_svg'  => 'machine-only',  // references an SVG in assets/
    'blueprint_data' => [
        'length'        => "14'4\" (4.4m)",
        'width'         => "5'2\" (1.57m)",
        'height'        => "4'3\" (1.3m)",
        'weight'        => '2,830 lbs',
        'trailer_length'=> "18'11\" (5.8m)",
        // ...
    ],

    // Rotator images (for future photo-sequence rotator)
    'rotator_images' => [],  // array of image URLs, empty until photo shoot
    'gallery_images' => [],  // multi-angle static gallery (v1)

    // Profiles
    'profiles' => [ /* existing profile data */ ],

    // Social proof
    'testimonials' => [
        ['quote' => '...', 'name' => '...', 'company' => '...', 'location' => '...'],
    ],

    // Comparison (sibling machine slugs)
    'compare_with' => ['ssh-multipro', 'ssr-multipro-jr'],
    'best_for'     => 'High-volume commercial & residential',

    // Accessories (WooCommerce product IDs or slugs)
    'featured_accessories' => ['unq-ssq3-a', 'dr1-ssq3', 'tr12-d', 'fsd1'],
];
```

---

## Part 7: Implementation Phases

### Phase 1: Foundation (Template + Hero + Stats + Breakdown)
- Create `app/inc/machine-product-template.php` (template_include filter)
- Create `app/inc/machine-product-data.php` (data layer)
- Create `app/templates/pages/product/single-machine.php` (master template)
- Add include in `app/functions.php`
- Build hero section (image, with video-ready markup)
- Build stats bar
- Build machine breakdown sections (4 subsystems)
- Sticky CTA bar (JS module)

### Phase 2: Blueprint + Specs + Profiles
- Create blueprint SVG from existing footprint PDF
- Build blueprint section (dark bg, SVG, dimension callouts)
- Build specs accordion (migrating all existing spec content)
- Build profile selector (tabs + grid)

### Phase 3: Social Proof + Comparison + CTA
- Build social proof section
- Build machine comparison section
- Build accessories section
- Build final CTA section
- Build resources section

### Phase 4: Interactive Enhancements
- Multi-angle gallery (v1 rotator — static images, thumbnail switcher)
- Photo sequence rotator (v2 — when photography available)
- Blueprint toggle (machine-only vs. on-trailer views)
- Scroll animations (entrance reveals)

### Phase 5: SEO & Content Migration
- Verify all existing page content is present in new template
- Add/maintain Product structured data (JSON-LD)
- Test with Google Search Console URL inspection
- Monitor rankings for 2 weeks post-launch

---

## Next Steps

1. **Review this plan** — confirm direction, flag any sections to add/remove/modify
2. **Audit existing media** — what photography/video does NTM already have per machine?
3. **Obtain footprint PDF** — to trace as blueprint SVG
4. **Begin Phase 1** — template foundation
