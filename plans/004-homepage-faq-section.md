# Plan 004: Add a homepage FAQ section with FAQPage JSON-LD

> **Executor instructions**: Follow this plan step by step. Run every
> verification command and confirm the expected result before moving to the
> next step. If anything in the "STOP conditions" section occurs, stop and
> report — do not improvise. When done, update the status row for this plan
> in `plans/README.md` — unless a reviewer dispatched you and told you they
> maintain the index.
>
> **Drift check (run first)**:
> `git diff --stat c5d139b..HEAD -- app/front-page.php app/templates/parts/front-page/ app/inc/machine-schema.php`
> If `front-page.php`'s section order or the referenced partials changed since
> this plan was written, compare the "Current state" excerpts against the live
> code before proceeding; on a mismatch, treat it as a STOP condition.

## Status

- **Priority**: P2
- **Effort**: M
- **Risk**: MED (new user-visible section — copy and design must match the page)
- **Depends on**: none (001 is unrelated; execute in any order)
- **Category**: direction (AEO / content)
- **Planned at**: commit `c5d139b`, 2026-07-21

## Why this matters

The homepage is fact-dense (prices, speeds, lead times) but contains zero
question-formatted content, and answer engines (ChatGPT, Perplexity, Google AI
Overviews) match conversational queries against structured Q&A. A short FAQ
block turns facts already on the page into directly extractable answers, with
`FAQPage` JSON-LD as the machine-readable layer. Honest expectations: Google
has restricted FAQ *rich results* in SERPs to government/health sites, so the
payoff here is LLM/answer-engine citability and on-page buyer reassurance —
not SERP stars. That is still worth an M effort: "how much does it cost / how
long does it take" are the two questions every buyer asks.

## Current state

- `app/front-page.php` composes the page from section partials in job-state
  order (CAPTURE → ROUTE → SELL → EDUCATE → CLOSE). The EDUCATE block
  (lines 75–78) and CLOSE block (lines 80–82):

  ```php
  <?php // EDUCATE ?>
  <?php get_template_part('templates/parts/front-page/social-proof'); ?>
  <?php get_template_part('templates/parts/learning-center'); ?>
  <?php get_template_part('templates/parts/front-page/tools'); ?>

  <?php // CLOSE ?>
  <?php get_template_part('templates/parts/front-page/three-step-plan'); ?>
  <?php get_template_part('templates/parts/front-page/final-cta'); ?>
  ```

  The FAQ belongs at the end of EDUCATE, after the `tools` partial and before
  the CLOSE comment.

- Section-partial conventions — model the new file on
  `app/templates/parts/front-page/three-step-plan.php`: file-header docblock
  explaining the section's job, `declare(strict_types=1)`, ABSPATH guard, a
  `$content` array of translatable strings at the top, then markup. Sections
  use the `.section` vertical rhythm (`py-16/20/24`); backgrounds alternate
  between adjacent homepage sections (recent commit "Alternate homepage
  section backgrounds") — inspect the `tools` and `three-step-plan` partials'
  root `<section>` classes and choose the background that continues the
  alternation between them. Headings: one `<h2>` with a `section-title`-style
  class and an `id` referenced by `aria-labelledby` on the `<section>` (see
  `explore-machines.php:61` for the pattern).

- JSON-LD conventions — `app/inc/machine-schema.php` is the exemplar. It
  builds a `FAQPage` node like this (lines 166–189) and echoes it with
  `wp_json_encode($schema, SCHEMA_JSON_FLAGS)` where
  `SCHEMA_JSON_FLAGS = JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP |
  JSON_HEX_APOS | JSON_HEX_QUOT`:

  ```php
  return [
      '@context'   => 'https://schema.org',
      '@type'      => 'FAQPage',
      'mainEntity' => $entities,   // Question nodes with acceptedAnswer.text
  ];
  ```

  Note: machine product pages already emit their own per-page `FAQPage` — a
  homepage `FAQPage` does not conflict (schema is per-URL), but there must be
  only ONE `FAQPage` node on the homepage itself.

- Factual constraints the copy MUST honor (decided product/pricing policy):
  - SSQ3 pricing leads with the **$85K base price** and a "trailer sold
    separately" qualifier — never a trailer-inclusive figure.
  - Lead time is **6 to 10 weeks**, with on-site training in week one
    (already stated in the three-step-plan copy — reuse that phrasing).
  - Pull all prices from the theme's own data (the homepage cards render:
    MACH II 5" gutter machine "$9,800+", SSR MultiPro Jr. "$44,900+", WAV
    "$237,300+"). Verify each figure against `app/data/machines/` before
    writing it into copy — do not invent or import numbers from anywhere else.

## Commands you will need

| Purpose | Command | Expected on success |
|---|---|---|
| PHP syntax | `php -l <file>` | `No syntax errors` |
| Lint | `npm run lint:php` | exit 0 |
| Build (Tailwind picks up new classes) | `npm run build` | exit 0 |
| Template tests | `npm run test:page-templates` | exit 0 |

**Rendered-page caveat**: DevKinsta serves the main checkout, not worktrees.
From a worktree, verify statically and defer rendered/schema checks to
post-merge QA in the served checkout.

## Scope

**In scope**:
- `app/templates/parts/front-page/faq.php` (create)
- `app/front-page.php` (one `get_template_part` line added in EDUCATE)

**Out of scope** (do NOT touch):
- `app/inc/machine-schema.php` — reference it; don't modify it.
- The existing `/faq/` WordPress page and its content.
- `app/inc/seo.php` and any Yoast/DB configuration.
- Every other front-page partial.

## Git workflow

- Branch off `dev`: `joelgrodriguez/004-homepage-faq-section`
- One commit; short imperative subject, e.g. "Add homepage FAQ section with
  FAQPage schema".
- Do NOT push or merge unless the operator instructed it.

## Steps

### Step 1: Verify the price facts

Open `app/data/machines/` (files like `mach-ii-5-gutter-machine.php`,
`ssr-multipro-jr.php`, `wav.php`, `ssq3-multipro.php` — exact names may
differ; list the directory) and confirm the starting prices used in Step 2's
copy. If a figure differs from the copy below, use the data file's figure.

**Verify**: each price in the FAQ copy traces to a specific data file (note
file + key in your report).

### Step 2: Create `app/templates/parts/front-page/faq.php`

Single source of truth: define the Q&A array once, render both the HTML and
the JSON-LD from it. Target structure:

```php
$faqs = [
    [
        'question' => __('What is a portable rollforming machine?', 'standard'),
        'answer'   => __('A portable rollforming machine forms metal roof panels or seamless gutters from raw coil right on the jobsite. Because the panel is made on-site, it can run any length the roof needs — no factory length limits, no mid-panel seams, and no waiting on a supplier.', 'standard'),
    ],
    [
        'question' => __('How much does a portable rollforming machine cost?', 'standard'),
        'answer'   => __('NTM seamless gutter machines start at $9,800. Roof panel machines range from $44,900 for the SSR MultiPro Jr. to $237,300+ for the commercial WAV wall panel machine. The flagship SSQ3 MultiPro starts at $85K, trailer sold separately.', 'standard'),
    ],
    [
        'question' => __('How long does it take to get an NTM machine?', 'standard'),
        'answer'   => __('Lead time is 6 to 10 weeks from order. Financing is applied for in the same flow, and your crew runs panels with our team on-site during week one.', 'standard'),
    ],
    [
        'question' => __('Which NTM machine is right for my business?', 'standard'),
        'answer'   => __('It depends on what you sell: K-style gutters point to the MACH II line, standing seam roofing to the SSR, SSH, or SSQ3 MultiPro. Take the 10-question machine quiz or talk to a specialist to match a machine to your jobs.', 'standard'),
    ],
];
```

(Adjust figures per Step 1. Keep answers to 2–3 sentences, plain declarative
voice, each self-contained — an LLM should be able to quote one answer alone.)

HTML: a `<section>` following the conventions in "Current state" — one `<h2>`
(e.g. "Rollforming questions, answered."), then the Q&As. Questions as `<h3>`,
answers as `<p>` — statically rendered and visible; do NOT hide answers behind
JS-only disclosure (if you use `<details>`, the content is still in the HTML —
acceptable; invisible-until-JS is not). Include one text link to the full
`/faq/` page. Match neighboring sections' spacing/typography classes; when in
doubt copy class recipes from `three-step-plan.php`.

JSON-LD: at the end of the partial, build the `FAQPage` node from the same
`$faqs` array (pattern from `machine-schema.php:166-189`, answers through
`wp_strip_all_tags`) and echo it in a `<script type="application/ld+json">`
tag via `wp_json_encode($schema, \Standard\MachineSchema\SCHEMA_JSON_FLAGS)`.
A JSON-LD script in the body is valid.

**Verify**: `php -l app/templates/parts/front-page/faq.php` → clean.

### Step 3: Wire it into `app/front-page.php`

Add after the `tools` line, before the `// CLOSE` comment:

```php
<?php get_template_part('templates/parts/front-page/faq'); ?>
```

Also add `faq` to the EDUCATE list in the file-header composition comment
(the docblock at the top of `front-page.php` documents every section — keep
it truthful).

**Verify**: `grep -n "front-page/faq" app/front-page.php` → exactly one match,
positioned between `tools` and `three-step-plan`.

### Step 4: Lint, build, tests

**Verify**: `npm run lint:php`, `npm run build`, `npm run test:page-templates`
→ all exit 0.

### Step 5 (served checkout only): Rendered + schema check

```bash
curl -sk https://newtech.local/ | grep -c '"@type":"FAQPage"'        # expect 1
curl -sk https://newtech.local/ | grep -c 'How much does a portable' # expect 2 (HTML + JSON-LD)
```

Paste the page URL into https://validator.schema.org/ (or extract the JSON-LD
block and validate it) → FAQPage parses with 4 Question nodes, zero errors.
From a worktree, defer this step to post-merge QA and say so in your report.

## Test plan

- `npm run test:page-templates` must stay green (it exercises template
  loading).
- The schema validation in Step 5 is the correctness test for the JSON-LD.
- No new unit tests: the partial is declarative markup + one array-to-schema
  transform copied from an existing, tested pattern.

## Done criteria

- [ ] `app/templates/parts/front-page/faq.php` exists; HTML + JSON-LD built from one `$faqs` array
- [ ] Every price/lead-time figure verified against `app/data/machines/` (traceability noted in report)
- [ ] SSQ3 answer says $85K base + trailer sold separately
- [ ] Exactly one `FAQPage` node renders on the homepage (post-merge check if in worktree)
- [ ] `front-page.php` docblock updated; partial wired between `tools` and CLOSE
- [ ] `npm run lint:php`, `npm run build`, `npm run test:page-templates` exit 0
- [ ] No files outside the in-scope list modified (`git status`)
- [ ] `plans/README.md` status row updated

## STOP conditions

Stop and report back (do not improvise) if:

- A price in `app/data/machines/` differs by more than rounding from the copy
  above (pricing is policy — flag it, don't pick one).
- The homepage already renders a `FAQPage` node from another source (check
  with the Step 5 grep before adding yours — Schema Pro cleanup scripts 050+
  should have removed the old one, but verify).
- `front-page.php`'s section order no longer matches the excerpt.
- You feel the need to restyle neighboring sections to make the FAQ fit —
  that's a design change beyond this plan.

## Maintenance notes

- When prices change in `app/data/machines/`, this FAQ's cost answer must be
  updated by hand — it is intentionally static copy. Reviewer: consider a
  follow-up to derive the price range programmatically from the data files
  (deferred here to keep the section simple).
- If a site-wide FAQ strategy lands later (e.g. per-category FAQs), the
  homepage block should stay at 4–6 questions max — it's a sampler, not the
  FAQ page.
- Reviewer should scrutinize: answer copy makes no certification/safety
  claims (CE/NATM claims elsewhere are a known legal exposure — do not add
  new ones), and the schema answers match the visible text.
