/**
 * Learning Center Scroll Cue
 *
 * Smooth-scrolls the filter bar to the top of the viewport when
 * the pulsing chevron in the hero fold is clicked. Uses native
 * scrollIntoView and relies on CSS scroll-padding-top to offset
 * for the sticky header.
 *
 * @module LearningCenterScroll
 */

export function init() {
  const cue = document.querySelector('[data-lc-scroll-cue]');
  const target = document.getElementById('lc-filters');

  if (!cue || !target) {
    return () => {};
  }

  const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  function onClick(event) {
    event.preventDefault();
    target.scrollIntoView({
      behavior: prefersReducedMotion ? 'auto' : 'smooth',
      block: 'start',
    });
  }

  cue.addEventListener('click', onClick);

  return () => {
    cue.removeEventListener('click', onClick);
  };
}
