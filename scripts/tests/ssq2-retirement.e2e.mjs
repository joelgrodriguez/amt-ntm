// End-to-end check: the SSQ II MultiPro is discontinued and the SSQ3 MultiPro
// is the only buying path, in real Chrome against a running site.
//
// Protects:
// - The old buying URL (/configurator/ssqii/) redirects to the SSQ3
//   configurator and keeps tracking parameters.
// - No checked page links to the SSQ II configurator or carries its expired
//   offer copy, price, or Corbel catalog ID.
// - SSQ II resources keep their content and lead their notice with the SSQ3.
// - Other machines, and the SSQ200 / SSQ210A / SSQ275 profiles, are untouched.
//
// Real boundary: Corbel's hosted quote form loads for the SSQ3 configurator.
// Nothing is submitted, so no quote or CRM record is created.
//
// Usage: npm run test:ssq2-retirement-e2e   (BASE=https://newtech.local by default)
// Writes an evidence log and screenshots to docs/test-evidence/.
import { chromium } from 'playwright';
import { mkdirSync, writeFileSync } from 'node:fs';
import { execSync } from 'node:child_process';
import { dirname, join } from 'node:path';
import { fileURLToPath } from 'node:url';

const BASE = (process.env.BASE || 'https://newtech.local').replace(/\/$/, '');
const ROOT = join(dirname(fileURLToPath(import.meta.url)), '..', '..');
const EVIDENCE_DIR = join(ROOT, 'docs', 'test-evidence');
const STAMP = new Date().toISOString().replace(/[-:]/g, '').replace(/\.\d+Z$/, 'Z');
// DevKinsta's nginx answers 503 when requests arrive too quickly.
const PAUSE_MS = Number(process.env.PAUSE_MS || 1500);

const SSQ2_PRODUCT = '/machines/roof-wall-panel-machines/ssq-roof-panel-machine/';
const SSQ3_PRODUCT = '/machines/roof-wall-panel-machines/ssq3-multipro/';
const SSQ2_CONFIGURATOR = '/configurator/ssqii/';
const SSQ3_CONFIGURATOR = '/configurator/ssq3-multi-pro/';

const lines = [];
let failed = false;
const log = (line) => { lines.push(line); console.log(line); };
const check = (name, ok, detail = '') => {
  if (!ok) failed = true;
  log(`${ok ? 'PASS' : 'FAIL'}  ${name}${detail ? `  (${detail})` : ''}`);
};
const sleep = (ms) => new Promise((resolve) => setTimeout(resolve, ms));

mkdirSync(EVIDENCE_DIR, { recursive: true });
const browser = await chromium.launch({ channel: 'chrome', headless: true });
const context = await browser.newContext({ ignoreHTTPSErrors: true });

async function get(path, { follow = true } = {}) {
  for (let attempt = 1; attempt <= 4; attempt += 1) {
    await sleep(PAUSE_MS * attempt);
    const response = await context.request.get(BASE + path, {
      maxRedirects: follow ? 5 : 0,
      failOnStatusCode: false,
    });
    if (response.status() !== 503 || attempt === 4) {
      return {
        status: response.status(),
        location: response.headers().location || '',
        html: await response.text(),
      };
    }
  }
  throw new Error(`unreachable: ${path}`);
}

const pathOf = (url) => {
  const parsed = new URL(url, BASE);
  return parsed.pathname + parsed.search;
};

// Text and markup that only an open SSQ II sale would produce.
const EXPIRED_OFFER = [
  ['SSQ II configurator link', /\/configurator\/ssqii\b/i],
  ['SSQ II Corbel catalog ID', /ssq2-multipro-panel/i],
  ['SSQ II quote button', /Build\s*(?:&amp;|&)\s*Quote SSQ II/i],
  ['last-chance copy', /last chance to purchase/i],
  ['final-sale copy', /Available for purchase through/i],
  ['pre-retirement badge', /Discontinuing Sep/i],
  ['pre-retirement label', /Will Be Discontinued/i],
];

function checkNoExpiredOffer(name, html) {
  const hits = EXPIRED_OFFER.filter(([, pattern]) => pattern.test(html)).map(([label]) => label);
  check(`${name}: no expired SSQ II offer`, hits.length === 0, hits.join(', '));
}

const noticeOf = (html) => html.match(/<aside[^>]*aria-label="Machine status"[\s\S]*?<\/aside>/)?.[0] || '';
const hrefsOf = (html) => [...html.matchAll(/\shref="([^"]+)"/g)].map((match) => pathOf(match[1].replaceAll('&amp;', '&')));

function checkNotice(name, html) {
  const notice = noticeOf(html);
  check(`${name}: status notice renders`, notice !== '');
  if (notice === '') return;

  const text = notice.replace(/<[^>]+>/g, ' ').replace(/\s+/g, ' ');
  const ssq3At = text.indexOf('SSQ3 MultiPro');
  const dateAt = text.indexOf('September 30, 2026');
  check(`${name}: notice leads with the SSQ3 MultiPro`, ssq3At !== -1 && (dateAt === -1 || ssq3At < dateAt), text.trim().slice(0, 140));
  check(`${name}: notice keeps the retirement date as context`, dateAt !== -1);

  const hrefs = hrefsOf(notice);
  check(`${name}: notice links to the SSQ3 page`, hrefs.includes(SSQ3_PRODUCT), hrefs.join(' '));
  check(`${name}: notice links to the SSQ3 configurator`, hrefs.includes(SSQ3_CONFIGURATOR), hrefs.join(' '));
}

log(`# SSQ II retirement E2E  ${STAMP}`);
log(`base=${BASE}  commit=${execSync('git rev-parse --short HEAD', { cwd: ROOT }).toString().trim()}  branch=${execSync('git branch --show-current', { cwd: ROOT }).toString().trim()}  dirty=${execSync('git status --porcelain', { cwd: ROOT }).toString().trim() !== ''}`);

// 1. Old buying URL.
log('\n## Old buying URL');
{
  const plain = await get(SSQ2_CONFIGURATOR, { follow: false });
  check('old SSQ II configurator URL redirects permanently', plain.status === 301, `status ${plain.status}`);
  check('redirect lands on the SSQ3 configurator', plain.location !== '' && pathOf(plain.location) === SSQ3_CONFIGURATOR, plain.location);

  const tracked = await get(`${SSQ2_CONFIGURATOR}?utm_source=e2e&utm_campaign=ssq2-retirement&gclid=abc123`, { follow: false });
  const target = tracked.location ? new URL(tracked.location, BASE) : null;
  check(
    'redirect keeps tracking parameters',
    target !== null
      && target.pathname === SSQ3_CONFIGURATOR
      && target.searchParams.get('utm_source') === 'e2e'
      && target.searchParams.get('utm_campaign') === 'ssq2-retirement'
      && target.searchParams.get('gclid') === 'abc123',
    tracked.location
  );

  const landed = await get(SSQ2_CONFIGURATOR);
  check('followed redirect serves the SSQ3 catalog ID', /data-corbel-product-id="ssq3"/.test(landed.html));
  checkNoExpiredOffer('followed redirect', landed.html);
}

// 2. SSQ II product page: a product notice, not a sales page.
log('\n## SSQ II product page');
{
  const page = await get(SSQ2_PRODUCT);
  check('SSQ II product page still resolves', page.status === 200, `status ${page.status}`);
  checkNoExpiredOffer('SSQ II product page', page.html);
  checkNotice('SSQ II product page', page.html);
  check('SSQ II product page shows no price', !/\$1[23]0K/.test(page.html) && !/"lowPrice"/.test(page.html.match(/"@type":"Product"[\s\S]*?<\/script>/)?.[0] || ''));
  check('SSQ II Product schema is Discontinued', /"availability":"https:\\\/\\\/schema\.org\\\/Discontinued"/.test(page.html));
  check('SSQ II product page keeps the owner manual link', page.html.includes('/learning-center/manual/ssq-ii-roof-panel-machine-manual/'));
  check('SSQ II product page keeps legacy specs', page.html.includes('75 ft'));
}

// 3. Historical resources keep their content and gain the SSQ3-led notice.
log('\n## Historical resources');
for (const [name, path, keeps] of [
  ['SSQ II article', '/learning-center/ssq-roof-panel-machine-features-benefits/', 'Review of the SSQ II'],
  ['SSQ II training video', '/learning-center/video/ssq-training-general-overview-video/', 'Training General Overview'],
  ['SSQ II price sheet', '/learning-center/pricesheet/ssqii-roof-panel-machine-pricing/', 'SSQII Roof Panel Machine Pricing'],
  ['SSQ II service hub', '/service-hub/ssq-ii-multipro/', 'SSQ II'],
  ['SSQII accessory archive', '/product-tag/ssqii/', 'SSQII'],
]) {
  const page = await get(path);
  check(`${name}: resolves`, page.status === 200, `status ${page.status}`);
  check(`${name}: keeps its own content`, page.html.includes(keeps));
  checkNoExpiredOffer(name, page.html);
  checkNotice(name, page.html);
}

// 4. Active sales paths never offer the SSQ II.
log('\n## Active sales paths');
for (const path of ['/', '/machines/', '/roof-wall-panel-machines/', '/choose-your-machine/', '/compare-roof-panel-machines/', '/configurator/', '/?s=SSQ+II', '/llms.txt']) {
  const page = await get(path);
  check(`${path}: resolves`, page.status === 200, `status ${page.status}`);
  checkNoExpiredOffer(path, page.html);
}
{
  const chooser = await get('/choose-your-machine/');
  check('machine chooser does not list the SSQ II', !hrefsOf(chooser.html).includes(SSQ2_PRODUCT) && !/SSQ II™ MultiPro/.test(chooser.html));
  check('machine chooser lists the SSQ3', hrefsOf(chooser.html).includes(SSQ3_PRODUCT));

  const home = await get('/');
  check('home quiz teaser does not name the SSQ II as a choice', !/SSH™ MultiPro, or SSQ II/.test(home.html));

  const search = await get('/?s=SSQ+II');
  check('SSQ II search still surfaces the SSQ3', hrefsOf(search.html).includes(SSQ3_PRODUCT));

  const llms = await get('/llms.txt');
  check('llms.txt names the SSQ3 as the current model', /SSQ3 MultiPro\]\([^)]+\) is the current model/.test(llms.html));
}

// 5. SSQ3 quote path, in a real browser.
log('\n## SSQ3 quote path');
{
  const product = await get(SSQ3_PRODUCT);
  check('SSQ3 product page resolves', product.status === 200, `status ${product.status}`);
  check('SSQ3 product page links to its configurator', hrefsOf(product.html).includes(SSQ3_CONFIGURATOR));
  check('SSQ3 product page shows no status notice', noticeOf(product.html) === '');

  const page = await context.newPage();
  await sleep(PAUSE_MS);
  await page.goto(`${BASE}${SSQ2_CONFIGURATOR}?utm_source=e2e`, { waitUntil: 'load' });
  const landedUrl = new URL(page.url());
  check('browser on the old URL ends on the SSQ3 configurator', landedUrl.pathname === SSQ3_CONFIGURATOR && landedUrl.searchParams.get('utm_source') === 'e2e', page.url());

  let frameSrc = '';
  try {
    const frame = page.locator('iframe[src*="corbelpay.com"]').first();
    await frame.waitFor({ state: 'attached', timeout: 30000 });
    frameSrc = (await frame.getAttribute('src')) || '';
  } catch {
    // Reported by the check below.
  }
  const frameUrl = frameSrc ? new URL(frameSrc) : null;
  check('Corbel quote form loads for the SSQ3', frameUrl?.searchParams.get('o') === 'ssq3', frameSrc || 'no Corbel iframe');
  check('Corbel quote form receives the tracking parameter', frameUrl?.searchParams.get('utm_source') === 'e2e', frameSrc);
  await page.waitForTimeout(4000);
  await page.screenshot({ path: join(EVIDENCE_DIR, `ssq2-retirement-${STAMP}-ssq3-configurator.png`) });

  await sleep(PAUSE_MS);
  await page.goto(BASE + SSQ2_PRODUCT, { waitUntil: 'load' });
  const notice = page.locator('aside[aria-label="Machine status"]').first();
  await notice.scrollIntoViewIfNeeded();
  await page.screenshot({ path: join(EVIDENCE_DIR, `ssq2-retirement-${STAMP}-ssq2-product.png`) });
  await sleep(PAUSE_MS);
  try {
    await notice.getByRole('link', { name: /Explore SSQ3 MultiPro/i }).click({ timeout: 10000 });
    await page.waitForURL(`**${SSQ3_PRODUCT}`, { timeout: 30000 });
  } catch {
    // Reported by the check below.
  }
  check('notice CTA opens the SSQ3 product page', new URL(page.url()).pathname === SSQ3_PRODUCT, page.url());
  await page.close();
}

// 6. Controls: unrelated machines and look-alike profile names.
log('\n## Controls');
for (const [slug, catalogId] of [['ssh', 'ssh-multipro-panel'], ['ssr', 'ssr-multipro-panel'], ['wav', 'wav-wall-panel'], ['machii', 'mach2-gutter'], ['5vc', '5vc-crimp-panel']]) {
  const page = await get(`/configurator/${slug}/`, { follow: false });
  check(`/configurator/${slug}/ serves its own configurator`, page.status === 200 && page.html.includes(`data-corbel-product-id="${catalogId}"`), `status ${page.status}`);
}
{
  const ssh = await get('/machines/roof-wall-panel-machines/ssh-roof-panel-machine/');
  check('SSH product page has no status notice', ssh.status === 200 && noticeOf(ssh.html) === '');
  check('SSH product page keeps its own configurator link', hrefsOf(ssh.html).includes('/configurator/ssh/'));
  check('SSH product page keeps its price schema', /"lowPrice":"71600"/.test(ssh.html));
}
for (const profile of ['ssq200', 'ssq210a', 'ssq275']) {
  const page = await get(`/learning-center/profile/${profile}/`);
  check(`${profile.toUpperCase()} profile page has no status notice`, page.status === 200 && noticeOf(page.html) === '', `status ${page.status}`);
}

await browser.close();

log(`\nRESULT: ${failed ? 'FAIL' : 'PASS'}`);
const file = join(EVIDENCE_DIR, `ssq2-retirement-${STAMP}.log`);
writeFileSync(file, lines.join('\n') + '\n');
console.log(`evidence: ${file}`);
process.exit(failed ? 1 : 0);
