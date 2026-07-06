/**
 * ScrollToTop Module
 *
 * Adds a scroll-to-top button that appears after scrolling down.
 * Button is fixed to bottom-right corner with smooth scroll behavior.
 *
 * @module ScrollToTop
 */

const SCROLL_THRESHOLD = 300; // Pixels before button appears

/**
 * Initialize scroll-to-top button
 * @returns {Function} Cleanup function
 */
export function init() {
  const button = document.getElementById('scroll-to-top');

  if (!button) {
    return () => {};
  }

  let ticking = false;

  function setButtonVisible(isVisible) {
    button.classList.toggle('opacity-0', !isVisible);
    button.classList.toggle('pointer-events-none', !isVisible);
    button.classList.toggle('opacity-100', isVisible);
    button.inert = !isVisible;
  }

  /**
   * Update button visibility based on scroll position
   */
  function updateButton() {
    const megaMenuOpen = document.body.classList.contains('mega-open');
    setButtonVisible(window.scrollY > SCROLL_THRESHOLD && !megaMenuOpen);
    ticking = false;
  }

  /**
   * Handle scroll event with requestAnimationFrame
   */
  function onScroll() {
    if (!ticking) {
      requestAnimationFrame(updateButton);
      ticking = true;
    }
  }

  /**
   * Scroll to top smoothly
   */
  function scrollToTop(e) {
    e.preventDefault();
    window.scrollTo({
      top: 0,
      behavior: 'smooth',
    });
  }
  updateButton();
  const bodyObserver = new MutationObserver(updateButton);
  bodyObserver.observe(document.body, { attributes: true, attributeFilter: ['class'] });
  window.addEventListener('scroll', onScroll, { passive: true });
  button.addEventListener('click', scrollToTop);
  return () => {
    bodyObserver.disconnect();
    window.removeEventListener('scroll', onScroll);
    button.removeEventListener('click', scrollToTop);
    button.inert = true;
  };
}
