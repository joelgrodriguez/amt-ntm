/**
 * Floating Quote CTA Module
 *
 * Shows a floating "Get a Quote" button after scrolling past the hero
 * on machine product pages. Uses IntersectionObserver for performance.
 *
 * @module FloatingQuoteCta
 *
 * @usage Single Machine Product (single-machine.php)
 */

/**
 * Initialize floating quote CTA visibility.
 * @returns {Function} Cleanup function.
 */
export function initFloatingQuoteCta() {
  const cta = document.getElementById('floating-quote-cta');
  const hero = document.getElementById('machine-hero');

  if (!cta || !hero) return () => {};

  const observer = new IntersectionObserver(
    ([entry]) => {
      // Show when hero is out of view
      if (entry.isIntersecting) {
        cta.classList.remove('is-visible');
      } else {
        cta.classList.add('is-visible');
      }
    },
    { threshold: 0 }
  );

  observer.observe(hero);

  return () => observer.disconnect();
}
