/**
 * Corbel vs HubSpot chat A/B experiment (self-hosted).
 *
 * Assignment: a sticky cookie buckets each visitor into 'corbel' or
 * 'hubspot' using the split published in
 * `window.ntmThirdPartyConfig.chatExperiment`. If Nelio A/B Testing is ever
 * installed and its variant snippet sets `window.ntmChatProvider` first,
 * that assignment wins.
 *
 * Reporting: exposures and chat goals are sent to the theme's REST endpoint
 * (aggregate counters feeding the Tools -> Chat A/B Test dashboard), GA4 via
 * dataLayer, Microsoft Clarity as a session tag, and mirrored to Nelio's
 * `nab.convert()` when an experiment ID is configured.
 */

export const PROVIDER_CORBEL = 'corbel';
export const PROVIDER_HUBSPOT = 'hubspot';

const COOKIE_NAME = 'ntmChatVariant';
const COOKIE_MAX_AGE = 60 * 60 * 24 * 90; // 90 days

/** How long to wait for a Nelio variant snippet before self-assigning. */
const VARIANT_TIMEOUT_MS = 4000;

export const GOALS = {
  conversationStarted: 0,
  questionResolved: 1,
  salesHandoff: 2,
  leadGenerated: 3,
};

const GOAL_METRICS = [
  'conversation_started',
  'question_resolved',
  'sales_handoff',
  'lead_generated',
];

function experimentConfig() {
  const config = window.ntmThirdPartyConfig || {};
  return config.chatExperiment || {};
}

function isValidProvider(value) {
  return value === PROVIDER_CORBEL || value === PROVIDER_HUBSPOT;
}

function readCookie() {
  const match = document.cookie.match(
    new RegExp(`(?:^|; )${COOKIE_NAME}=(corbel|hubspot)`)
  );
  return match ? match[1] : '';
}

function writeCookie(provider) {
  document.cookie = `${COOKIE_NAME}=${provider}; path=/; max-age=${COOKIE_MAX_AGE}; SameSite=Lax`;
}

/**
 * Sticky self-assignment: reuse the cookie when present, otherwise roll
 * once against the configured Corbel share.
 */
function selfAssign() {
  const existing = readCookie();
  if (existing) return existing;

  const corbelShare = Number(experimentConfig().corbelSplit) || 20;
  const provider =
    Math.random() * 100 < corbelShare ? PROVIDER_CORBEL : PROVIDER_HUBSPOT;
  writeCookie(provider);
  return provider;
}

/**
 * Send an aggregate counter to the theme's REST endpoint. Fire-and-forget;
 * sendBeacon survives page unloads.
 */
function beacon(provider, metric) {
  const { trackUrl } = experimentConfig();
  if (!trackUrl) return;

  const body = JSON.stringify({ provider, metric });

  if (navigator.sendBeacon) {
    navigator.sendBeacon(
      trackUrl,
      new Blob([body], { type: 'application/json' })
    );
    return;
  }

  fetch(trackUrl, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body,
    keepalive: true,
  }).catch(() => {});
}

function tagAnalytics(provider) {
  if (Array.isArray(window.dataLayer)) {
    window.dataLayer.push({
      event: 'chat_experiment_exposure',
      chat_provider: provider,
    });
  }

  // Clarity queues calls made before its script loads.
  if (typeof window.clarity === 'function') {
    window.clarity('set', 'chat_variant', provider);
  }
}

/**
 * Resolve which chat provider this visitor should see.
 *
 * - Experiment stopped: always HubSpot — current site behavior.
 * - Nelio present and mid-assignment: wait briefly for its variant snippet.
 * - Otherwise: sticky cookie self-assignment at the configured split.
 *
 * Resolution also records the exposure (REST + GA4 + Clarity), once per
 * page view.
 *
 * @returns {Promise<'corbel'|'hubspot'>}
 */
export function resolveChatProvider() {
  if (!experimentConfig().enabled) {
    return Promise.resolve(PROVIDER_HUBSPOT);
  }

  const finish = (provider) => {
    window.ntmChatProvider = provider;
    tagAnalytics(provider);
    beacon(provider, 'exposure');
    return provider;
  };

  // A Nelio snippet (or QA override) already decided.
  if (isValidProvider(window.ntmChatProvider)) {
    return Promise.resolve(finish(window.ntmChatProvider));
  }

  // Nelio active but variants not applied yet: give it a moment.
  if (window.nabIsLoading) {
    return new Promise((resolve) => {
      let settled = false;
      const settle = () => {
        if (settled) return;
        settled = true;
        resolve(
          finish(
            isValidProvider(window.ntmChatProvider)
              ? window.ntmChatProvider
              : selfAssign()
          )
        );
      };
      window.addEventListener('nelio-ab-testing/variant-ready', settle, {
        once: true,
      });
      window.setTimeout(settle, VARIANT_TIMEOUT_MS);
    });
  }

  return Promise.resolve(finish(selfAssign()));
}

/**
 * Report a chat goal conversion to every sink. Safe when any sink is absent.
 *
 * @param {number} goalIndex - One of the GOALS values.
 */
export function trackChatGoal(goalIndex) {
  const { enabled, experimentId } = experimentConfig();
  if (!enabled) return;

  const provider = window.ntmChatProvider || readCookie() || PROVIDER_HUBSPOT;
  const metric = GOAL_METRICS[goalIndex];
  if (!metric) return;

  beacon(provider, metric);

  if (Array.isArray(window.dataLayer)) {
    window.dataLayer.push({
      event: 'chat_experiment_goal',
      chat_provider: provider,
      chat_goal: metric,
    });
  }

  if (typeof window.clarity === 'function') {
    window.clarity('event', `chat_${metric}`);
  }

  if (experimentId) {
    try {
      window.nab?.convert?.(experimentId, goalIndex);
    } catch {
      // Nelio validates its arguments aggressively; never break the page.
    }
  }
}

/**
 * Bridge chat-widget events from both vendors onto the shared goals so each
 * variant reports the same funnel.
 *
 * Corbel: its assistant is a cross-origin iframe whose loader relays
 * postMessages; widget-open is the conversation-started proxy until Corbel
 * emits richer events. Also accepts `corbel:*` CustomEvents and the generic
 * `ntm:chat-goal` escape hatch.
 * HubSpot: subscribes to the Conversations SDK once it loads.
 *
 * @returns {Function} cleanup
 */
export function initChatExperiment() {
  if (!experimentConfig().enabled) {
    return () => {};
  }

  const controller = new AbortController();
  const { signal } = controller;
  const fired = new Set();

  const fireOnce = (goalIndex) => {
    if (fired.has(goalIndex)) return;
    fired.add(goalIndex);
    trackChatGoal(goalIndex);
  };

  // Vendor-neutral escape hatch: any script can report a goal with
  // window.dispatchEvent(new CustomEvent('ntm:chat-goal', { detail: { goal: 'salesHandoff' } }))
  window.addEventListener(
    'ntm:chat-goal',
    (event) => {
      const goal = GOALS[event.detail?.goal];
      if (typeof goal === 'number') fireOnce(goal);
    },
    { signal }
  );

  // Corbel assistant events.
  const corbelEventGoals = {
    'corbel:conversation-started': GOALS.conversationStarted,
    'corbel:question-resolved': GOALS.questionResolved,
    'corbel:handoff': GOALS.salesHandoff,
    'corbel:lead-captured': GOALS.leadGenerated,
  };
  Object.entries(corbelEventGoals).forEach(([eventName, goalIndex]) => {
    window.addEventListener(eventName, () => fireOnce(goalIndex), { signal });
  });

  // Corbel's loader relays a `resize` postMessage with `open: true` when
  // the visitor expands the widget. Map any future goal-shaped messages
  // (`{ source: 'corbel-reception-assistant', type: 'goal', goal: '...' }`).
  window.addEventListener(
    'message',
    (event) => {
      const data = event.data;
      if (!data || data.source !== 'corbel-reception-assistant') return;
      if (!String(event.origin).endsWith('.corbelpay.com')) return;

      if (data.type === 'resize' && data.open) {
        fireOnce(GOALS.conversationStarted);
      } else if (data.type === 'goal') {
        const goal = GOALS[data.goal];
        if (typeof goal === 'number') fireOnce(goal);
      }
    },
    { signal }
  );

  // HubSpot Conversations SDK events.
  const bindHubspot = () => {
    const conversations = window.HubSpotConversations;
    if (!conversations?.on) return;

    conversations.on('conversationStarted', () =>
      fireOnce(GOALS.conversationStarted)
    );
    conversations.on('contactAssociated', () =>
      fireOnce(GOALS.leadGenerated)
    );
  };

  if (window.HubSpotConversations) {
    bindHubspot();
  } else {
    window.hsConversationsOnReady = [
      ...(window.hsConversationsOnReady || []),
      bindHubspot,
    ];
  }

  return () => controller.abort();
}
