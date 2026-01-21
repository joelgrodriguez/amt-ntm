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

  /**
   * Update button visibility based on scroll position
   */
  function updateButton() {
    if (window.scrollY > SCROLL_THRESHOLD) {
      button.classList.remove('opacity-0', 'pointer-events-none');
      button.classList.add('opacity-100');
    } else {
      button.classList.add('opacity-0', 'pointer-events-none');
      button.classList.remove('opacity-100');
    }
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

  // Initial state
  updateButton();

  // Add event listeners
  window.addEventListener('scroll', onScroll, { passive: true });
  button.addEventListener('click', scrollToTop);

  // Return cleanup function
  return () => {
    window.removeEventListener('scroll', onScroll);
    button.removeEventListener('click', scrollToTop);
  };
}
