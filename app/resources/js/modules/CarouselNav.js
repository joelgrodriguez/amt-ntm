/**
 * Carousel Navigation Module
 *
 * Handles prev/next buttons that target a scrollable track by ID.
 * This keeps interactive behavior in the JS layer instead of inline in PHP
 * templates, which makes the templates easier to read and reuse.
 *
 * @module CarouselNav
 */

const PREV_SELECTOR = '[data-carousel-prev]';
const NEXT_SELECTOR = '[data-carousel-next]';

/**
 * Compute how far a carousel should scroll for one step.
 *
 * @param {HTMLElement} track
 * @returns {number}
 */
function getScrollAmount(track) {
  const card = track.querySelector(':scope > a, :scope > article, :scope > div');
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
 * Initializes reusable carousel navigation.
 *
 * @returns {Function} Cleanup function.
 */
export function initCarouselNav() {
  const buttons = document.querySelectorAll(`${PREV_SELECTOR}, ${NEXT_SELECTOR}`);
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

  return () => controller.abort();
}
