/**
 * Accordion Module
 *
 * Smooth animated open/close for native <details> accordion groups.
 *
 * Markup contract:
 *   - [data-accordion-group]            — parent container
 *   - > details                          — direct children; one open at a time
 *   - .accordion__body OR
 *     [data-accordion-body]              — element to tween. Use the class for
 *                                          full-styled accordions (carries the
 *                                          px-6 py-6 border bg-white shell).
 *                                          Use the data attribute when you want
 *                                          the height tween without the shell
 *                                          styling (e.g. compact nav dropdowns).
 *
 * Timing is read from --resize-dur / --resize-ease in transitions.css so
 * every accordion in the theme breathes with the same motion tokens.
 *
 * @module Accordion
 */

const GROUP_SELECTOR = '[data-accordion-group]';

/* Pulls --resize-dur / --resize-ease from transitions.css so the
 * accordion tween shares the theme's motion vocabulary. Cubic-bezier
 * (0.22, 1, 0.36, 1) is the expo-out the panel-reveal uses — slower
 * settle than the keyword `ease-out`, reads as "premium" rather than
 * "snappy". */
function readMotionTokens() {
  if (typeof window === 'undefined') {
    return { duration: 300, easing: 'cubic-bezier(0.22, 1, 0.36, 1)' };
  }
  const cs = getComputedStyle(document.documentElement);
  const dur = parseFloat(cs.getPropertyValue('--resize-dur'));
  const ease = cs.getPropertyValue('--resize-ease').trim();
  return {
    duration: Number.isFinite(dur) ? dur : 300,
    easing: ease || 'cubic-bezier(0.22, 1, 0.36, 1)',
  };
}

/**
 * Animate a <details> element open.
 * @param {HTMLDetailsElement} detail
 */
function animateOpen(detail) {
  const body = detail.querySelector('.accordion__body, [data-accordion-body]');
  if (!body) {
    detail.open = true;
    return;
  }

  // Set open so the content renders (needed to measure height)
  detail.open = true;
  const height = body.scrollHeight;
  const { duration, easing } = readMotionTokens();

  // Animate from 0 to measured height
  body.style.overflow = 'hidden';
  const anim = body.animate(
    [
      { height: '0px', opacity: 0, paddingTop: '0px', paddingBottom: '0px' },
      { height: `${height}px`, opacity: 1 },
    ],
    { duration, easing }
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
  const body = detail.querySelector('.accordion__body, [data-accordion-body]');
  if (!body) {
    detail.open = false;
    return;
  }

  const height = body.scrollHeight;
  const { duration, easing } = readMotionTokens();

  body.style.overflow = 'hidden';
  const anim = body.animate(
    [
      { height: `${height}px`, opacity: 1 },
      { height: '0px', opacity: 0, paddingTop: '0px', paddingBottom: '0px' },
    ],
    { duration, easing }
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
