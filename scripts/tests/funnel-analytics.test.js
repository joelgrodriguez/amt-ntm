import {
  APPROVED_FIELDS,
  EVENT_NAMES,
  emitAnalyticsEvent,
  trackCtaClick,
  trackHubspotFormSubmit,
} from '../../app/resources/js/modules/FunnelAnalytics.js';

function assert(condition, message) {
  if (!condition) {
    throw new Error(message);
  }
}

globalThis.window = {
  dataLayer: [],
  location: {
    origin: 'https://newtechmachinery.com',
    pathname: '/machines/',
  },
};

assert(
  Object.values(EVENT_NAMES).join(',') === 'cta_click,configurator_open,hubspot_form_submit,chat_interaction,chat_conversation_started',
  'The funnel must expose the approved conversion and HubSpot chat event names.'
);
assert(!APPROVED_FIELDS.includes('email'), 'Email must never be an approved analytics field.');

delete window.dataLayer;
assert(
  emitAnalyticsEvent(EVENT_NAMES.CHAT_INTERACTION, {
    chat_vendor: 'hubspot',
    page_path: '/machines/',
  }),
  'Chat analytics must create the dataLayer queue when GTM has not loaded yet.'
);
assert(window.dataLayer[0].chat_vendor === 'hubspot', 'Chat analytics must identify HubSpot without personal data.');

window.dataLayer.length = 0;
emitAnalyticsEvent(EVENT_NAMES.CTA_CLICK, {
  machine_id: 'ssr-multipro-jr',
  page_path: '/machines/',
  cta_location: 'test',
  destination: '/configurator/ssr/',
  email: 'never-send@example.com',
  name: 'Never Send',
  phone: '555-555-5555',
  message: 'Never send free text',
});

assert(window.dataLayer.length === 1, 'A valid CTA event should reach dataLayer.');
for (const forbidden of ['email', 'name', 'phone', 'message']) {
  assert(!(forbidden in window.dataLayer[0]), `${forbidden} must be removed from analytics payloads.`);
}

window.dataLayer.length = 0;
trackCtaClick({
  href: 'https://newtechmachinery.com/configurator/ssr/?email=never-send@example.com',
  dataset: { machineId: 'ssr-multipro-jr', analyticsLocation: 'product_card' },
  closest() { return null; },
});
assert(
  window.dataLayer.map(({ event }) => event).join(',') === 'cta_click,configurator_open',
  'A configurator CTA must emit click and configurator-open events.'
);
assert(
  window.dataLayer.every(({ destination }) => destination === '/configurator/ssr/'),
  'Destinations must exclude query parameters that could contain PII.'
);

trackHubspotFormSubmit('e5267365-c19e-4f19-991a-003c5fdbeecf');
const formEvent = window.dataLayer.at(-1);
assert(
  formEvent.event === 'hubspot_form_submit'
    && formEvent.form_id === 'e5267365-c19e-4f19-991a-003c5fdbeecf',
  'A successful HubSpot callback must emit the form ID only.'
);

delete window.dataLayer;
assert(
  emitAnalyticsEvent('unapproved_event', {}) === false,
  'Unapproved analytics events must fail closed.'
);

console.log('Funnel analytics tests passed.');
