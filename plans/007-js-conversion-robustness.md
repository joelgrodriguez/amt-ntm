# Plan 007: Conversion-path robustness — HubSpot retry, latch reset, URL guards

> **Executor instructions**: Follow this plan step by step. Run every
> verification command and confirm the expected result before moving to the
> next step. If anything in the "STOP conditions" section occurs, stop and
> report — do not improvise. Your reviewer maintains `plans/README.md` — do
> not edit it.
>
> **Drift check (run first)**: `git diff --stat 91b252b..HEAD -- app/resources/js/modules/HubspotForms.js app/resources/js/modules/ContactLazy.js app/resources/js/modules/SearchModal.js app/inc/pdf-attachment.php`
> On any change since this SHA, compare the excerpts below; on mismatch, STOP.

## Status

- **Priority**: P2
- **Effort**: S
- **Risk**: LOW
- **Depends on**: none
- **Category**: bug
- **Planned at**: commit `91b252b`, 2026-07-10

## Why this matters

The site exists to convert shoppers into conversations — HubSpot forms ARE the conversion path. Today a single network blip while a form mounts shows "Form unavailable" permanently for that page view, with the retry mechanism half-built but unreachable. Three smaller defensive gaps ride along: a module-level latch that never resets, an anchor `href` assigned from a REST response without scheme validation, and a PDF URL scraped from post content returned unvalidated.

## Current state

- `app/resources/js/modules/HubspotForms.js` — `mountForm(target)` is async; on failure it sets `target.dataset.hubspotLoaded = 'false'` (line ~73) intending a retry, but the IntersectionObserver callback (lines ~92-104) unobserves immediately after the first intersection, so no retry can ever fire:

```js
const observer = new IntersectionObserver(
  (entries) => {
    entries.forEach((entry) => {
      if (!entry.isIntersecting) {
        return;
      }

      mountForm(entry.target);
      observer.unobserve(entry.target);
    });
  },
  { rootMargin: '500px 0px' }
);
```

and in `mountForm`:

```js
  target.dataset.hubspotLoaded = 'true';
  clearPlaceholder(target);

  try {
    const hubspot = await loadHubspotScript();
    hubspot?.forms?.create({ region, portalId, formId, target: `#${target.id}` });
  } catch (_error) {
    target.dataset.hubspotLoaded = 'false';
    target.innerHTML = '<p class="text-sm text-blue-600">Form unavailable. Call New Tech Machinery directly.</p>';
  }
}
```

- `app/resources/js/modules/ContactLazy.js` — module-level `let hubspotLoaded = false;` (line 24) latched true in `loadHubspot()` (lines 26-28); `cleanup()` (lines 73-78) aborts the controller but never resets the latch, so any re-init (HMR, future re-mount) silently never loads the contact form. The embedded script also attaches no `error` listener.

- `app/resources/js/modules/SearchModal.js:308` — `link.href = item.url;` where `item.url` comes from the WP REST search response. Titles nearby are handled safely via `textContent`; the URL is assigned raw.

- `app/inc/pdf-attachment.php:45-47` — third fallback branch returns a regex capture from post content verbatim:

```php
    if (preg_match('/url=([^\s\]"]+\.pdf)/i', $content, $m) === 1) {
        return $m[1];
    }
```

(The two branches above it return `wp_get_attachment_url()` output, which is inherently valid. Render sites currently `esc_url()` the value — this fix makes the function's contract safe regardless of future consumers.)

- Conventions: vanilla ES modules, early-return guards, no frameworks. `HubspotForms.js` uses `data-*` state; `ContactLazy.js` uses module state + AbortController. PHP: `declare(strict_types=1)`, WordPress escaping helpers.

## Commands you will need

| Purpose | Command | Expected on success |
|---|---|---|
| PHP lint | `php -l app/inc/pdf-attachment.php` | `No syntax errors detected` |
| JS syntax | `node --check app/resources/js/modules/HubspotForms.js` (repeat per JS file) | exit 0, no output |

(Worktree has no `node_modules`; `node --check` needs none. Do not run npm.)

## Scope

**In scope**:
- `app/resources/js/modules/HubspotForms.js`
- `app/resources/js/modules/ContactLazy.js`
- `app/resources/js/modules/SearchModal.js` (the `link.href` assignment only)
- `app/inc/pdf-attachment.php` (the regex branch only)

**Out of scope** (do NOT touch):
- `app/single-profile.php`, `app/single-footprint.php`, `spec-sheet-layout.php` — render-site escaping stays as-is.
- Any other SearchModal behavior (focus trap, aria wiring — verified good).
- The HubSpot portal/form IDs in ContactLazy.js — they are public embed identifiers, not secrets, and not yours to change.

## Git workflow

- Branch: `advisor/007-js-conversion-robustness`
- One commit, subject like: `Harden conversion paths: HubSpot retry, latch reset, URL guards`
- Do NOT push.

## Steps

### Step 1: Make failed HubSpot mounts retryable

In `HubspotForms.js`:
1. Change `mountForm` to return a success boolean: `return true;` after the `create(...)` call; in the `catch`, keep the fallback message and `return false;`.
2. In the observer callback, only unobserve on success:

```js
entries.forEach(async (entry) => {
  if (!entry.isIntersecting) {
    return;
  }
  const mounted = await mountForm(entry.target);
  if (mounted) {
    observer.unobserve(entry.target);
  }
});
```

3. Guard `mountForm` so the `hubspotLoaded === 'true'` dataset check (already present near the top of the function — read it) prevents double-mount while a mount is in flight, and ensure the early-return path for missing `formId/portalId/target.id` ALSO returns `false` after unobserving is impossible there — for that path, return `true` instead so the observer unobserves the misconfigured element (retrying a missing ID is pointless). Add a one-line comment explaining that choice.

**Verify**: `node --check app/resources/js/modules/HubspotForms.js` → exit 0. `grep -n 'return true\|return false' app/resources/js/modules/HubspotForms.js` → success/failure paths both present.

### Step 2: Reset the ContactLazy latch and handle script error

In `ContactLazy.js`:
1. In `cleanup()`, add `hubspotLoaded = false;`.
2. In `loadHubspot()`, add an error listener after the load listener:

```js
script.addEventListener('error', () => {
  hubspotLoaded = false;
});
```

**Verify**: `node --check app/resources/js/modules/ContactLazy.js` → exit 0. `grep -c 'hubspotLoaded = false' app/resources/js/modules/ContactLazy.js` → `2` (initializer aside — expect the declaration line plus two assignments: total `grep -c 'hubspotLoaded = false'` = 3 including `let hubspotLoaded = false;`).

### Step 3: Scheme-guard the search result links

In `SearchModal.js`, replace `link.href = item.url;` with:

```js
let safeHref = '';
try {
  const parsed = new URL(item.url, window.location.origin);
  if (parsed.protocol === 'http:' || parsed.protocol === 'https:') {
    safeHref = parsed.href;
  }
} catch {
  // unparseable URL from the REST response — leave the item unlinked
}
if (!safeHref) {
  return; // skip rendering this result
}
link.href = safeHref;
```

(The surrounding `items.forEach((item, index) => { ... })` structure makes `return` skip just this item. Keep the `link.id`/index wiring after the guard so skipped items don't consume an id — actually the id uses the forEach `index`, which is fine to leave sparse; do not renumber.)

**Verify**: `node --check app/resources/js/modules/SearchModal.js` → exit 0. `grep -n 'link.href = item.url' app/resources/js/modules/SearchModal.js` → no matches.

### Step 4: Validate the scraped PDF URL

In `pdf-attachment.php`, change the third branch to:

```php
    if (preg_match('/url=([^\s\]"]+\.pdf)/i', $content, $m) === 1) {
        $url = esc_url_raw($m[1]);
        return $url !== '' ? $url : null;
    }
```

**Verify**: `php -l app/inc/pdf-attachment.php` → clean. `grep -n 'esc_url_raw' app/inc/pdf-attachment.php` → one match.

## Test plan

No JS test harness exists. Gates: `node --check` on all three modules + `php -l`. Reviewer post-merge: build, then on the served checkout load the front-page contact section and a service-request page with DevTools network throttling — verify the form mounts, and that blocking `js.hsforms.net` then scrolling away/back triggers a remount attempt.

## Done criteria

- [ ] All three JS files pass `node --check`; PHP file passes `php -l`
- [ ] Failed HubSpot mounts remain observed (retry path reachable)
- [ ] `cleanup()` in ContactLazy resets the latch
- [ ] No raw `link.href = item.url` remains in SearchModal.js
- [ ] `esc_url_raw` guards the pdf regex branch
- [ ] `git status --porcelain` shows only the four in-scope files

## STOP conditions

- `mountForm`'s current structure differs materially from the excerpt (e.g. it already returns a value, or the dataset guard is absent).
- The SearchModal render loop is not a `forEach` where `return` skips one item.

## Maintenance notes

- If HubSpot embed strategy changes (e.g. consolidating ContactLazy into HubspotForms — they duplicate loader logic), the retry semantics built here are the pattern to keep. That consolidation is a reasonable future cleanup; out of scope today.
- Reviewer scrutiny point: the async observer callback — ensure no unhandled rejection path (mountForm catches internally, so `await` is safe).
