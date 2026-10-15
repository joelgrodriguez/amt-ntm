# Chat A/B experiment data snapshot — 2026-10-15

Corbel vs HubSpot site chat, test window 2026-09-22 to 2026-10-15, split
20% Corbel / 80% HubSpot. Captured to answer Corbel's reconciliation
questions and to preserve Clarity sessions before they expire (Clarity keeps
recordings about 30 days, so Sept 22 sessions expire around Oct 22).

## Sources and how to reproduce

| Source | What it holds | How it was read |
|---|---|---|
| Theme dashboard (Tools → Chat A/B Test) | Daily counters per provider and metric. No times, pages, or visitors. | Copied from the dashboard on 2026-10-13, then checked against its own daily rows. |
| Microsoft Clarity, project `rjqv4ko6q0` | One row per session, with the `chat_variant` tag and a `chat_conversation_started` event. | Recordings, Last 30 days, filters `chat_variant: corbel` + Smart events `chat_conversation_started`. URL: `https://clarity.microsoft.com/projects/view/rjqv4ko6q0/impressions?Variables=chat_variant%3Acorbel&date=Last%2030%20days&smartEvents=chat_conversation_started` |
| GA4, property `342310564` | Nothing for this test (see below). | Events report, Sep 17 to Oct 14. Admin → Custom definitions. |

## Dashboard totals (as of 2026-10-13)

| Metric | Corbel (n=8,600) | HubSpot (n=35,179) | Corbel rate | HubSpot rate | Lift | p-value |
|---|---|---|---|---|---|---|
| Conversations started | 30 | 156 | 0.35% | 0.44% | -21.3% | 0.2266 |
| Questions resolved | 0 | 0 | — | — | — | — |
| Handoffs to sales | 0 | 0 | — | — | — | — |
| Leads generated | 0 | 3 | 0.00% | 0.01% | -100% | 0.3918 |

These numbers were checked against the daily rows; all match. Without Oct 8
for both sides: Corbel 24 / 8,113 (0.30%), HubSpot 94 / 33,447 (0.28%),
p = 0.82, which is a tie.

## Corbel sessions in Clarity

There are 21 sessions in which a Corbel-assigned visitor opened the chat. The
full list is in [chat-experiment-clarity-corbel-sessions-2026-10-15.csv](chat-experiment-clarity-corbel-sessions-2026-10-15.csv).
Times are MST (UTC−7), the timezone of the browser that read them.

- 3 sessions are from Israel, where the Corbel team is based. That leaves 18
  sessions from outside visitors.
- 2 sessions are the same visitor (`t4842j`, Sept 22), so there are 17 unique
  outside visitors.
- 6 came from Facebook ads, 5 of them to `/gutter-machine-roi-calculator/`.
- 1 session (Oct 15, Zimbabwe) ended on the contact-form thank-you page.
- By day: Sep 22: 4, Sep 23: 2, Sep 25: 1, Sep 26: 1, Sep 27: 1, Sep 30: 1,
  Oct 1: 1, Oct 10: 2, Oct 12: 3, Oct 13: 4, Oct 15: 1.

## Findings

1. **"Conversation started" means different things per vendor.** For Corbel,
   it counts a widget *open* (the loader's `resize` message with
   `open: true`). For HubSpot, it counts HubSpot's `conversationStarted`
   event. The two are not comparable until Corbel sends a first-message event.
2. **The dashboard counts per page view, and Clarity counts per session.**
   This is why the dashboard shows 30 and Clarity shows 21. Clarity also
   misses visitors who block its script.
3. **Oct 8 looks like junk on both sides.** The dashboard logged 6 Corbel
   opens and 62 HubSpot conversations that day, while Clarity has no Corbel
   chat sessions on Oct 8. The HubSpot side of Oct 8 has not been checked in
   Clarity yet.
4. **GA4 never received the chat events.** The Events report has no
   `chat_experiment_exposure` or `chat_experiment_goal`. The theme pushes them
   to `dataLayer`, but no tag forwards them to GA4. `chat_provider` is also not
   a registered custom dimension. The dashboard's "cross-checks" note is wrong
   about GA4; only Clarity works.
5. **The HubSpot connector is on the wrong portal.** It is signed into portal
   3958217, not NTM's 4478417, so the 3 HubSpot chat leads have not yet been
   checked for quotes or sales.
6. **The amt-reporting Worker returns only aggregates.** It cannot list events
   or sessions. Its `atlas_metric_facts` tool also fails, because
   `ATLAS_API_BASE` is not set on the Worker.

## Open items

- Get Corbel's own numbers: conversations, resolved, handoffs, leads, and
  whether any led to quotes or sales.
- Pull the same Clarity list for `chat_variant: hubspot`, including Oct 8.
- Forward the chat events to GA4 and register `chat_provider`, so future data
  can be queried.
