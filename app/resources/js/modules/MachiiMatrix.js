/**
 * MACH II Variant Matrix Module
 *
 * Two jobs on the /machines/machii/ page:
 *
 * 1. Smooth-scroll the sticky variant tab anchors into view, offset
 *    by the WP admin bar + sticky tabs height, so clicking a tab
 *    doesn't jam the variant title under the tab strip.
 * 2. Mark the currently-visible variant's tab with `aria-current`
 *    via IntersectionObserver so the active machine is always
 *    obvious as the user scrolls.
 *
 * Both behaviors degrade gracefully: without JS, the tabs still
 * navigate via native anchor hash, and CSS scroll-margin-top on the
 * variant articles handles the offset. The JS adds the active-tab
 * highlight on scroll, which is the only behavior that requires it.
 *
 * @module MachiiMatrix
 *
 * @usage MACH II Family (page-machii.php)
 * @see templates/pages/machii/variant-matrix.php
 */

/**
 * Initialize the MACH II variant matrix.
 *
 * @returns {Function} Cleanup function.
 */
export function initMachiiMatrix() {
  const matrix = document.querySelector('[data-machii-matrix]');
  if (!matrix) {
    return () => {};
  }

  const tabs = matrix.querySelectorAll('.machii-tabs__link');
  const variants = matrix.querySelectorAll('.machii-variant');

  if (!tabs.length || !variants.length) {
    return () => {};
  }

  const tabBySlug = new Map();
  tabs.forEach((tab) => {
    const slug = tab.dataset.variantSlug;
    if (slug) {
      tabBySlug.set(slug, tab);
    }
  });

  /**
   * Set the active tab by slug. Idempotent.
   * @param {string} slug
   */
  function setActive(slug) {
    tabs.forEach((tab) => {
      const isActive = tab.dataset.variantSlug === slug;
      if (isActive) {
        tab.setAttribute('aria-current', 'true');
      } else {
        tab.removeAttribute('aria-current');
      }
    });
  }

  // Pick the variant closest to the top of the viewport (but not
  // above it) as the active one. rootMargin pulls the trigger line
  // about a third down the viewport so swapping happens as the next
  // variant takes over the visual center, not when it merely enters.
  const observer = new IntersectionObserver(
    (entries) => {
      let bestSlug = null;
      let bestTop = Infinity;

      entries.forEach((entry) => {
        if (!entry.isIntersecting) {
          return;
        }
        const top = entry.boundingClientRect.top;
        if (top < bestTop) {
          bestTop = top;
          bestSlug = entry.target.dataset.variantSlug || null;
        }
      });

      if (bestSlug) {
        setActive(bestSlug);
      }
    },
    {
      rootMargin: '-30% 0px -55% 0px',
      threshold: 0,
    }
  );

  variants.forEach((variant) => observer.observe(variant));

  // Default to the first tab on first paint so the strip has a
  // visible active state before the user has scrolled.
  const firstSlug = variants[0]?.dataset.variantSlug;
  if (firstSlug) {
    setActive(firstSlug);
  }

  return () => {
    observer.disconnect();
  };
}
