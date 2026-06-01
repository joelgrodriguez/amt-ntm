/**
 * Scroll Reveal Module
 *
 * Reveals elements with smooth animations as they enter the viewport.
 * Uses IntersectionObserver for performance.
 *
 * @module ScrollReveal
 */

/**
 * Map a `data-reveal` attribute value to its reveal CSS class.
 * Unknown / empty / "fade" fall back to the base `reveal` class.
 * `stagger` is the one value whose class is not `reveal-`-prefixed
 * (it's a child-cascade modifier applied alongside child reveals).
 * @param {string|undefined} value
 * @returns {string}
 */
export function revealClassFor(value) {
  switch (value) {
    case 'stagger': return 'stagger';
    case 'image':   return 'reveal-image';
    case 'rule':    return 'reveal-rule';
    case 'left':    return 'reveal-left';
    case 'right':   return 'reveal-right';
    case 'scale':   return 'reveal-scale';
    default:        return 'reveal';
  }
}

/**
 * Default options for scroll reveal.
 * @type {Object}
 */
const defaults = {
  selector: '.reveal, .reveal-left, .reveal-right, .reveal-scale, .reveal-image, .reveal-rule, [data-reveal]',
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

  elements.forEach((el) => {
    if (el.hasAttribute('data-reveal')) {
      el.classList.add(revealClassFor(el.getAttribute('data-reveal')));
    }
  });

  if (!elements.length) return;
  const prefersReducedMotion = window.matchMedia(
    '(prefers-reduced-motion: reduce)'
  ).matches;

  if (prefersReducedMotion) {
    elements.forEach((el) => el.classList.add('is-visible'));
    return;
  }

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
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
