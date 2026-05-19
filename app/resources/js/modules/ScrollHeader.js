/**
 * ScrollHeader Module
 *
 * Implements scroll-aware sticky header behavior:
 * - Scrolling down: header slides up and disappears
 * - Scrolling up: header reappears, becomes sticky, then auto-hides
 * - At top of page: header returns to normal flow
 *
 * @module ScrollHeader
 */

const SCROLL_THRESHOLD = 100; // Pixels before header starts hiding
const SCROLL_DELTA = 10; // Minimum scroll distance to trigger change
const AUTO_HIDE_DELAY = 2500; // How long the revealed sticky header stays visible

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
  let autoHideTimer = 0;
  let pointerInsideHeader = false;
  let focusInsideHeader = false;
  let focusCheckTimer = 0;

  /**
   * Check if mobile menu or mega menu is open
   * @returns {boolean}
   */
  function isMenuOpen() {
    const mobileMenu = document.getElementById('mobile-menu');
    const hasOpenMegaMenu = document.querySelector(
      '.mega-panel.is-open, .mega-trigger[aria-expanded="true"]'
    );

    return (
      (mobileMenu && mobileMenu.classList.contains('is-open')) ||
      hasOpenMegaMenu !== null
    );
  }

  /**
   * Header should not auto-hide while the user is actively using it.
   * @returns {boolean}
   */
  function isHeaderInUse() {
    return pointerInsideHeader || focusInsideHeader || isMenuOpen();
  }

  /**
   * Clear pending auto-hide timeout.
   */
  function clearAutoHideTimer() {
    if (!autoHideTimer) {
      return;
    }

    window.clearTimeout(autoHideTimer);
    autoHideTimer = 0;
  }

  /**
   * Hide the header while keeping it fixed so the transform can animate out.
   */
  function hideHeader() {
    clearAutoHideTimer();
    header.classList.add('header--sticky', 'header--hidden');
  }

  /**
   * Reset header back into the normal document flow.
   */
  function resetHeader() {
    clearAutoHideTimer();
    header.classList.remove('header--hidden', 'header--sticky');
  }

  /**
   * Reveal the fixed header without changing timer state.
   */
  function revealStickyHeader() {
    header.classList.add('header--sticky');
    header.classList.remove('header--hidden');
  }

  /**
   * Show the sticky header and queue its idle auto-hide.
   */
  function showStickyHeader() {
    revealStickyHeader();
    scheduleAutoHide();
  }

  /**
   * Schedule auto-hide when the sticky header is currently visible.
   */
  function scheduleAutoHideIfVisible() {
    if (
      window.scrollY >= SCROLL_THRESHOLD &&
      header.classList.contains('header--sticky') &&
      !header.classList.contains('header--hidden')
    ) {
      scheduleAutoHide();
    }
  }

  /**
   * Auto-hide the revealed header after an idle period.
   */
  function scheduleAutoHide() {
    clearAutoHideTimer();

    autoHideTimer = window.setTimeout(() => {
      autoHideTimer = 0;

      if (window.scrollY < SCROLL_THRESHOLD) {
        resetHeader();
        return;
      }

      if (isHeaderInUse()) {
        scheduleAutoHide();
        return;
      }

      hideHeader();
    }, AUTO_HIDE_DELAY);
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
      resetHeader();
      lastScrollY = currentScrollY;
      ticking = false;
      return;
    }

    // Scrolling down - hide header
    if (scrollDelta > SCROLL_DELTA) {
      hideHeader();
    }
    // Scrolling up - show sticky header
    else if (scrollDelta < -SCROLL_DELTA) {
      showStickyHeader();
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

  function onPointerEnter() {
    pointerInsideHeader = true;
    clearAutoHideTimer();
  }

  function onPointerLeave() {
    pointerInsideHeader = false;
    scheduleAutoHideIfVisible();
  }

  function onFocusIn() {
    focusInsideHeader = true;
    clearAutoHideTimer();

    if (window.scrollY >= SCROLL_THRESHOLD) {
      revealStickyHeader();
    }
  }

  function onFocusOut() {
    window.clearTimeout(focusCheckTimer);
    focusCheckTimer = window.setTimeout(() => {
      focusInsideHeader = header.contains(document.activeElement);
      scheduleAutoHideIfVisible();
    }, 0);
  }

  // Add scroll listener
  window.addEventListener('scroll', onScroll, { passive: true });
  header.addEventListener('pointerenter', onPointerEnter);
  header.addEventListener('pointerleave', onPointerLeave);
  header.addEventListener('focusin', onFocusIn);
  header.addEventListener('focusout', onFocusOut);

  // Return cleanup function
  return () => {
    window.removeEventListener('scroll', onScroll);
    header.removeEventListener('pointerenter', onPointerEnter);
    header.removeEventListener('pointerleave', onPointerLeave);
    header.removeEventListener('focusin', onFocusIn);
    header.removeEventListener('focusout', onFocusOut);
    window.clearTimeout(focusCheckTimer);
    resetHeader();
  };
}
