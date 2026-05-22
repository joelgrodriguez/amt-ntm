# Image Inventory (May 2026 Drop)

158 new images uploaded to the media library under `/wp-content/uploads/2026/05/`. Source folder: `~/standard/mettech/ntm/projects/theme/NTM Theme 2026/_renamed/jpg/`. Filenames follow `ntm-{subject}-{NNN}.jpg`.

This doc captures **what's in each group** and **where each group plugs in**. Placements are recommendations — confirm with the user before swapping any CDN URL.

## Quick wins (do these first)

These are the high-leverage swaps. Each one updates multiple surfaces.

| Current URL | Replace with | Surfaces affected |
|---|---|---|
| `2025/04/Nate-training-East-Kentucky-Metal-9-scaled.jpg` (placeholder reused everywhere) | `ntm-customer-onsite-002.jpg` or `ntm-mach2-gutter-install-abel-001.jpg` | front-page why-own, about manifesto, all 3 customer-story sections (roof-wall, gutter, machines) |
| `2025/09/Machine-on-rooftop-scaled.jpg` (hero poster) | `ntm-standing-seam-roof-007.jpg` (lakefront residential) or `ntm-customer-onsite-001.jpg` (overhead drone of trailer + crew) | hero poster across machines, roof-wall, gutter pages |
| SSQ3 hero `2026/01/Screenshot-2026-01-07-at-9.37.43-AM.png` | `ntm-ssq3-product-render-001.jpg` (clean white-bg SSQ3) | front-page hero slider + SSQ3 single-machine page |
| MACH II Combo `2024/07/...CS-Rain-Gutters...Still002.jpg` | `ntm-mach2-gutter-install-abel-002.jpg` (Abel's crew installing gutter) | front-page flagships + MACH II Combo single page |

---

## Groups

### `ntm-customer-onsite-*` (2 images)
**What it is:** Customer field shots.
- **001** — Overhead drone of a portable rollformer on its trailer; two figures walking up. Wide cinematic 16:9. Landscape.
- **002** — Customer portrait beside his machine + a finished panel, residential neighborhood backdrop. Landscape.

**Where to use:**
- **001 → front-page hero slider** (replacement for one of the existing slide bg images). Drone POV reads "the machine arrives on your job." Cinematic enough for the hero register.
- **001 → front-page why-own header image** (replaces Nate-training placeholder). 16:9, action, real customer.
- **002 → roof-wall customer-story** (image-left), and a stronger candidate than the current Nate-training shot for any "real customer with their machine" placement.

---

### `ntm-mach2-gutter-install-abel-*` (3 images)
**What it is:** Abel Cisneros' crew (C&S Rain Gutters — already on the testimonial slider) installing a finished gutter on a brick house. Crew on ladders, gutter mid-install. Real install action.

**Where to use:**
- **001 → /seamless-gutter-machines/ hero poster** or value-prop break image. This is *the* gutter-install money shot.
- **002 or 003 → gutter customer-story section.** Currently a placeholder ("Real Story Coming Soon"). Even without a real quote pinned yet, Abel's already in the testimonial slider — pull his quote here too and finally have aligned imagery.
- **001 → MACH II Combo flagship card on front page** (`hero_image` key in `data/machines/mach-ii-combo-gutter.php`).

---

### `ntm-mach2-gutter-assembly-drone-*` (2 images) ⚠️ mislabeled
**What it actually is:** Drone shots of the **NTM assembly/production floor** (forklift, machine frames being built, the whole shop). Not gutter-related despite the filename.

**Where to use:**
- **002 or 004 → About page** as a manifesto sidekick or full-bleed break between origin and timeline. "Built here since 1991" needs a "here" — this is "here."
- Optional full-bleed image-break section anywhere the "we build it ourselves" story matters.

> 📝 Rename suggestion: `ntm-assembly-floor-drone-002.jpg` etc. — current filename misleads.

---

### `ntm-mach2-electric-power-pack-*` (9 images)
**What it is:** Studio product shots of the Quick-Change electric power pack — multiple angles, on-machine and off-machine, close detail (control cables, hydraulic motor, hydraulic pump).
- **001** — clean side-profile of the electric power pack with hydraulic lines, NTM-branded crate in background. Hero-ready.
- **022** — head-on shot showing "Control Cable 1/2/Lighting Connection" panel + hydraulic pump. Detail/spec shot.
- Most others are alternate angles.

**Where to use:**
- **001 → SSQ3 single-machine page, "The Power Pack" breakdown card** (`image` field in `data/machines/ssq3-multipro.php` → `breakdown[power-pack]`).
- **022 → SSQ3 spec/diagnostics deep-dive** or service-hub page.
- A 3-shot gallery here would slot into the SSQ3 gallery section (`gallery.images` is currently empty).

---

### `ntm-ssq2-frame-overhead-*` (3 images)
**What it is:**
- **002 / 003** — Top-down studio shots of the **SSQ frame internals** (rollers + chassis, lit on warehouse floor). Engineering-porn macro of the machine's skeleton.
- **006** — **Full assembled SSQ II on trailer**, studio backdrop, NTM branding visible. Catalog-grade product shot.

**Where to use:**
- **006 → SSQ II MultiPro single-machine page hero** (replaces the existing CDN image).
- **006 → front-page hero slider** (SSQ II slide; current `hero_image` is in `data/machines/ssq-ii-multipro.php`).
- **002 / 003 → SSQ II "The Frame" breakdown card** OR the machines page `image-break.php` (currently using the old `ssqii-updated.png`).

---

### `ntm-ssq3-overhead-drone-*` (8 images)
**What it is:** Top-down studio shots of the **SSQ3 frame internals** — exposed rollers, drive chains, panel-forming guts. Same composition language as `ssq2-frame-overhead` but for the new SSQ3.
- **001** — Full-machine overhead, complete chassis.
- **005, 006** — Tighter framing showing drive system.

**Where to use:**
- **001 → SSQ3 page hero** or large gallery anchor.
- **005 → SSQ3 "The Forming System" breakdown card.**
- One of these would also be a striking machines-page `image-break.php` replacement.

---

### `ntm-ssq3-product-render-001.jpg` (1 image)
**What it is:** Clean studio shot of the assembled SSQ3 on its blue trailer, slightly off-axis, white background. Wheels, control panel, branding all visible. Catalog-grade.

**Where to use:**
- **SSQ3 hero image** — `data/machines/ssq3-multipro.php` → `hero.hero_image`. Current value points at a Screenshot from January; this is the proper marketing shot. **Highest-impact single swap on the SSQ3 page.**
- Flagship card on the front page (pairs with the `flagships.php` section's `image_key => 'image'`).

---

### `ntm-ssq3-quikswap-tooling-*` (20 images)
**What it is:** Tight macro shots of the QWIKSwap™ tooling system — close-ups of forming dies, rollers, the quick-change mechanism. Everything is engineered, precision metalwork, in-shop lighting.
- **001, 014** — Sharp depth-of-field hero macros, ready to drop into a feature section.
- Other variants are alternate angles of the same hardware.

**Where to use:**
- **SSQ3 "The Forming System" breakdown card** — this is the "25-minute tooling changeover" story made visible.
- **machines page `differentiators.php`** if there's a "QWIKSwap" cell.
- Gallery filler for the SSQ3 page (`gallery.images` currently empty).
- One could be the SSQ3 page hero secondary (`hero.image` vs `hero.hero_image`).

---

### `ntm-ssq3-rfid-sensors-*` (6 images)
**What it is:** Tight macro of the **RFID cover sensors** mounted on the SSQ3, with the profile-code chart (FF100/FF150/SS100/...) visible next to the sensor. The "smart machine" story made visible.

**Where to use:**
- **SSQ3 "The Brain" breakdown card** (`data/machines/ssq3-multipro.php` → `breakdown[brain]`). Current value `''`. This is the textbook image for "RFID cover sensors and on-controller diagnostics."
- Wherever the UNIQ vs manual story is told.

---

### `ntm-ssq3-manual-controller-*` (50+ images)
**What it is:** Two distinct sub-groups inside the filename family:
- **001–100** — Tight macros of the **SSQ3 control panel** — buttons (START FEED, SHEAR UP/DOWN, EMERGENCY STOP), the Siemens HMI touchscreen under its sun-hood, gauges. Studio + in-context.
- **107–132** — **Full assembled SSQ3 machine, studio backdrop, blue-trailer rigged**, multiple angles (3/4, side, head-on). Product catalog shots.

**Where to use:**
- **001 (controller close-up) → SSQ3 "The Brain" breakdown card** — alt option to the RFID shot.
- **001 → UNIQ spotlight section on machines page** (`uniq-spotlight.php`'s `image`, currently pointed at a 2021 training-overview PNG).
- **117 / 009 (full-machine) → SSQ3 hero or alt hero**, or front-page flagship card.
- Several alternates make a strong gallery sequence.

---

### `ntm-standing-seam-roof-*` (21 images)
**What it is:** Drone + ground-level shots of completed NTM-paneled roofs in the wild. Residential, commercial, agricultural — wide variety.
- **001** — Commercial / industrial entryway, black + white panels.
- **002** — Agricultural barn / hangar, black standing seam over white walls.
- **007** — **Lakefront luxury residence with black standing seam**. Cinematic. Hero-grade.
- **012** — Commercial restaurant ("Mullenix's"), drone POV, black standing seam.
- **016** — **Multi-unit townhomes, blue siding + black standing seam**. Residential repeat-pattern beauty shot.
- **021** — Overhead drone of a complex hipped residential roof. Pure pattern/texture, abstract.

**Where to use:**
- **007 → /roof-wall-panel-machines/ hero poster** — replaces the Machine-on-rooftop default.
- **016, 021 → image-break / texture break sections** on machines and front-page surfaces.
- Could also build a "finished work" gallery somewhere on the site (currently no surface exists — would be a small new feature).

---

### `ntm-team-customer-service-*` (4 images)
**What it is:** Editorial portraits of customer-service team members at their desks/headsets. Tight, professional. Vertical-leaning.

**Where to use:**
- **About page leadership/industry-standing section.**
- **Service hub page** (`page-service-hub.php`) header or "talk to our team" CTA.
- Final-CTA contact callouts.

---

### `ntm-team-engineer-*` (9 images)
**What it is:** NTM engineers working on machines — at workbenches, calibrating, measuring. Real work, real people, NTM-branded polos. Mix of horizontal and vertical.

**Where to use:**
- **About origin / timeline** ("built here since 1991" — show who builds it).
- **Roof-wall / gutter learning-center cards** where "engineering" surfaces.
- Service-hub page testimonials of NTM's own technical depth.

---

### `ntm-team-production-*` (7 images)
**What it is:** Production-floor team — assembling, inspecting, welding, working on machine frames. Tight on hands and faces, NTM-branded apparel.

**Where to use:**
- **About manifesto image column** (replaces the Nate-training placeholder).
- **About image-break section** (with assembly-floor drone shot as a pairing).
- Any "built here" surface.

---

### `ntm-team-service-*` (3 images)
**What it is:** Service techs working on assembled machines, leaning over the frames, focused. Similar register to engineer set but on finished machines instead of in fab.

**Where to use:**
- **Service hub page** (`page-service-hub.php`) hero or feature card.
- **Roof-wall / gutter FAQ sections** where service questions appear.

---

### `ntm-team-whole-team-outside-*` (1 image)
**What it is:** **The entire NTM Denver team in front of the 16265 facility**, blue shirts, dozens of people. The full-company group photo.

**Where to use:**
- **About page** as the dominant image somewhere — manifesto image column or a dedicated "the team" break. This is the single most authentic shot in the entire set for the About story.
- Footer or final-CTA image on about / contact.

---

### `ntm-uniq-controller-shear-warning-*` (2 images)
**What it is:** Beautifully-lit studio shot of the **UNIQ control panel** with all the named buttons (STOP FEED, SHEAR UP, EMERGENCY STOP) and the touchscreen. Vertical. Hero-grade.

**Where to use:**
- **machines page `uniq-spotlight.php`** — current `image` is an old 2021 training image; this is the *actual* UNIQ control panel they're describing.
- **`/technology/uniq/`** page (if it exists / when built).
- SSQ3 "The Brain" breakdown card alt.

---

## Surfaces ranked by improvement opportunity

1. **machines page `uniq-spotlight.php`** — currently a 2021 training PNG; swap to `ntm-uniq-controller-shear-warning-001`. One-line change, big visual lift.
2. **SSQ3 data file hero** (`data/machines/ssq3-multipro.php`) — `ntm-ssq3-product-render-001`. Updates front-page hero slider + SSQ3 single page in one shot.
3. **All three customer-story sections** (roof-wall, gutter, machines) — all using the same Nate-training placeholder. Swap to `ntm-customer-onsite-002` (or the Abel install shot for gutter).
4. **MACH II Combo data file hero** (`data/machines/mach-ii-combo-gutter.php`) — `ntm-mach2-gutter-install-abel-002`. Aligns the flagship card with the testimonial slider's Abel quote.
5. **/roof-wall-panel-machines/ hero poster** — `ntm-standing-seam-roof-007` instead of the generic Machine-on-rooftop.
6. **/seamless-gutter-machines/ hero poster** — `ntm-mach2-gutter-install-abel-001`.
7. **About manifesto + about leadership** — currently both using Nate-training. Pair them with `ntm-team-whole-team-outside-001` and `ntm-team-production-*` for a real "built here" story.
8. **SSQ3 breakdown cards** (forming-system / frame / power-pack / brain) — all four currently empty. Drop in `quikswap-tooling-001`, `overhead-drone-001`, `electric-power-pack-001`, `rfid-sensors-001` respectively.
9. **front-page why-own header image** — `ntm-customer-onsite-001` or `ntm-mach2-gutter-install-abel-001`.
10. **machines page `image-break.php`** — replace the old `ssqii-updated.png` with `ntm-ssq2-frame-overhead-006` or `ntm-standing-seam-roof-021`.

## Filename → CDN URL pattern

All images are accessible via:
```
/wp-content/uploads/2026/05/{filename}.jpg
```

E.g. `ntm-ssq3-product-render-001.jpg` → `https://newtechmachinery.com/wp-content/uploads/2026/05/ntm-ssq3-product-render-001.jpg` (prod) or `content_url('/uploads/2026/05/ntm-ssq3-product-render-001.jpg')` (theme helper).

WordPress has generated standard size variants (100, 150, 250, 300, 400, 600, 640, 768, 1024, 1536) for each, so `responsive_image()` will pick the right one.

## Open questions / followups

- Should we rename `mach2-gutter-assembly-drone-*` to `assembly-floor-drone-*` (or similar) to match what it actually depicts? Filenames are sticky once they're attachment slugs.
- The standing-seam-roof set has enough variety to support a "Built with NTM" customer gallery — worth scoping as a separate feature?
- Where do the `manual-controller-107…132` full-machine product shots belong relative to `ssq3-product-render-001`? Pick one canonical hero, push the others into a gallery.
