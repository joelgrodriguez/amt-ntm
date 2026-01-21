/**
 * ScrollHeader Module
 *
 * Implements scroll-aware sticky header behavior:
 * - Scrolling down: header slides up and disappears
 * - Scrolling up: header reappears and becomes sticky
 * - At top of page: header returns to normal flow
 *
 * @module ScrollHeader
 */

const SCROLL_THRESHOLD = 100; // Pixels before header starts hiding
const SCROLL_DELTA = 10; // Minimum scroll distance to trigger change

/**
 * Initialize scroll header behavior
 * @returns {Function} Cleanup function
 */
export function init() {
  const header = document.getElementById('site-header');

  if (!header) {
    return () => {};
  }

  let lastScrollY = window.scrollY;
  let ticking = false;

  /**
   * Check if mobile menu or mega menu is open
   * @returns {boolean}
   */
  function isMenuOpen() {
    const mobileMenu = document.getElementById('mobile-menu');
    const hasOpenMegaMenu = document.querySelector('.menu-item-has-children.menu-open');

    return (
      (mobileMenu && mobileMenu.classList.contains('is-open')) ||
      hasOpenMegaMenu !== null
    );
  }

  /**
   * Update header state based on scroll position and direction
   */
  function updateHeader() {
    const currentScrollY = window.scrollY;
    const scrollDelta = currentScrollY - lastScrollY;

    // Don't update if menu is open
    if (isMenuOpen()) {
      ticking = false;
      return;
    }

    // At top of page - remove all states
    if (currentScrollY < SCROLL_THRESHOLD) {
      header.classList.remove('header--hidden', 'header--sticky');
      lastScrollY = currentScrollY;
      ticking = false;
      return;
    }

    // Scrolling down - hide header
    if (scrollDelta > SCROLL_DELTA) {
      header.classList.add('header--hidden');
      header.classList.remove('header--sticky');
    }
    // Scrolling up - show sticky header
    else if (scrollDelta < -SCROLL_DELTA) {
      header.classList.remove('header--hidden');
      header.classList.add('header--sticky');
    }

    lastScrollY = currentScrollY;
    ticking = false;
  }

  /**
   * Handle scroll event with requestAnimationFrame
   */
  function onScroll() {
    if (!ticking) {
      requestAnimationFrame(updateHeader);
      ticking = true;
    }
  }

  // Add scroll listener
  window.addEventListener('scroll', onScroll, { passive: true });

  // Return cleanup function
  return () => {
    window.removeEventListener('scroll', onScroll);
    header.classList.remove('header--hidden', 'header--sticky');
  };
}
