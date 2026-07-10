/**
 * Generic HubSpot form lazy loader.
 *
 * Mounts any element with data-hubspot-form when it nears the viewport.
 *
 * @file HubspotForms.js
 */

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
  clearPlaceholder(target);

  try {
    const hubspot = await loadHubspotScript();
    hubspot?.forms?.create({
      region,
      portalId,
      formId,
      target: `#${target.id}`,
    });
    return true;
  } catch (_error) {
    target.dataset.hubspotLoaded = 'false';
    target.innerHTML = '<p class="text-sm text-blue-600">Form unavailable. Call New Tech Machinery directly.</p>';
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
