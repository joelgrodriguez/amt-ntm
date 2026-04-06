/**
 * Accordion Module
 *
 * Smooth animated open/close for native <details> accordion groups.
 * Add [data-accordion-group] to the parent container.
 * First <details> opens on init; clicking another closes the rest.
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

    // Ensure first item is open
    if (!items.some((d) => d.open)) {
      items[0].open = true;
    }

    items.forEach((detail) => {
      const summary = detail.querySelector('summary');
      if (!summary) return;

      summary.addEventListener('click', (e) => {
        e.preventDefault();

        if (detail.open) {
          // Don't close if it's the only open one (always-one-open)
          return;
        }

        // Close any open sibling with animation
        items.forEach((other) => {
          if (other !== detail && other.open) {
            animateClose(other);
          }
        });

        // Open the clicked one with animation
        animateOpen(detail);
      }, { signal: controller.signal });
    });
  });

  return () => controller.abort();
}
