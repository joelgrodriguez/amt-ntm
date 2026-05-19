/**
 * Contact Section Lazy Loaders
 *
 * Defers two heavy third-party embeds until the user actually needs them:
 *
 *   1. HubSpot form script — hydrated via IntersectionObserver when the
 *      contact section is within 400px of the viewport. Saves ~150–300ms
 *      of main-thread work for visitors who never scroll there.
 *
 *   2. Google Maps iframe — replaced server-side by a clickable placeholder.
 *      The placeholder hydrates to the real iframe on click or keyboard
 *      activation. No iframe HTTP traffic until the user opts in.
 *
 * Both behaviors share an AbortController so HMR / cleanup is straightforward.
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

function hydrateMap(placeholder) {
  const src = placeholder.dataset.mapSrc;
  const title = placeholder.dataset.mapTitle || 'Map';
  if (!src) return;

  const iframe = document.createElement('iframe');
  iframe.src = src;
  iframe.title = title;
  iframe.width = '100%';
  iframe.height = '100%';
  iframe.className = 'absolute inset-0';
  iframe.style.border = '0';
  iframe.allowFullscreen = true;
  iframe.loading = 'lazy';
  iframe.referrerPolicy = 'no-referrer-when-downgrade';

  placeholder.replaceWith(iframe);
}

export function initContactLazy() {
  cleanup();
  abortController = new AbortController();
  const { signal } = abortController;

  const formTarget = document.getElementById('contact-form');
  if (formTarget && 'IntersectionObserver' in window) {
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
  } else if (formTarget) {
    loadHubspot(formTarget);
  }

  const mapPlaceholders = document.querySelectorAll('[data-map-placeholder]');
  for (const placeholder of mapPlaceholders) {
    const onActivate = (e) => {
      if (e.type === 'keydown' && e.key !== 'Enter' && e.key !== ' ') return;
      e.preventDefault();
      hydrateMap(placeholder);
    };
    placeholder.addEventListener('click', onActivate, { signal });
    placeholder.addEventListener('keydown', onActivate, { signal });
  }
}

export function cleanup() {
  if (abortController) {
    abortController.abort();
    abortController = null;
  }
}
