/**
 * Accordion Module
 *
 * Smooth animated open/close for native <details> accordion groups.
 * Add [data-accordion-group] to the parent container.
 * All items closed by default; clicking opens (closing any open sibling),
 * clicking an open item closes it. At most one open at a time.
 *
 * @module Accordion
 */

const GROUP_SELECTOR = '[data-accordion-group]';
const DURATION = 250; // ms
const EASING = 'ease-out';

/**
 * Animate a <details> element open.
 * @param {HTMLDetailsElement} detail
 */
function animateOpen(detail) {
  const body = detail.querySelector('.accordion__body');
  if (!body) {
    detail.open = true;
    return;
  }

  // Set open so the content renders (needed to measure height)
  detail.open = true;
  const height = body.scrollHeight;

  // Animate from 0 to measured height
  body.style.overflow = 'hidden';
  const anim = body.animate(
    [
      { height: '0px', opacity: 0, paddingTop: '0px', paddingBottom: '0px' },
      { height: `${height}px`, opacity: 1 },
    ],
    { duration: DURATION, easing: EASING }
  );

  anim.onfinish = () => {
    body.style.overflow = '';
    body.style.height = '';
  };
}

/**
 * Animate a <details> element closed.
 * @param {HTMLDetailsElement} detail
 */
function animateClose(detail) {
  const body = detail.querySelector('.accordion__body');
  if (!body) {
    detail.open = false;
    return;
  }

  const height = body.scrollHeight;

  body.style.overflow = 'hidden';
  const anim = body.animate(
    [
      { height: `${height}px`, opacity: 1 },
      { height: '0px', opacity: 0, paddingTop: '0px', paddingBottom: '0px' },
    ],
    { duration: DURATION, easing: EASING }
  );

  anim.onfinish = () => {
    detail.open = false;
    body.style.overflow = '';
    body.style.height = '';
  };
}

/**
 * Initializes all accordion groups on the page.
 *
 * @returns {Function} Cleanup function.
 */
export function initAccordion() {
  const groups = document.querySelectorAll(GROUP_SELECTOR);
  if (!groups.length) return () => {};

  const controller = new AbortController();

  groups.forEach((group) => {
    const items = [...group.querySelectorAll(':scope > details')];
    if (!items.length) return;

    items.forEach((detail) => {
      const summary = detail.querySelector('summary');
      if (!summary) return;

      summary.addEventListener('click', (e) => {
        e.preventDefault();

        if (detail.open) {
          animateClose(detail);
          return;
        }

        items.forEach((other) => {
          if (other !== detail && other.open) {
            animateClose(other);
          }
        });

        animateOpen(detail);
      }, { signal: controller.signal });
    });
  });

  return () => controller.abort();
}
