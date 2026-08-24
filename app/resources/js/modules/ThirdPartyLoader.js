/**
 * Load non-essential replay and Corbel chat scripts after interaction or
 * post-load idle time.
 *
 * Measurement-critical GA and Meta scripts retain their vendor-provided
 * async/defer behavior so landing-page attribution is not sacrificed.
 */

const INTERACTION_EVENTS = ['pointerdown', 'keydown', 'touchstart', 'scroll'];
const IDLE_TIMEOUT_MS = 1500;
const CHAT_STAGGER_MS = 500;

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

function loadCorbel(scriptUrl) {
  if (!scriptUrl || document.querySelector('#corbelAssistant, [data-ntm-corbel-chat]')) return;

  const placeholder = document.createElement('div');
  placeholder.id = 'corbelAssistant';
  document.body.appendChild(placeholder);

  loadScript(scriptUrl, { 'data-ntm-corbel-chat': 'true' });
}

export function initThirdPartyLoader() {
  const config = window.ntmThirdPartyConfig || {};
  const controller = new AbortController();
  const { signal } = controller;
  let started = false;
  let timer = 0;
  let idleCallback = 0;

  const start = () => {
    if (started) return;
    started = true;
    window.clearTimeout(timer);
    if (idleCallback && 'cancelIdleCallback' in window) {
      window.cancelIdleCallback(idleCallback);
    }

    loadClarity(config.clarityProjectId);
    timer = window.setTimeout(
      () => loadCorbel(config.corbelChatScriptUrl),
      CHAT_STAGGER_MS
    );
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
