/**
 * Mobile Menu Module
 *
 * Full-width mobile menu with header darkening effect.
 *
 * @module MobileMenu
 */

/** @type {number} Breakpoint for desktop view (matches Tailwind lg) */
const DESKTOP_BREAKPOINT = 1024;

/** @type {number} Debounce delay for resize handler in ms */
const RESIZE_DEBOUNCE_MS = 100;

/**
 * Initializes the mobile menu functionality.
 *
 * @returns {Function|undefined} Cleanup function for HMR, or undefined if elements not found.
 */
export function initMobileMenu() {
  const toggle = document.querySelector('#mobile-menu-toggle');
  const menu = document.querySelector('#mobile-menu');
  const header = document.querySelector('#site-header');
  const iconOpen = document.querySelector('#menu-icon-open');
  const iconClose = document.querySelector('#menu-icon-close');

  if (!toggle || !menu || !header) return;

  let isOpen = false;
  let resizeTimeout = null;

  /**
   * Updates DOM state based on isOpen.
   */
  const updateState = () => {
    // Toggle menu visibility
    menu.classList.toggle('is-open', isOpen);

    // Toggle header dark mode
    header.classList.toggle('header-menu-open', isOpen);

    // Toggle header icons
    iconOpen?.classList.toggle('hidden', isOpen);
    iconClose?.classList.toggle('hidden', !isOpen);

    // Update aria state
    toggle.setAttribute('aria-expanded', isOpen);

    // Prevent body scroll when menu is open
    document.body.classList.toggle('overflow-hidden', isOpen);

    // Add inert to main content when menu is open (blocks interactions)
    const main = document.querySelector('main');
    const footer = document.querySelector('footer');
    if (main) main.inert = isOpen;
    if (footer) footer.inert = isOpen;
  };

  /**
   * Opens the menu.
   */
  const open = () => {
    isOpen = true;
    updateState();
  };

  /**
   * Closes the menu.
   */
  const close = () => {
    isOpen = false;
    updateState();
  };

  /**
   * Handles toggle button click.
   */
  const handleClick = () => {
    isOpen ? close() : open();
  };

  /**
   * Handles keydown events for Escape key.
   *
   * @param {KeyboardEvent} e - Keyboard event
   */
  const handleKeydown = (e) => {
    if (e.key === 'Escape' && isOpen) {
      close();
      toggle.focus(); // Return focus to toggle button
    }
  };

  /**
   * Handles window resize with debouncing.
   */
  const handleResize = () => {
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(() => {
      if (window.innerWidth >= DESKTOP_BREAKPOINT && isOpen) {
        close();
      }
    }, RESIZE_DEBOUNCE_MS);
  };

  // Attach event listeners
  toggle.addEventListener('click', handleClick);
  document.addEventListener('keydown', handleKeydown);
  window.addEventListener('resize', handleResize);

  /**
   * Cleanup function to remove event listeners.
   * Used for HMR to prevent memory leaks.
   *
   * @returns {void}
   */
  return () => {
    toggle.removeEventListener('click', handleClick);
    document.removeEventListener('keydown', handleKeydown);
    window.removeEventListener('resize', handleResize);
    clearTimeout(resizeTimeout);
  };
}
