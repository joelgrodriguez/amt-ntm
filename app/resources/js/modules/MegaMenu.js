/**
 * Mega Menu
 *
 * Manages open/close of full-width desktop mega panels. This is a
 * navigation disclosure, not a dialog: the page stays scrollable, the
 * scrim is a soft veil (not a modal lock), and focus management only
 * engages when the panel was opened by keyboard.
 *
 * @file MegaMenu.js
 */

/** Fade duration for the outgoing panel when switching triggers. Kept short
 *  so the menu stays responsive; matches --mega-switch-fade in the CSS. */
const MEGA_SWITCH_FADE_MS = 140;

/**
 * Wire the category tab-list inside one tabbed-machines panel. Clicking a
 * tab (or arrowing onto it) reveals its panel and hides the siblings. Tabs
 * are a roving-tabindex group: only the active tab is in the tab order, and
 * Arrow/Home/End move selection between them.
 *
 * @param {HTMLElement} panel
 * @returns {() => void} teardown
 */
const initMegaTabs = (panel) => {
    /** @type {HTMLButtonElement[]} */
    const tabs = Array.from(panel.querySelectorAll('.mega-tab'));
    if (tabs.length < 2) return () => {};

    /** The wrapper that holds the tab panels; carries data-dir for the
     *  slide direction so the incoming panel travels with the click. */
    const content = panel.querySelector('.mega-tab-content');

    /** Index of the currently active tab, so we can tell forward from back. */
    let activeIndex = Math.max(0, tabs.findIndex((t) => t.getAttribute('aria-selected') === 'true'));

    /** @param {HTMLButtonElement} tab @param {{ focus?: boolean }} [opts] */
    const select = (tab, { focus = false } = {}) => {
        const nextIndex = tabs.indexOf(tab);
        if (nextIndex === -1 || nextIndex === activeIndex) {
            if (focus) tab.focus();
            return;
        }

        // Slide direction follows the sidebar: a later tab enters from the
        // right (forward), an earlier one from the left (back).
        if (content) {
            content.setAttribute('data-dir', nextIndex > activeIndex ? 'forward' : 'back');
        }

        tabs.forEach((t) => {
            const active = t === tab;
            t.setAttribute('aria-selected', String(active));
            t.tabIndex = active ? 0 : -1;
            const panelId = t.getAttribute('aria-controls');
            const tabPanel = panelId ? document.getElementById(panelId) : null;
            if (!tabPanel) return;
            // Toggling .is-active off→on re-fires the CSS card-stagger
            // animation; aria-hidden keeps the inactive panels out of the
            // a11y tree without display:none (which would kill the slide).
            tabPanel.classList.toggle('is-active', active);
            tabPanel.setAttribute('aria-hidden', active ? 'false' : 'true');
        });

        activeIndex = nextIndex;
        if (focus) tab.focus();
    };

    /** @param {MouseEvent} e */
    const handleClick = (e) => select(/** @type {HTMLButtonElement} */ (e.currentTarget));

    /** @param {KeyboardEvent} e */
    const handleKeydown = (e) => {
        const i = tabs.indexOf(/** @type {HTMLButtonElement} */ (e.currentTarget));
        if (i === -1) return;

        let next = -1;
        if (e.key === 'ArrowDown' || e.key === 'ArrowRight') next = (i + 1) % tabs.length;
        else if (e.key === 'ArrowUp' || e.key === 'ArrowLeft') next = (i - 1 + tabs.length) % tabs.length;
        else if (e.key === 'Home') next = 0;
        else if (e.key === 'End') next = tabs.length - 1;

        if (next !== -1) {
            e.preventDefault();
            select(tabs[next], { focus: true });
        }
    };

    tabs.forEach((t) => {
        t.addEventListener('click', handleClick);
        t.addEventListener('keydown', handleKeydown);
    });

    return () => {
        tabs.forEach((t) => {
            t.removeEventListener('click', handleClick);
            t.removeEventListener('keydown', handleKeydown);
        });
    };
};

export const initMegaMenu = () => {
    const triggers  = /** @type {NodeListOf<HTMLButtonElement>} */ (document.querySelectorAll('.mega-trigger'));
    const overlay   = document.getElementById('mega-menu-overlay');
    const container = document.getElementById('mega-menu-container');

    if (!triggers.length || !container) {
        return () => {};
    }

    // Wire category tabs inside every panel that has them.
    const tabTeardowns = /** @type {HTMLElement[]} */ (
        Array.from(container.querySelectorAll('.mega-panel'))
    ).map(initMegaTabs);

    /** Tabbable selector for keyboard focus trap. */
    const FOCUSABLE_SELECTOR = [
        'a[href]',
        'button:not([disabled])',
        'input:not([disabled]):not([type="hidden"])',
        'select:not([disabled])',
        'textarea:not([disabled])',
        '[tabindex]:not([tabindex="-1"])',
    ].join(',');

    /** @type {string|null} */
    let activePanel = null;

    /** True only when the active panel was opened by keyboard (Enter/Space
     *  on the trigger). Mouse opens leave this false so we don't yank the
     *  user's pointer-driven scan focus into the panel. */
    let isKeyboardOpen = false;

    /** @param {string} id */
    const getPanel = (id) => document.getElementById(`mega-panel-${id}`);

    /** @param {HTMLElement} panel */
    const getTabbable = (panel) =>
        /** @type {HTMLElement[]} */ (Array.from(panel.querySelectorAll(FOCUSABLE_SELECTOR)))
            .filter((el) => {
                if (el.hasAttribute('hidden') || el.offsetParent === null) return false;
                // Inactive tab panels stay in the DOM (opacity:0, out of flow)
                // so they can slide; exclude their contents from the focus trap.
                const tabPanel = el.closest('.mega-tab-panel');
                return !tabPanel || tabPanel.classList.contains('is-active');
            });

    /** @param {HTMLElement} panel @param {'open'|'closed'} state */
    const setPanelState = (panel, state) => {
        const open = state === 'open';
        panel.classList.toggle('is-open', open);
        panel.classList.toggle('is-closing', !open);
        panel.setAttribute('aria-hidden', open ? 'false' : 'true');
        panel.inert = !open;
    };

    const resetChrome = () => {
        triggers.forEach((t) => t.setAttribute('aria-expanded', 'false'));
        overlay?.classList.remove('is-open');
        document.body.classList.remove('overflow-hidden', 'mega-open');
    };

    const restoreFocusToActiveTrigger = () => {
        if (!activePanel) return;
        const sel = `.mega-trigger[data-mega-panel="${activePanel}"]`;
        /** @type {HTMLButtonElement|null} */
        (document.querySelector(sel))?.focus({ preventScroll: true });
    };

    const close = ({ restoreFocus = false } = {}) => {
        if (activePanel) {
            const panel = getPanel(activePanel);
            if (panel) setPanelState(panel, 'closed');
            if (restoreFocus) restoreFocusToActiveTrigger();
        }
        resetChrome();
        activePanel = null;
        isKeyboardOpen = false;
    };

    /** @param {HTMLButtonElement} trigger @param {{ fromKeyboard?: boolean }} [opts] */
    const open = (trigger, { fromKeyboard = false } = {}) => {
        const id = trigger.dataset.megaPanel;
        if (!id) return;

        const panel = getPanel(id);
        if (!panel) return;

        // Switching from another open panel: cross-fade rather than hard-cut.
        // The outgoing panel fades out in place (.is-switching kills its
        // slide-up exit) while the new one drops in — two competing vertical
        // slides read as jagged; a fade under the incoming drop reads smooth.
        if (activePanel && activePanel !== id) {
            const prev = getPanel(activePanel);
            if (prev) {
                prev.classList.add('is-switching');
                setPanelState(prev, 'closed');
                window.setTimeout(() => prev.classList.remove('is-switching'), MEGA_SWITCH_FADE_MS);
            }
        }

        panel.classList.remove('is-closing');
        void panel.offsetHeight;
        setPanelState(panel, 'open');

        trigger.setAttribute('aria-expanded', 'true');
        triggers.forEach((t) => {
            if (t !== trigger) t.setAttribute('aria-expanded', 'false');
        });
        overlay?.classList.add('is-open');
        document.body.classList.add('overflow-hidden', 'mega-open');
        activePanel = id;
        isKeyboardOpen = fromKeyboard;

        // Only move focus into the panel when the trigger was activated
        // by keyboard. Mouse users keep their cursor-driven flow.
        if (fromKeyboard) {
            const first = getTabbable(panel)[0];
            if (first) {
                first.focus({ preventScroll: true });
            } else {
                panel.setAttribute('tabindex', '-1');
                panel.focus({ preventScroll: true });
            }
        }
    };

    /** @param {HTMLButtonElement} trigger @param {{ fromKeyboard?: boolean }} [opts] */
    const toggle = (trigger, opts = {}) => {
        const id = trigger.dataset.megaPanel;
        if (activePanel === id) {
            close();
            return;
        }
        open(trigger, opts);
    };

    /** @param {{ restoreFocus?: boolean }} [opts] */
    const dismiss = (opts = {}) => close(opts);

    // ── Event handlers ──────────────────────────────────────────────────

    /** @param {MouseEvent} e */
    const handleTriggerClick = (e) => {
        // detail === 0 fires when a button is activated by keyboard
        // (Enter / Space), not mouse. That's our modality signal.
        const fromKeyboard = e.detail === 0;
        toggle(/** @type {HTMLButtonElement} */ (e.currentTarget), { fromKeyboard });
    };

    /** @param {KeyboardEvent} e */
    const handleKeydown = (e) => {
        if (!activePanel) return;

        if (e.key === 'Escape') {
            dismiss({ restoreFocus: true });
            return;
        }

        // Focus trap engages only for keyboard-opened panels. Mouse users
        // never had focus moved in, so trapping is the wrong fix here.
        if (e.key === 'Tab' && isKeyboardOpen) {
            const panel = getPanel(activePanel);
            if (!panel) return;
            const tabbables = getTabbable(panel);
            if (tabbables.length === 0) {
                e.preventDefault();
                return;
            }
            const first = tabbables[0];
            const last  = tabbables[tabbables.length - 1];
            const active = document.activeElement;
            if (e.shiftKey && active === first) {
                e.preventDefault();
                last.focus();
            } else if (!e.shiftKey && active === last) {
                e.preventDefault();
                first.focus();
            }
        }
    };

    /** Close on any click outside an interactive piece of content. */
    /** @param {MouseEvent} e */
    const handleDocClick = (e) => {
        if (!activePanel) return;
        const target = /** @type {Element} */ (e.target);
        if (Array.from(triggers).some((t) => t.contains(target))) return;
        if (target.closest?.('a, button, input, select, textarea, label')) return;
        dismiss();
    };

    const handleOverlayClick = () => dismiss();

    /** Close on bfcache restore + history navigation. Browsers persist DOM
     *  state across back/forward, which would otherwise re-show the panel
     *  in whatever state the user left it. */
    /** @param {PageTransitionEvent} e */
    const handlePageShow = (e) => {
        if (e.persisted && activePanel) dismiss();
    };
    const handlePopState = () => {
        if (activePanel) dismiss();
    };

    triggers.forEach((t) => t.addEventListener('click', handleTriggerClick));
    overlay?.addEventListener('click', handleOverlayClick);
    document.addEventListener('keydown', handleKeydown);
    document.addEventListener('click', handleDocClick);
    window.addEventListener('pageshow', handlePageShow);
    window.addEventListener('popstate', handlePopState);

    return () => {
        triggers.forEach((t) => t.removeEventListener('click', handleTriggerClick));
        overlay?.removeEventListener('click', handleOverlayClick);
        document.removeEventListener('keydown', handleKeydown);
        document.removeEventListener('click', handleDocClick);
        window.removeEventListener('pageshow', handlePageShow);
        window.removeEventListener('popstate', handlePopState);
        tabTeardowns.forEach((teardown) => teardown());
    };
};
