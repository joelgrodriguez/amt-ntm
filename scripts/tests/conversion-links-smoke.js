/**
 * Rendered conversion-path smoke check.
 *
 * Run against a deployed site:
 * NTM_BASE_URL=https://example.com npm run smoke:conversion-links
 */

const baseUrl = process.env.NTM_BASE_URL;
if (!baseUrl) {
  throw new Error('NTM_BASE_URL is required.');
}

const origin = new URL(baseUrl).origin;
const pages = [
  { path: '/machines/' },
  { path: '/roof-wall-panel-machines/' },
  { path: '/seamless-gutter-machines/' },
  {
    path: '/machines/gutter-machines/bg7-box-gutter-machine/',
    forbidFloatingCta: true,
  },
];

function assert(condition, message) {
  if (!condition) {
    throw new Error(message);
  }
}

async function get(url) {
  let lastError;

  for (let attempt = 1; attempt <= 3; attempt += 1) {
    try {
      const response = await fetch(url, {
        headers: { 'user-agent': 'NTM conversion-path smoke test' },
        redirect: 'follow',
        signal: AbortSignal.timeout(15_000),
      });

      if (response.ok) {
        return response;
      }

      lastError = new Error(`${url} returned HTTP ${response.status}.`);
      if (response.status < 500) {
        break;
      }
    } catch (error) {
      lastError = error;
    }

    if (attempt < 3) {
      await new Promise((resolve) => setTimeout(resolve, attempt * 1_000));
    }
  }

  throw lastError || new Error(`${url} could not be checked.`);
}

function getSalesLinks(html, pageUrl) {
  const anchors = html.match(/<a\b[^>]*>/gi) || [];
  const links = [];

  for (const anchor of anchors) {
    const isSalesCta = /\sdata-analytics-cta(?:\s|=|>)/i.test(anchor);
    const isProductTitle = /class=["'][^"']*card-product__title-link/i.test(anchor);
    if (!isSalesCta && !isProductTitle) {
      continue;
    }

    const href = anchor.match(/\shref=["']([^"']+)["']/i)?.[1] || '';
    if (href === '' || href.startsWith('#')) {
      continue;
    }

    const url = new URL(href.replaceAll('&amp;', '&'), pageUrl);
    if (url.origin === origin) {
      links.push(url.origin + url.pathname);
    }
  }

  return [...new Set(links)];
}

const destinations = new Set();
for (const page of pages) {
  const pageUrl = new URL(page.path, origin).href;
  const response = await get(pageUrl);
  const html = await response.text();

  if (page.forbidFloatingCta) {
    assert(
      !html.includes('id="floating-build-cta"'),
      'BG7 must not render the floating Build & Quote CTA.'
    );
  }

  const links = getSalesLinks(html, pageUrl);
  assert(links.length > 0, `${pageUrl} rendered no testable sales links.`);
  links.forEach((link) => destinations.add(link));
}

for (const destination of destinations) {
  await get(destination);
}

console.log(`Conversion link smoke passed: ${pages.length} pages, ${destinations.size} destinations.`);
