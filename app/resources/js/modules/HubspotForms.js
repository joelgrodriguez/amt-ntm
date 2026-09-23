/**
 * Generic HubSpot form lazy loader.
 *
 * Mounts any element with data-hubspot-form when it nears the viewport.
 *
 * @file HubspotForms.js
 */

import { trackHubspotFormSubmit, trackOpenAiLead } from './FunnelAnalytics.js';

const HUBSPOT_SRC = 'https://js.hsforms.net/forms/embed/v2.js';

let scriptPromise = null;
let observers = [];

function loadHubspotScript() {
  if (window.hbspt?.forms) {
    return Promise.resolve(window.hbspt);
  }

  if (scriptPromise) {
    return scriptPromise;
  }

  scriptPromise = new Promise((resolve, reject) => {
    const existing = document.querySelector(`script[src="${HUBSPOT_SRC}"]`);

    if (existing) {
      existing.addEventListener('load', () => resolve(window.hbspt), { once: true });
      existing.addEventListener('error', reject, { once: true });
      return;
    }

    const script = document.createElement('script');
    script.src = HUBSPOT_SRC;
    script.async = true;
    script.charset = 'utf-8';
    script.addEventListener('load', () => resolve(window.hbspt), { once: true });
    script.addEventListener('error', reject, { once: true });
    document.head.appendChild(script);
  });

  return scriptPromise;
}

function clearPlaceholder(target) {
  target.querySelector('[data-hubspot-placeholder]')?.remove();
}

/**
 * Keep the loading skeleton until HubSpot inserts its markup, so the card is
 * never blank while the embed script downloads.
 *
 * @param {HTMLElement} target
 * @returns {MutationObserver}
 */
function clearPlaceholderWhenFormArrives(target) {
  const observer = new MutationObserver((mutations) => {
    const formArrived = mutations.some((mutation) =>
      Array.from(mutation.addedNodes).some(
        (node) => node.nodeType === Node.ELEMENT_NODE && !node.matches('[data-hubspot-placeholder]')
      )
    );

    if (formArrived) {
      clearPlaceholder(target);
      observer.disconnect();
    }
  });

  observer.observe(target, { childList: true });
  return observer;
}

function showFallback(target) {
  const fallback = target.querySelector('template[data-hubspot-fallback]');
  target.innerHTML = fallback
    ? fallback.innerHTML
    : '<p class="text-sm text-blue-600">Form unavailable. Call New Tech Machinery directly.</p>';
}

/**
 * Mount a single HubSpot form target (idempotent).
 * Exported so gated UIs can force-load a form that is still off-screen.
 *
 * @param {HTMLElement} target
 * @returns {Promise<boolean>}
 */
export async function ensureHubspotForm(target) {
  if (!(target instanceof HTMLElement)) {
    return false;
  }
  return mountForm(target);
}

async function mountForm(target) {
  if (target.dataset.hubspotLoaded === 'true') {
    return true;
  }

  const formId = target.dataset.hubspotFormId;
  const portalId = target.dataset.hubspotPortalId;
  const region = target.dataset.hubspotRegion || 'na1';

  if (!formId || !portalId || !target.id) {
    // Misconfigured element — retrying won't fix a missing ID, so report
    // success to let the observer unobserve it instead of retrying forever.
    return true;
  }

  target.dataset.hubspotLoaded = 'true';
  const placeholderObserver = clearPlaceholderWhenFormArrives(target);

  try {
    const hubspot = await loadHubspotScript();
    hubspot?.forms?.create({
      region,
      portalId,
      formId,
      target: `#${target.id}`,
      // Let page modules (e.g. readiness quiz) swap loaders once fields paint.
      onFormReady: () => {
        placeholderObserver.disconnect();
        clearPlaceholder(target);
        target.dataset.hubspotReady = 'true';
        target.dispatchEvent(
          new CustomEvent('hubspot:formReady', {
            bubbles: true,
            detail: { formId, portalId },
          })
        );
      },
      // Let page modules unlock content after a successful submit.
      onFormSubmitted: () => {
        trackHubspotFormSubmit(formId);
        trackOpenAiLead(formId);
        target.dispatchEvent(
          new CustomEvent('hubspot:formSubmitted', {
            bubbles: true,
            detail: { formId, portalId },
          })
        );
      },
    });
    return true;
  } catch (_error) {
    target.dataset.hubspotLoaded = 'false';
    target.dataset.hubspotReady = 'false';
    placeholderObserver.disconnect();
    showFallback(target);
    target.dispatchEvent(
      new CustomEvent('hubspot:formReady', {
        bubbles: true,
        detail: { formId, portalId, error: true },
      })
    );
    return false;
  }
}

export function initHubspotForms() {
  cleanupHubspotForms();

  const forms = Array.from(document.querySelectorAll('[data-hubspot-form]'));

  if (forms.length === 0) {
    return cleanupHubspotForms;
  }

  if (!('IntersectionObserver' in window)) {
    forms.forEach(mountForm);
    return cleanupHubspotForms;
  }

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach(async (entry) => {
        if (!entry.isIntersecting) {
          return;
        }

        const mounted = await mountForm(entry.target);
        if (mounted) {
          observer.unobserve(entry.target);
        }
      });
    },
    { rootMargin: '500px 0px' }
  );

  forms.forEach((form) => observer.observe(form));
  observers.push(observer);

  return cleanupHubspotForms;
}

export function cleanupHubspotForms() {
  observers.forEach((observer) => observer.disconnect());
  observers = [];
}
