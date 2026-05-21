/**
 * Carousel Navigation Module
 *
 * Handles prev/next buttons that target a scrollable track by ID.
 * Buttons are also hidden when their track doesn't overflow, so a
 * three-card section on a wide desktop doesn't show inert arrows.
 * Visibility recomputes on resize.
 *
 * @module CarouselNav
 */

const PREV_SELECTOR = '[data-carousel-prev]';
const NEXT_SELECTOR = '[data-carousel-next]';
const HIDE_CLASS = 'is-hidden';

/**
 * Compute how far a carousel should scroll for one step.
 *
 * @param {HTMLElement} track
 * @returns {number}
 */
function getScrollAmount(track) {
  const card = track.querySelector(':scope > a, :scope > article, :scope > div, :scope > figure');
  if (!card) {
    return track.clientWidth;
  }

  return card.offsetWidth + 16;
}

/**
 * Scroll the targeted track by one card.
 *
 * @param {HTMLButtonElement} button
 * @param {'prev'|'next'} direction
 * @returns {void}
 */
function scrollTrack(button, direction) {
  const targetId = direction === 'prev' ? button.dataset.carouselPrev : button.dataset.carouselNext;
  if (!targetId) {
    return;
  }

  const track = document.getElementById(targetId);
  if (!track) {
    return;
  }

  const amount = direction === 'prev' ? -getScrollAmount(track) : getScrollAmount(track);
  track.scrollBy({ left: amount, behavior: 'smooth' });
}

/**
 * Update visibility of nav buttons based on whether their track
 * actually overflows. A 1px tolerance smooths sub-pixel rounding.
 *
 * @param {HTMLButtonElement[]} buttons
 */
function refreshOverflowVisibility(buttons) {
  const groupedByTarget = new Map();
  buttons.forEach((button) => {
    const targetId = button.dataset.carouselPrev || button.dataset.carouselNext;
    if (!targetId) return;
    if (!groupedByTarget.has(targetId)) {
      groupedByTarget.set(targetId, []);
    }
    groupedByTarget.get(targetId).push(button);
  });

  groupedByTarget.forEach((groupButtons, targetId) => {
    const track = document.getElementById(targetId);
    if (!track) return;
    const overflows = track.scrollWidth - track.clientWidth > 1;
    groupButtons.forEach((button) => {
      button.classList.toggle(HIDE_CLASS, !overflows);
    });
  });
}

/**
 * Initializes reusable carousel navigation.
 *
 * @returns {Function} Cleanup function.
 */
export function initCarouselNav() {
  const buttons = Array.from(document.querySelectorAll(`${PREV_SELECTOR}, ${NEXT_SELECTOR}`));
  if (!buttons.length) {
    return () => {};
  }

  const controller = new AbortController();
  const { signal } = controller;

  buttons.forEach((button) => {
    button.addEventListener(
      'click',
      () => {
        const direction = button.matches(PREV_SELECTOR) ? 'prev' : 'next';
        scrollTrack(button, direction);
      },
      { signal }
    );
  });

  refreshOverflowVisibility(buttons);

  let resizeTimer = null;
  const onResize = () => {
    if (resizeTimer) {
      window.cancelAnimationFrame(resizeTimer);
    }
    resizeTimer = window.requestAnimationFrame(() => refreshOverflowVisibility(buttons));
  };
  window.addEventListener('resize', onResize, { signal });

  // Images can change track width after first paint as they load.
  buttons.forEach((button) => {
    const targetId = button.dataset.carouselPrev || button.dataset.carouselNext;
    const track = targetId ? document.getElementById(targetId) : null;
    if (!track) return;
    track.querySelectorAll('img').forEach((img) => {
      if (!img.complete) {
        img.addEventListener('load', onResize, { signal, once: true });
      }
    });
  });

  return () => controller.abort();
}
