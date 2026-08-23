/**
 * Non-PII analytics for the website-to-lead funnel.
 *
 * Google Tag Manager owns delivery. This module only pushes approved event
 * data when its dataLayer is available.
 */

export const EVENT_NAMES = Object.freeze({
  CTA_CLICK: 'cta_click',
  CONFIGURATOR_OPEN: 'configurator_open',
  HUBSPOT_FORM_SUBMIT: 'hubspot_form_submit',
  CHAT_INTERACTION: 'chat_interaction',
  CHAT_CONVERSATION_STARTED: 'chat_conversation_started',
});

export const APPROVED_FIELDS = Object.freeze([
  'machine_id',
  'page_path',
  'cta_location',
  'destination',
  'form_id',
  'chat_vendor',
]);

let clickHandler = null;

function approvedPayload(payload) {
  return Object.fromEntries(
    APPROVED_FIELDS
      .filter((field) => typeof payload[field] === 'string' && payload[field] !== '')
      .map((field) => [field, payload[field]])
  );
}

/**
 * Push one whitelisted event. Missing or blocked analytics fails open.
 */
export function emitAnalyticsEvent(eventName, payload = {}) {
  if (!Object.values(EVENT_NAMES).includes(eventName)) {
    return false;
  }

  try {
    if (!Array.isArray(window.dataLayer)) {
      return false;
    }

    window.dataLayer.push({
      event: eventName,
      ...approvedPayload(payload),
    });
    return true;
  } catch (_error) {
    return false;
  }
}

function getPath(url) {
  try {
    return new URL(url, window.location.origin).pathname;
  } catch (_error) {
    return '';
  }
}

function getMachineId(link) {
  return link.dataset.machineId
    || link.closest?.('[data-machine-id]')?.dataset?.machineId
    || '';
}

/**
 * Track a sales link and its configurator entry, when applicable.
 */
export function trackCtaClick(link) {
  const destination = getPath(link.href);
  const payload = {
    machine_id: getMachineId(link),
    page_path: window.location.pathname,
    cta_location: link.dataset.analyticsLocation || 'sales_link',
    destination,
  };

  emitAnalyticsEvent(EVENT_NAMES.CTA_CLICK, payload);

  if (destination.startsWith('/configurator/')) {
    emitAnalyticsEvent(EVENT_NAMES.CONFIGURATOR_OPEN, payload);
  }
}

/**
 * Track the successful HubSpot callback without reading submitted fields.
 */
export function trackHubspotFormSubmit(formId) {
  emitAnalyticsEvent(EVENT_NAMES.HUBSPOT_FORM_SUBMIT, {
    form_id: String(formId || ''),
    page_path: window.location.pathname,
  });
}

export function initFunnelAnalytics() {
  cleanupFunnelAnalytics();

  clickHandler = (event) => {
    const link = event.target?.closest?.('a[href]');
    if (!link) {
      return;
    }

    const destination = getPath(link.href);
    const isSalesLink = link.hasAttribute('data-analytics-cta')
      || destination.startsWith('/configurator/')
      || destination === '/contact/'
      || destination === '/contact';

    if (isSalesLink) {
      trackCtaClick(link);
    }
  };

  document.addEventListener('click', clickHandler);
  return cleanupFunnelAnalytics;
}

export function cleanupFunnelAnalytics() {
  if (clickHandler) {
    document.removeEventListener('click', clickHandler);
    clickHandler = null;
  }
}
