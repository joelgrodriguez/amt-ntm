/**
 * Load non-essential replay and chat scripts after interaction or post-load
 * idle time.
 *
 * Measurement-critical GA and Meta scripts retain their vendor-provided
 * async/defer behavior so landing-page attribution is not sacrificed.
 */

import { emitAnalyticsEvent, EVENT_NAMES } from './FunnelAnalytics.js';
import { resolveChatProvider, PROVIDER_CORBEL } from './ChatExperiment.js';

const INTERACTION_EVENTS = ['pointerdown', 'keydown', 'touchstart', 'scroll'];
const IDLE_TIMEOUT_MS = 1500;
const CHAT_STAGGER_MS = 500;
const LOCAL_CORBEL_CONFIG_URL = 'data:application/json,%7B%22enabled%22%3Atrue%7D';

function loadScript(src, attributes = {}) {
  return new Promise((resolve) => {
    const script = document.createElement('script');
    script.src = src;
    script.async = false;

    Object.entries(attributes).forEach(([name, value]) => {
      if (value) script.setAttribute(name, value);
    });

    script.addEventListener('load', resolve, { once: true });
    script.addEventListener('error', resolve, { once: true });
    document.head.appendChild(script);
  });
}

function loadClarity(projectId) {
  if (!projectId || window.clarity) return;

  window.clarity = function clarityQueue() {
    window.clarity.q.push(arguments);
  };
  window.clarity.q = [];

  loadScript(`https://www.clarity.ms/tag/${encodeURIComponent(projectId)}`);
}

function registerHubspotEvents() {
  if (window.__ntmHubspotAnalyticsRegistered) return;
  window.__ntmHubspotAnalyticsRegistered = true;

  const onReady = () => {
    let interacted = false;
    let conversationStarted = false;

    window.HubSpotConversations?.on('userInteractedWithWidget', () => {
      if (interacted) return;
      interacted = true;
      emitAnalyticsEvent(EVENT_NAMES.CHAT_INTERACTION, {
        chat_vendor: 'hubspot',
        page_path: window.location.pathname,
      });
    });

    window.HubSpotConversations?.on('conversationStarted', () => {
      if (conversationStarted) return;
      conversationStarted = true;
      emitAnalyticsEvent(EVENT_NAMES.CHAT_CONVERSATION_STARTED, {
        chat_vendor: 'hubspot',
        page_path: window.location.pathname,
      });
    });
  };

  if (window.HubSpotConversations) {
    onReady();
    return;
  }

  window.hsConversationsOnReady = window.hsConversationsOnReady || [];
  window.hsConversationsOnReady.push(onReady);
}

function loadHubspot(portalId) {
  if (!portalId || document.querySelector('#hs-script-loader, [data-ntm-hubspot-chat]')) return;

  loadScript(`https://js.hs-scripts.com/${encodeURIComponent(portalId)}.js`, {
    id: 'hs-script-loader',
    'data-ntm-hubspot-chat': 'true',
  });
}

function loadCorbel(scriptUrl) {
  if (!scriptUrl || document.querySelector('#corbelAssistant, [data-ntm-corbel-chat]')) return;

  const placeholder = document.createElement('div');
  placeholder.id = 'corbelAssistant';
  document.body.appendChild(placeholder);

  const attributes = { 'data-ntm-corbel-chat': 'true' };
  if (window.location.hostname.endsWith('.local')) {
    attributes['data-assistant-config-url'] = LOCAL_CORBEL_CONFIG_URL;
  }

  loadScript(scriptUrl, attributes);
}

/**
 * Load exactly one chat vendor per visitor. Without a running experiment
 * every visitor gets HubSpot; the experiment cookie assigns Corbel to its
 * configured share.
 */
function loadChat(config) {
  resolveChatProvider().then((provider) => {
    if (provider === PROVIDER_CORBEL && config.corbelScriptUrl) {
      loadCorbel(config.corbelScriptUrl);
    } else {
      loadHubspot(config.hubspotPortalId);
    }
  });
}

export function initThirdPartyLoader() {
  const config = window.ntmThirdPartyConfig || {};
  const controller = new AbortController();
  const { signal } = controller;
  let started = false;
  let timer = 0;
  let idleCallback = 0;

  registerHubspotEvents();

  const start = () => {
    if (started) return;
    started = true;
    window.clearTimeout(timer);
    if (idleCallback && 'cancelIdleCallback' in window) {
      window.cancelIdleCallback(idleCallback);
    }

    loadClarity(config.clarityProjectId);
    timer = window.setTimeout(() => loadChat(config), CHAT_STAGGER_MS);
  };

  INTERACTION_EVENTS.forEach((eventName) => {
    window.addEventListener(eventName, start, {
      once: true,
      passive: true,
      signal,
    });
  });

  const scheduleAfterLoad = () => {
    if (started) return;

    if ('requestIdleCallback' in window) {
      idleCallback = window.requestIdleCallback(start, { timeout: IDLE_TIMEOUT_MS });
    } else {
      timer = window.setTimeout(start, IDLE_TIMEOUT_MS);
    }
  };

  if (document.readyState === 'complete') {
    scheduleAfterLoad();
  } else {
    window.addEventListener('load', scheduleAfterLoad, { once: true, signal });
  }

  return () => {
    controller.abort();
    window.clearTimeout(timer);
    if (idleCallback && 'cancelIdleCallback' in window) {
      window.cancelIdleCallback(idleCallback);
    }
  };
}
