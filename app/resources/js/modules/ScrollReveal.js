/**
 * Scroll Reveal Module
 *
 * Reveals elements with smooth animations as they enter the viewport.
 * Uses IntersectionObserver for performance.
 *
 * @module ScrollReveal
 */

/**
 * Default options for scroll reveal.
 * @type {Object}
 */
const defaults = {
  selector: '.reveal, .reveal-left, .reveal-right, .reveal-scale',
  threshold: 0.1,
  rootMargin: '0px 0px -50px 0px',
};

/**
 * Initializes scroll reveal functionality.
 *
 * @param {Object} options - Configuration options
 * @param {string} [options.selector] - CSS selector for elements to reveal
 * @param {number} [options.threshold] - Intersection threshold (0-1)
 * @param {string} [options.rootMargin] - Root margin for intersection
 * @returns {void}
 */
export function initScrollReveal(options = {}) {
  const config = { ...defaults, ...options };
  const elements = document.querySelectorAll(config.selector);

  if (!elements.length) return;

  // Check for reduced motion preference
  const prefersReducedMotion = window.matchMedia(
    '(prefers-reduced-motion: reduce)'
  ).matches;

  if (prefersReducedMotion) {
    // Show all elements immediately if user prefers reduced motion
    elements.forEach((el) => el.classList.add('is-visible'));
    return;
  }

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          // Stop observing once revealed
          observer.unobserve(entry.target);
        }
      });
    },
    {
      threshold: config.threshold,
      rootMargin: config.rootMargin,
    }
  );

  elements.forEach((el) => observer.observe(el));
}
