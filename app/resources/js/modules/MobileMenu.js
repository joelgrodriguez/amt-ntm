/**
 * Mobile Menu Module
 *
 * Two-level slide-in panel menu. Owns open/close state and L1↔L2 navigation.
 * Markup contract:
 *   - #mobile-menu-toggle              — hamburger button in the site header
 *   - #mobile-menu                     — root <nav>; .is-open class drives visibility
 *   - #mobile-menu .mobile-menu__track — flex container; data-active-panel="<slug>"
 *   - .mobile-menu__panel              — sibling panels with data-panel="<slug>"
 *   - [data-panel-target="<slug>"]     — L1 row that drills into a panel
 *   - [data-action="back"]             — drills back to root
 *   - [data-action="close"]            — closes the menu from any panel
 *
 * @module MobileMenu
 */

const DESKTOP_BREAKPOINT = 1024;
const RESIZE_DEBOUNCE_MS = 100;
/** Match the .mobile-menu transition duration in mobile-menu.css */
const CLOSE_RESET_DELAY_MS = 220;

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
    const track = menu?.querySelector('.mobile-menu__track');

    if (!toggle || !menu || !header || !track) return;

    /** @type {{ isOpen: boolean, activePanel: string }} */
    const state = { isOpen: false, activePanel: 'root' };

    /** @type {HTMLElement | null} Element to refocus when returning to root. */
    let lastTrigger = null;
    let resizeTimeout = null;
    let closeResetTimeout = null;

    const panels = menu.querySelectorAll('.mobile-menu__panel');

    /**
     * Sync DOM to current state. Idempotent.
     */
    const render = () => {
        // Open/close visibility and aria
        menu.classList.toggle('is-open', state.isOpen);
        menu.setAttribute('aria-hidden', String(!state.isOpen));
        toggle.setAttribute('aria-expanded', String(state.isOpen));

        // Header darken
        header.classList.toggle('header-menu-open', state.isOpen);

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
        panels.forEach((panel) => {
            const isActive = panel.dataset.panel === state.activePanel;
            panel.setAttribute('aria-hidden', String(!isActive));
        });
    };

    const open = () => {
        clearTimeout(closeResetTimeout);
        state.isOpen = true;
        render();
    };

    const close = () => {
        state.isOpen = false;
        render();
        // Reset to root after the close transition completes so the rewind
        // isn't visible to the user.
        clearTimeout(closeResetTimeout);
        closeResetTimeout = setTimeout(() => {
            state.activePanel = 'root';
            lastTrigger = null;
            render();
        }, CLOSE_RESET_DELAY_MS);
    };

    /**
     * @param {string} slug
     * @param {HTMLElement} trigger
     */
    const goToPanel = (slug, trigger) => {
        lastTrigger = trigger;
        state.activePanel = slug;
        render();
        // Move focus to the new panel's back button for keyboard/SR users.
        const newPanel = menu.querySelector(`[data-panel="${slug}"]`);
        const backBtn = newPanel?.querySelector('[data-action="back"]');
        if (backBtn instanceof HTMLElement) backBtn.focus();
    };

    const goBack = () => {
        state.activePanel = 'root';
        render();
        if (lastTrigger instanceof HTMLElement) {
            lastTrigger.focus();
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
        state.isOpen ? close() : open();
    };

    /**
     * @param {KeyboardEvent} e
     */
    const handleKeydown = (e) => {
        if (e.key === 'Escape' && state.isOpen) {
            close();
            toggle.focus();
        }
    };

    const handleResize = () => {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(() => {
            if (window.innerWidth >= DESKTOP_BREAKPOINT && state.isOpen) {
                close();
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
        clearTimeout(closeResetTimeout);
    };
}
