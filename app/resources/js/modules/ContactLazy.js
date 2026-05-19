/**
 * Contact Section Lazy Loader
 *
 * Defers the HubSpot form embed until the user is close to seeing it.
 * Hydrated via IntersectionObserver with a 400px rootMargin so the form
 * is ready by the time the user reaches the contact section. Saves
 * ~150–300ms of main-thread work for visitors who never scroll there.
 *
 * The Google Maps iframe uses browser-native loading="lazy" in the
 * template; no JS hydration needed for it.
 *
 * @file ContactLazy.js
 *
 * @usage Front Page (front-page.php)
 * @template templates/parts/front-page/contact.php
 */

const HUBSPOT_SRC = 'https://js.hsforms.net/forms/embed/v2.js';
const HUBSPOT_PORTAL_ID = '4478417';
const HUBSPOT_FORM_ID = '8819d347-bf19-49e1-8e49-cd45dbd7235f';
const HUBSPOT_REGION = 'na1';

let abortController = null;
let hubspotLoaded = false;

function loadHubspot(target) {
  if (hubspotLoaded) return;
  hubspotLoaded = true;

  const script = document.createElement('script');
  script.src = HUBSPOT_SRC;
  script.async = true;
  script.charset = 'utf-8';
  script.addEventListener('load', () => {
    if (!window.hbspt || !window.hbspt.forms) return;
    window.hbspt.forms.create({
      region: HUBSPOT_REGION,
      portalId: HUBSPOT_PORTAL_ID,
      formId: HUBSPOT_FORM_ID,
      target: '#' + target.id,
    });
  });
  document.head.appendChild(script);
}

export function initContactLazy() {
  cleanup();
  abortController = new AbortController();
  const { signal } = abortController;

  const formTarget = document.getElementById('contact-form');
  if (!formTarget) return;

  if ('IntersectionObserver' in window) {
    const observer = new IntersectionObserver(
      (entries, obs) => {
        for (const entry of entries) {
          if (entry.isIntersecting) {
            loadHubspot(formTarget);
            obs.disconnect();
          }
        }
      },
      { rootMargin: '400px 0px' }
    );
    observer.observe(formTarget);
    signal.addEventListener('abort', () => observer.disconnect());
  } else {
    loadHubspot(formTarget);
  }
}

export function cleanup() {
  if (abortController) {
    abortController.abort();
    abortController = null;
  }
}
