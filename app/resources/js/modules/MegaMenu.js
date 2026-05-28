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

export const initMegaMenu = () => {
    const triggers  = /** @type {NodeListOf<HTMLButtonElement>} */ (document.querySelectorAll('.mega-trigger'));
    const overlay   = document.getElementById('mega-menu-overlay');
    const container = document.getElementById('mega-menu-container');

    if (!triggers.length || !container) {
        return () => {};
    }

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
            .filter((el) => !el.hasAttribute('hidden') && el.offsetParent !== null);

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
        document.body.classList.remove('mega-open');
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

        // Hard-close any panel still on screen so trigger-to-trigger
        // switching is instant. No timed close-then-open dance.
        if (activePanel && activePanel !== id) {
            const prev = getPanel(activePanel);
            if (prev) setPanelState(prev, 'closed');
        }

        panel.classList.remove('is-closing');
        void panel.offsetHeight;
        setPanelState(panel, 'open');

        trigger.setAttribute('aria-expanded', 'true');
        triggers.forEach((t) => {
            if (t !== trigger) t.setAttribute('aria-expanded', 'false');
        });
        overlay?.classList.add('is-open');
        document.body.classList.add('mega-open');
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

    triggers.forEach((t) => t.addEventListener('click', handleTriggerClick));
    overlay?.addEventListener('click', handleOverlayClick);
    document.addEventListener('keydown', handleKeydown);
    document.addEventListener('click', handleDocClick);

    return () => {
        triggers.forEach((t) => t.removeEventListener('click', handleTriggerClick));
        overlay?.removeEventListener('click', handleOverlayClick);
        document.removeEventListener('keydown', handleKeydown);
        document.removeEventListener('click', handleDocClick);
    };
};
