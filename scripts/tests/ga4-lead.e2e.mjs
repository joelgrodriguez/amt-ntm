// End-to-end check: a confirmed HubSpot form submit reaches GA4 as exactly one
// `generate_lead`, in real Chrome against the local DevKinsta site.
//
// Stubbed on purpose:
// - HubSpot's embed (window.hbspt), so no real CRM contact is created.
// - GA4 collect requests are intercepted and answered locally, because the
//   local site uses the production measurement ID. Nothing reaches Google.
// Everything else (theme bundle, Site Kit gtag, form mounting) is real.
//
// Usage: npm run test:ga4-lead-e2e   (BASE=https://newtech.local by default)
// Writes an evidence log to docs/test-evidence/.
import { chromium } from 'playwright';
import { mkdirSync, writeFileSync } from 'node:fs';
import { execSync } from 'node:child_process';
import { dirname, join } from 'node:path';
import { fileURLToPath } from 'node:url';

const BASE = process.env.BASE || 'https://newtech.local';
const PAGE = `${BASE}/contact/`;
const ROOT = join(dirname(fileURLToPath(import.meta.url)), '..', '..');
const lines = [];
let failed = false;
const log = (line) => { lines.push(line); console.log(line); };
const check = (name, ok, detail = '') => {
  if (!ok) failed = true;
  log(`${ok ? 'PASS' : 'FAIL'}  ${name}${detail ? `  (${detail})` : ''}`);
};

const HBSPT_STUB = () => {
  window.__hsForms = [];
  window.hbspt = {
    forms: {
      create(options) {
        window.__hsForms.push(options);
        const target = document.querySelector(options.target);
        if (target) target.insertAdjacentHTML('beforeend', '<form data-stub-form><input name="email" value="lead@example.com"></form>');
        options.onFormReady?.();
      },
    },
  };
};

// Parse every GA4 event out of a collect request (URL params and batched body lines).
function ga4Events(url, body) {
  const events = [];
  const shared = new URL(url).searchParams;
  const rows = body ? body.split('\n').filter(Boolean) : [''];
  for (const row of rows) {
    const params = new URLSearchParams(row);
    const name = params.get('en') || shared.get('en');
    if (!name) continue;
    const merged = new URLSearchParams(shared);
    for (const [k, v] of params) merged.set(k, v);
    events.push({ name, params: Object.fromEntries(merged) });
  }
  return events;
}

async function scenario(browser, { name, initScript }) {
  const context = await browser.newContext({ ignoreHTTPSErrors: true });
  const page = await context.newPage();
  const events = [];
  const pageErrors = [];
  page.on('pageerror', (err) => pageErrors.push(String(err)));

  await context.route('**/g/collect**', async (route) => {
    const req = route.request();
    events.push(...ga4Events(req.url(), req.postData()));
    await route.fulfill({ status: 204, body: '' });
  });
  await context.route('https://js.hsforms.net/**', (route) => route.abort());
  await page.addInitScript(HBSPT_STUB);
  if (initScript) await page.addInitScript(initScript);

  await page.goto(PAGE, { waitUntil: 'load' });
  await page.mouse.move(100, 200);
  await page.locator('[data-hubspot-form]').first().scrollIntoViewIfNeeded();
  await page.waitForFunction(() => window.__hsForms?.length > 0, null, { timeout: 15000 });

  const formId = await page.evaluate(() => window.__hsForms[0].formId);
  await page.waitForTimeout(2500);
  const leadsBeforeSubmit = events.filter((e) => e.name === 'generate_lead').length;

  const domEvent = await page.evaluate(() => new Promise((resolve) => {
    document.addEventListener('hubspot:formSubmitted', () => resolve(true), { once: true });
    window.__hsForms[0].onFormSubmitted?.();
    setTimeout(() => resolve(false), 3000);
  }));

  // gtag batches events and sends them about 5 s later. Poll for the lead;
  // negative scenarios wait the full window so a late send still fails them.
  const deadline = Date.now() + 10000;
  while (Date.now() < deadline && !events.some((e) => e.name === 'generate_lead')) {
    await page.waitForTimeout(250);
  }
  // Catch a duplicate send in a later batch.
  await page.waitForTimeout(1500);

  await context.close();
  return { name, formId, events, leadsBeforeSubmit, domEvent, pageErrors };
}

const browser = await chromium.launch({ channel: 'chrome', headless: true });
const stamp = new Date().toISOString().replace(/[-:]/g, '').replace(/\.\d+Z$/, 'Z');
log(`GA4 generate_lead E2E  ${stamp}`);
log(`page=${PAGE}  chrome=${browser.version()}`);
log(`commit=${execSync('git rev-parse --short HEAD', { cwd: ROOT }).toString().trim()}  branch=${execSync('git branch --show-current', { cwd: ROOT }).toString().trim()}`);
log('stubs: window.hbspt (no CRM writes); GA4 /g/collect answered locally (no hits reach Google)');
log('');

// 1, 2, 5: no plugin ownership -> theme sends exactly one clean generate_lead, only after submit.
const base = await scenario(browser, { name: 'theme-owned' });
const leads = base.events.filter((e) => e.name === 'generate_lead');
check('harness captures real GA4 traffic (page_view)', base.events.some((e) => e.name === 'page_view'), `events=${[...new Set(base.events.map((e) => e.name))].join(',')}`);
check('no generate_lead before the form is submitted', base.leadsBeforeSubmit === 0, `before=${base.leadsBeforeSubmit}`);
check('one generate_lead after a confirmed submit', leads.length === 1, `count=${leads.length}`);
check('generate_lead carries the HubSpot form id', leads[0]?.params['ep.form_id'] === base.formId, `form_id=${leads[0]?.params['ep.form_id']}`);
const leadDump = JSON.stringify(leads[0]?.params || {});
check('generate_lead carries no form field values', !leadDump.includes('lead@example.com'));
const leadPage = leads[0]?.params.dl ? new URL(leads[0].params.dl).pathname : undefined;
check('GA4 records the lead on /contact/', leadPage === '/contact/', `dl path=${leadPage}`);
check('form-submitted page event still fires', base.domEvent === true);
check('no page errors', base.pageErrors.length === 0, base.pageErrors.join(' | '));

// 3: plugin owns GA4 -> theme stays silent so the plugin's own listener is the only sender.
const plugin = await scenario(browser, {
  name: 'plugin-owned',
  initScript: () => { window.standardTrackingConfig = { destinations: { ga4: true, openai: true } }; },
});
const pluginLeads = plugin.events.filter((e) => e.name === 'generate_lead').length;
check('theme sends nothing when the plugin owns GA4', pluginLeads === 0, `count=${pluginLeads}`);

// 4: tag blocked -> no crash, thank-you flow still runs.
const blocked = await scenario(browser, {
  name: 'gtag-blocked',
  initScript: () => { Object.defineProperty(window, 'gtag', { configurable: true, get: () => undefined, set: () => {} }); },
});
check('blocked gtag: no page errors', blocked.pageErrors.length === 0, blocked.pageErrors.join(' | '));
check('blocked gtag: form-submitted page event still fires', blocked.domEvent === true);

await browser.close();
log('');
log(`RESULT: ${failed ? 'FAIL' : 'PASS'}`);
const out = join(ROOT, 'docs', 'test-evidence', `ga4-generate-lead-${stamp}.log`);
mkdirSync(dirname(out), { recursive: true });
writeFileSync(out, `${lines.join('\n')}\n`);
console.log(`evidence: docs/test-evidence/ga4-generate-lead-${stamp}.log`);
process.exit(failed ? 1 : 0);
