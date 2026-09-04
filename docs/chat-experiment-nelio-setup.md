# Corbel vs HubSpot Chat A/B Test (self-hosted)

The theme runs this experiment itself — no Nelio subscription required.
While the experiment is **stopped** (the default), the site behaves exactly
as before: everyone gets HubSpot chat, nothing is tracked.

## Code map

- `app/inc/chat-experiment.php` — experiment state, stats table, REST
  tracking endpoint, z-test math
- `app/inc/chat-experiment-dashboard.php` — **Tools → Chat A/B Test** admin
  page (start/stop, split control, results)
- `app/resources/js/modules/ChatExperiment.js` — sticky cookie assignment,
  goal capture, reporting to REST + GA4 + Clarity
- `app/resources/js/modules/ThirdPartyLoader.js` — loads exactly one chat
  vendor per visitor through the existing deferred gate
- `app/inc/performance.php` — publishes `chatExperiment` config in
  `window.ntmThirdPartyConfig`

## How it works

1. **Assignment.** On first visit, `ChatExperiment.js` rolls against the
   configured Corbel share (default 20%) and stores the result in a 90-day
   `ntmChatVariant` cookie (`SameSite=Lax`). The visitor sees the same chat
   on every page and return visit.
2. **Loading.** `ThirdPartyLoader.js` loads only the assigned vendor:
   Corbel's assistant iframe, or HubSpot's `js.hs-scripts.com/{portal}.js`
   loader. Both go through the same post-interaction deferred gate, so
   performance is identical across arms. Configurator / finance-center pages
   always get Corbel (functionally required) and are excluded from stats.
3. **Tracking.** Every exposure and goal is sent to three sinks:
   - the theme's REST endpoint (`/wp-json/ntm/v1/chat-experiment/track`) —
     aggregate daily counters, no PII, feeding the dashboard
   - GA4 via `dataLayer`: `chat_experiment_exposure` and
     `chat_experiment_goal` events with `chat_provider`
   - Microsoft Clarity: `chat_variant` session tag + `chat_*` events, so
     replays can be filtered by variant
4. **Dashboard.** Tools → Chat A/B Test shows per-goal conversion rates for
   both arms, lift, and a two-proportion z-test verdict (95% confidence),
   plus a 60-day daily breakdown.

## Goals

| # | Goal | Corbel source | HubSpot source |
|---|---|---|---|
| 0 | Conversation started | widget-open postMessage (proxy) | Conversations SDK `conversationStarted` |
| 1 | Question resolved | pending Corbel event / weekly report | dashboard-only, weekly report |
| 2 | Handoff to sales | pending Corbel event / weekly report | dashboard-only, weekly report |
| 3 | Lead generated | pending Corbel event / weekly report | Conversations SDK `contactAssociated` |

Corbel's assistant is a cross-origin iframe; today its loader only relays a
widget-open message. If Corbel adds richer postMessages, the theme already
handles `{ source: 'corbel-reception-assistant', type: 'goal', goal:
'questionResolved' | 'salesHandoff' | 'leadGenerated' }` with zero changes.
Any script can also report goals manually:

```js
window.dispatchEvent(new CustomEvent('ntm:chat-goal', {
  detail: { goal: 'salesHandoff' }
}));
```

Rows that a vendor cannot report in-browser get supplemented from their
weekly reports (compare rates, never raw counts — HubSpot has 4x traffic).

## Launch checklist

1. Confirm the HubSpot portal ID under **Settings → Standard Site
   Integrations** and that a chatflow is enabled/targeted in HubSpot →
   Conversations → Chatflows.
2. On staging: start the experiment from Tools → Chat A/B Test, then in a
   private window verify one (and only one) chat loads; delete the
   `ntmChatVariant` cookie and reload until you've seen both variants.
   To force a variant, set the cookie manually:
   `document.cookie = 'ntmChatVariant=hubspot; path=/'`.
3. Open a chat in each variant and confirm the counter appears on the
   dashboard and the GA4 DebugView events fire.
4. Visit a `/configurator/...` page — Corbel loads for everyone.
5. Stop the experiment — site returns to Corbel-only for all visitors.
6. Launch on production at 80/20 from the dashboard.

## Duration / decision rule

- At 20% on the Corbel arm, expect roughly 3–5 weeks; wait for ~100
  conversions per goal per side and always evaluate full weeks.
- The dashboard marks a row "Too early" under 50 combined conversions and
  only declares a winner at p < 0.05.
- Predefine the decision rule, e.g. ship Corbel site-wide if non-inferior
  on leads and better on resolution rate.

## Nelio compatibility

If Nelio A/B Testing is ever installed, its JavaScript-test variant snippets
(`window.ntmChatProvider = '...'; done();`) take precedence over the
cookie, and goals mirror to `nab.convert()` once an experiment ID is
registered via `add_filter('ntm_chat_experiment_id', fn() => ID)`.
