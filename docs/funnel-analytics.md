# Funnel analytics

The theme sends three non-PII events to the existing Google Tag Manager `dataLayer`:

- `cta_click`
- `configurator_open`
- `hubspot_form_submit`

Payloads can contain only a machine ID, page path, CTA location, destination path, and HubSpot form ID. The helper drops all other fields. It does not read form values.

## Corbel completion gap

The theme can track entry into a configurator, but it cannot see completion inside Corbel's cross-origin iframe. Corbel must send a validated `postMessage` event or a server-side webhook before NTM can track that step. Do not label `configurator_open` as a completed quote.
