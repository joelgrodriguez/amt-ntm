// Real Chrome against DevKinsta; third-party traffic is blocked before delivery.
// Run: node scripts/tests/visitor-tracker.e2e.mjs
import assert from 'node:assert/strict';
import { execFileSync } from 'node:child_process';
import { mkdirSync, writeFileSync } from 'node:fs';
import { dirname, join } from 'node:path';
import { fileURLToPath } from 'node:url';
import { setTimeout } from 'node:timers/promises';
import { chromium } from 'playwright';

const ROOT = join(dirname(fileURLToPath(import.meta.url)), '..', '..');
const BASE = process.env.BASE || 'https://newtech.local';
const ORIGIN = new URL(BASE).origin;
const RB2B_URL = 'https://ddwl4m2hdecbv.cloudfront.net/b/XOE9GH3PRZOM/XOE9GH3PRZOM.js.gz';
const LEADPIPE_URL = 'https://leadpipe.aws53.cloud/p/e66aa922-7659-4180-ad54-c3462b614191.js';
const stamp = new Date().toISOString().replace(/[-:]/g, '').replace(/\.\d+Z$/, 'Z');
const evidence = join(ROOT, 'docs/test-evidence', `visitor-tracker-${stamp}.log`);
const lines = [];
const log = (line) => { lines.push(line); console.log(line); };
let browser;
let failed = false;

async function scenario(name, options = {}, preload = false) {
  const context = await browser.newContext({ ignoreHTTPSErrors: true, serviceWorkers: 'block', ...options });
  const requests = [];
  try {
    await context.route('**/*', (route) => {
      const request = route.request();
      requests.push(request.url());
      if (new URL(request.url()).origin !== ORIGIN || request.method() !== 'GET') {
        return route.abort();
      }
      return route.continue();
    });
    if (preload) {
      await context.addInitScript(() => { window.reb2b = { loaded: true, existing: true }; });
    }
    const page = await context.newPage();
    const response = await page.goto(`${BASE}/?visitor-tracker-check=${stamp}-${name}`, { waitUntil: 'load' });
    assert.equal(response.status(), 200, 'public page must load');
    const html = await response.text();
    assert.ok(!html.includes('secure.smart-business-intuition.com'), 'Lead Forensics script and noscript pixel must not render');
    assert.ok(!requests.some((url) => url.includes('smart-business-intuition.com')), 'Lead Forensics must not be requested');
    const leadpipe = page.locator(`head script[src="${LEADPIPE_URL}"]`);
    assert.equal(await leadpipe.count(), 1, 'Leadpipe must render the supplied account once in the page head');
    assert.notEqual(await leadpipe.getAttribute('async'), null, 'Leadpipe must load asynchronously');

    if (options.javaScriptEnabled !== false) {
      assert.equal(requests.filter((url) => url === LEADPIPE_URL).length, 1, 'Leadpipe must request the supplied account once');
      assert.ok(html.includes('XOE9GH3PRZOM'), 'page must contain the configured RB2B account');
      if (preload) {
        assert.equal(requests.filter((url) => url === RB2B_URL).length, 0, 'existing RB2B must not load again');
        assert.equal(await page.evaluate(() => window.reb2b.existing), true, 'existing RB2B must remain intact');
      } else {
        assert.equal(requests.filter((url) => url === RB2B_URL).length, 1, 'RB2B must request the configured account once');
        assert.equal(await page.evaluate(() => window.reb2b.loaded), true, 'RB2B bootstrap must initialize');
        await page.evaluate(() => {
          const bootstrap = [...document.scripts].find((script) => !script.src && script.textContent.includes('XOE9GH3PRZOM'));
          (0, eval)(bootstrap.textContent);
        });
        assert.equal(await page.evaluate((url) => [...document.scripts].filter((script) => script.src === url && script.async).length, RB2B_URL), 1, 'repeated bootstrap must not add a second script');
      }
    }
    log(`PASS ${name}`);
  } finally {
    log(`${name} vendor requests: ${JSON.stringify(requests.filter((url) => /cloudfront\.net|smart-business-intuition\.com|leadpipe\.aws53\.cloud/.test(url)))}`);
    await context.close();
  }
}

try {
  log(`Visitor tracker E2E ${stamp}`);
  log(`command=node scripts/tests/visitor-tracker.e2e.mjs BASE=${BASE}`);
  log(`commit=${execFileSync('git', ['rev-parse', '--short', 'HEAD'], { cwd: ROOT }).toString().trim()}`);
  log('Boundaries: real local WordPress and Chrome; third-party and non-GET requests aborted; vendor ingestion unverified.');
  browser = await chromium.launch({ channel: 'chrome', headless: true });
  log(`chrome=${browser.version()}`);
  await scenario('public-page');
  await setTimeout(1500);
  await scenario('already-loaded', {}, true);
  await setTimeout(1500);
  await scenario('javascript-disabled', { javaScriptEnabled: false });
} catch (error) {
  failed = true;
  log(`FAIL ${error.stack || error}`);
} finally {
  await browser?.close();
  log(`RESULT: ${failed ? 'FAIL' : 'PASS'}`);
  mkdirSync(dirname(evidence), { recursive: true });
  writeFileSync(evidence, `${lines.join('\n')}\n`);
  console.log(`evidence: ${evidence}`);
}
process.exitCode = failed ? 1 : 0;
