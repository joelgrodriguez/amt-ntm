/**
 * Mobile Menu Module
 *
 * Two-level slide-in panel menu. Owns open/close state and L1↔L2 navigation.
 * Markup contract:
 *   - #mobile-menu-toggle              — hamburger button in the site header
 *   - #menu-icon-open / #menu-icon-close — hamburger / X icons swapped via .hidden
 *   - #mobile-menu                     — root <nav>; .is-open class drives visibility
 *   - #mobile-menu .mobile-menu__viewport — clipped panel viewport
 *   - #mobile-menu .mobile-menu__track — flex container moved by exact panel offset
 *   - .mobile-menu__panel              — sibling panels with data-panel="<slug>"
 *   - [data-panel-target="<slug>"]     — L1 row that drills into a panel
 *   - [data-action="back"]             — drills back to root
 *   - [data-action="close"]            — closes the menu from any panel
 *
 * @module MobileMenu
 */

const DESKTOP_BREAKPOINT = 1024;
const RESIZE_DEBOUNCE_MS = 100;

/**
 * Initializes the mobile menu functionality.
 *
 * @returns {Function|undefined} Cleanup function for HMR, or undefined if elements not found.
 */
export function initMobileMenu() {
  const toggle = document.querySelector('#mobile-menu-toggle');
  const menu = document.querySelector('#mobile-menu');
  const iconOpen = document.querySelector('#menu-icon-open');
  const iconClose = document.querySelector('#menu-icon-close');
  const viewport = menu?.querySelector('.mobile-menu__viewport');
  const track = menu?.querySelector('.mobile-menu__track');

  if (!toggle || !menu || !viewport || !track) return;

  /** @type {{ isOpen: boolean, activePanel: string }} */
  const state = { isOpen: false, activePanel: 'root' };

  /** @type {HTMLElement | null} Element to refocus when returning to root. */
  let lastTrigger = null;
  let resizeTimeout = null;

  const panels = menu.querySelectorAll('.mobile-menu__panel');

  /**
   * Positions the track so the active panel starts flush with the viewport.
   * Percent transforms are easy to get subtly wrong here because they resolve
   * against the transformed element's own box, not the clipped viewport.
   */
  const positionTrack = () => {
    // Focus on off-screen panels can change scrollLeft even with overflow hidden.
    // Keep the clip window fixed; panel movement belongs only to the track.
    viewport.scrollLeft = 0;
    const activePanel = [...panels].find((panel) => panel.dataset.panel === state.activePanel);
    const offset = activePanel instanceof HTMLElement ? activePanel.offsetLeft : 0;
    track.style.transform = `translate3d(${-offset}px, 0, 0)`;
    viewport.scrollLeft = 0;
  };

  /**
   * Sync DOM to current state. Idempotent.
   */
  const render = () => {
    // Open/close visibility and aria
    menu.classList.toggle('is-open', state.isOpen);
    menu.setAttribute('aria-hidden', String(!state.isOpen));
    menu.inert = !state.isOpen;
    toggle.setAttribute('aria-expanded', String(state.isOpen));

    // Hamburger icon swap
    iconOpen?.classList.toggle('hidden', state.isOpen);
    iconClose?.classList.toggle('hidden', !state.isOpen);

    // Body scroll lock + main/footer inert
    document.body.classList.toggle('overflow-hidden', state.isOpen);
    const main = document.querySelector('main');
    const footer = document.querySelector('footer');
    if (main) main.inert = state.isOpen;
    if (footer) footer.inert = state.isOpen;

    // Active panel
    track.setAttribute('data-active-panel', state.activePanel);
    positionTrack();
    panels.forEach((panel) => {
      const isActive = panel.dataset.panel === state.activePanel;
      panel.setAttribute('aria-hidden', String(!isActive));
      panel.inert = !isActive;
    });
  };

  const open = () => {
    state.activePanel = 'root';
    lastTrigger = null;
    render();
    // Force the closed/root state to commit before opening, so reopening from
    // an L2 panel can never animate or flash from the previous panel.
    void menu.offsetHeight;
    state.isOpen = true;
    render();
  };

  const close = () => {
    state.isOpen = false;
    state.activePanel = 'root';
    lastTrigger = null;
    render();
  };

  /**
   * @param {string} slug
   * @param {HTMLElement} trigger
   */
  const goToPanel = (slug, trigger) => {
    // Guard: if the markup references a slug with no matching panel, do
    // nothing. Prevents a typo'd data-panel-target from putting the track
    // into an invisible-broken state (active=bogus, no CSS rule, stuck on L1).
    const newPanel = menu.querySelector(`[data-panel="${slug}"]`);
    if (!newPanel) return;

    lastTrigger = trigger;
    state.activePanel = slug;
    render();

    // Move focus to the new panel's back button for keyboard/SR users.
    const backBtn = newPanel.querySelector('[data-action="back"]');
    if (backBtn instanceof HTMLElement) backBtn.focus({ preventScroll: true });
  };

  const goBack = () => {
    state.activePanel = 'root';
    render();
    if (lastTrigger instanceof HTMLElement) {
      lastTrigger.focus({ preventScroll: true });
      lastTrigger = null;
    }
  };

  /**
   * Click delegation inside the menu: drill in / back / close.
   *
   * @param {MouseEvent} e
   */
  const handleMenuClick = (e) => {
    const target = e.target instanceof Element ? e.target : null;
    if (!target) return;

    const drillTrigger = target.closest('[data-panel-target]');
    if (drillTrigger instanceof HTMLElement) {
      const slug = drillTrigger.dataset.panelTarget;
      if (slug) {
        e.preventDefault();
        goToPanel(slug, drillTrigger);
        return;
      }
    }

    const actionEl = target.closest('[data-action]');
    if (actionEl instanceof HTMLElement) {
      const action = actionEl.dataset.action;
      if (action === 'back') {
        e.preventDefault();
        goBack();
      } else if (action === 'close') {
        e.preventDefault();
        close();
        toggle.focus();
      }
    }
  };

  const handleToggleClick = () => {
    const isOpen = menu.classList.contains('is-open');
    if (isOpen) {
      close();
    } else {
      open();
    }
  };

  /**
   * @param {KeyboardEvent} e
   */
  const handleKeydown = (e) => {
    if (e.key === 'Escape' && menu.classList.contains('is-open')) {
      close();
      toggle.focus();
    }
  };

  const handleResize = () => {
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(() => {
      const isOpen = menu.classList.contains('is-open');
      if (window.innerWidth >= DESKTOP_BREAKPOINT && isOpen) {
        close();
      } else if (isOpen) {
        render();
      }
    }, RESIZE_DEBOUNCE_MS);
  };

  // Wire up
  toggle.addEventListener('click', handleToggleClick);
  menu.addEventListener('click', handleMenuClick);
  document.addEventListener('keydown', handleKeydown);
  window.addEventListener('resize', handleResize);

  // Initial render so aria-hidden values are correct on load.
  render();

  return () => {
    toggle.removeEventListener('click', handleToggleClick);
    menu.removeEventListener('click', handleMenuClick);
    document.removeEventListener('keydown', handleKeydown);
    window.removeEventListener('resize', handleResize);
    clearTimeout(resizeTimeout);
  };
}
