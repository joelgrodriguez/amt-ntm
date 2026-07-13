# Reporting Connectors — NTM + Sheffield

**Status:** spec / setup checklist — nothing built yet
**Owner:** Joel (access grants) + agents (build)
**Drives:** the weekly executive report ("no negative performance from the new site,
dips explained with rebound ETA, UX confusion vs clarity, traffic + leads vs benchmark")
**Written:** 2026-07-13

## Why new connectors

Verified 2026-07-13 from a live session:

- The existing GSC connector's service account sees **only `ar500armor.com`**.
- The existing GA4 connector returns 403 for anything relevant — AR-scoped.
- The connected HubSpot connector is portal **3958217**; this theme's forms post to
  portal **4478417** (`app/inc/hubspot.php`). Wrong portal — it cannot see NTM leads.

Everything Armored Republic-shaped stays untouched. NTM + Sheffield get their own stack.

## Decision: one MCP, not five

**Build ONE custom read-only remote MCP — working name `amt-reporting` — that wraps
GA4 + GSC + Clarity + Kinsta for both brands.** Every tool takes a `site` parameter
(`ntm` | `sheffield`); the server maps it to the right property ID / token internally.

HubSpot is the exception: prefer the **official HubSpot connector** pointed at the
right portal (richer than anything hand-rolled). Only if claude.ai won't hold a second
HubSpot connector instance (the slot is currently AR's portal) do we add a minimal
`hubspot_leads_summary` tool to `amt-reporting` using a private-app token.

Why one server instead of per-service connectors (the AR pattern):

| | One `amt-reporting` MCP | Per-service connectors (AR pattern) |
|---|---|---|
| Connector entries in claude.ai | 1 | 8–10 across two brands |
| Credential storage | one Worker secret vault | scattered per deploy |
| Tool shape | pre-shaped for the weekly report (cheap tokens) | generic API passthrough |
| Blast radius | one deploy to update | n deploys |

Tradeoff: one server is a single point of failure and mixes four upstream APIs in one
codebase. Acceptable — it's read-only reporting, not production infrastructure.

## Architecture

- **Host:** Cloudflare Worker on the **ArchMet** CF account (`a7f67f1877d7d017a245c2309c43f159`).
- **Stack:** TypeScript, Cloudflare `McpAgent` (agents SDK), streamable HTTP transport.
- **Secrets:** `wrangler secret put` — Google SA JSON key, Clarity token(s), Kinsta key,
  HubSpot private-app token(s). Nothing in the repo, ever.
- **Connector auth:** mirror whatever the AR connectors use (already solved once).
- **Clarity harvest (important):** Clarity's Data Export API serves only the **last 1–3
  days per call, max 10 calls/day per project, numbers only — no heatmap images**. A
  weekly-only pull would lose days 4–7. So the Worker gets a **daily Cron Trigger** that
  pulls Clarity into KV/D1; the `clarity_ux_signals` tool reads the accumulated history.
  No model, no tokens, deterministic. Heatmap *images* stay in Clarity — the report links
  to them and embeds the numbers (rage clicks, dead clicks, quick-backs).

## Part 1 — What Joel does (the clicking)

### 1. Google Cloud project + service account (GA4 + GSC)

1. [console.cloud.google.com](https://console.cloud.google.com) → **New project**:
   `amt-reporting`. Keep it separate from the AR project — client isolation.
2. **APIs & Services → Enable APIs:** enable
   - Google Analytics Data API
   - Google Search Console API
   - (optional) Google Analytics Admin API — lets tools discover properties
3. **IAM & Admin → Service Accounts → Create:** `amt-reporting-sa`. No GCP IAM roles
   needed — access is granted inside GA4/GSC, not in Cloud IAM.
4. Open the SA → **Keys → Add key → JSON.** Download once; it goes into Worker secrets,
   then delete the local copy.
5. Record the SA email (`amt-reporting-sa@amt-reporting.iam.gserviceaccount.com`) — it's
   what you grant everywhere below.

### 2. GA4 grants (per property)

For **NTM** and **Sheffield** GA4 properties:

1. GA4 → Admin → **Property access management** → add the SA email as **Viewer**.
2. Record each **Property ID** (Admin → Property settings — the numeric ID) in the
   table at the bottom.
3. **Check:** did the NTM relaunch keep the same GA4 property? If a new property was
   created at launch, also grant the *old* property — the pre-launch benchmark lives there.

### 3. Search Console grants (per property)

For `newtechmachinery.com` and `sheffieldmetals.com` properties (note whether each is a
domain property `sc-domain:` or URL-prefix):

1. Search Console → property → **Settings → Users and permissions → Add user** →
   SA email, permission **Full**. (Only an Owner can add users.)
2. Record the exact property identifier in the table below.

### 4. Microsoft Clarity token(s)

1. [clarity.microsoft.com](https://clarity.microsoft.com) → NTM project →
   **Settings → Data export → Generate new API token.** Name it `amt-reporting`.
2. Same for the Sheffield project **if Clarity is installed there** (verify — open
   question below).
3. Hand tokens to the Worker secrets; don't paste them into chat or the repo.

### 5. Kinsta API key

1. MyKinsta → your name → **Company settings → API keys → Create API key.**
2. Before we build anything here: **check whether Kinsta's official MCP server covers
   analytics** — if yes, use theirs and drop Kinsta from `amt-reporting` entirely.
3. Also verify which analytics the public API actually exposes (visits, bandwidth,
   cache hit ratio, response codes). If it's thin, this section of the report falls
   back to GA4 + CrUX and we skip the Kinsta tool. Sheffield may not even be on Kinsta —
   confirm before granting anything.

### 6. HubSpot

1. Confirm portal IDs: NTM = **4478417** (from the theme). Find Sheffield's (view
   source on sheffieldmetals.com forms, or ask whoever owns the portal).
2. **Option A (try first):** add the official HubSpot connector in claude.ai connected
   to the NTM portal. If claude.ai refuses a second HubSpot instance alongside AR's,
   go to B.
3. **Option B:** in each portal, **Settings → Integrations → Private Apps → Create**:
   name `amt-reporting`, read-only scopes only (`crm.objects.contacts.read`, `forms`).
   Token goes into Worker secrets; we add a `hubspot_leads_summary` tool.

### 7. Nothing needed

- **CrUX / PageSpeed Insights** (Core Web Vitals): free API, no grant. Included in the
  report at zero setup cost.
- **WordPress MCP:** skipped for v1 — GA4/GSC/HubSpot/Clarity cover everything the
  weekly report needs; WP adds nothing here.

## Part 2 — What agents build (after grants exist)

| Phase | Work | Output |
|---|---|---|
| 1 | Scaffold `amt-reporting` Worker: McpAgent, site→credential map, tools below, daily Clarity cron → KV/D1 | deployed Worker + claude.ai custom connector, smoke-tested per tool per site |
| 2 | Benchmark freeze: pull 6 months pre-launch monthlies + same-week-last-year (sessions, organic clicks, avg position, leads) | versioned `reporting/benchmark.json` in this repo |
| 3 | First report driven by hand, reviewed by Joel | validated manual run |
| 4 | Encode as `weekly-exec-report` skill: queries, green/yellow/red thresholds, rebound playbook (reindexing dip weeks 1–4, 301 equity settling 2–6 weeks, CTR shifts from new titles, YoY vs seasonality) | skill + Gmail **draft** to Joel — never auto-send to execs |
| 5 | Weekly schedule (Monday AM). Verify claude.ai connectors actually resolve in a headless scheduled run before trusting it; local fallback otherwise | running loop |

Each phase is its own Admiral task when we get there. This doc is the contract.

### Tool surface (read-only, report-shaped)

| Tool | Returns |
|---|---|
| `ga4_traffic_summary(site, range, compare_range)` | sessions, users, engaged sessions, conversions by channel, deltas |
| `ga4_top_pages(site, range)` | top landing pages + week-over-week movement |
| `gsc_search_summary(site, range, compare_range)` | clicks, impressions, CTR, avg position, deltas |
| `gsc_top_movers(site, range)` | biggest query/page gains and losses |
| `gsc_index_health(site)` | sitemap status + sampled URL inspections (404s, redirects) |
| `clarity_ux_signals(site, range)` | rage clicks, dead clicks, quick-backs, excessive scroll, JS errors, scroll depth by top URLs — from harvested KV/D1 history |
| `kinsta_infra_summary(site)` | uptime/cache/5xx — **only if** the API supports it and no official MCP exists |
| `hubspot_leads_summary(site, range)` | form submissions + new contacts by source — **Option B only** |

## Gotchas to remember at build time

- **GSC data lags ~2–3 days.** A Monday report covers the week ending the prior
  Thursday/Friday. Say so in the report footer instead of pretending it's real-time.
- **Clarity retention is short** and the API window is 1–3 days — the daily harvest
  cron is load-bearing, not optional. If it dies for a week, that week's UX data is gone.
- **Heatmap images can't be exported.** Numbers + links, per above. Set that expectation
  with leadership before the first report ships.
- **claude.ai connectors may be absent in headless scheduled runs.** Phase 5 verifies
  this explicitly before anyone relies on the Monday cadence.

## Fill in as you go

| Thing | NTM | Sheffield |
|---|---|---|
| GA4 property ID | | |
| Same GA4 property pre/post launch? | | n/a |
| GSC property (exact) | | |
| Clarity project + token created? | | installed at all? |
| Kinsta site (hosted there?) | yes | ? |
| HubSpot portal ID | 4478417 | |
| HubSpot access route (A official / B private app) | | |

## Open questions

1. Does Sheffield run Clarity? If not: install it now — heatmap questions about *that*
   site will come eventually, and there's no retroactive data.
2. Is Sheffield on Kinsta, and does anyone want infra data for it in this report?
3. Same GA4 property across the NTM relaunch, or a new one?
4. Does claude.ai allow two HubSpot connector instances (AR's + NTM's)?
5. Who are the report recipients, and is a Gmail draft to Joel the right delivery gate?
   (Current plan: yes — a human always presses send.)
