/**
 * Mega Menu
 *
 * Manages open/close of full-width desktop mega panels, tab switching
 * within panels, overlay, and keyboard navigation.
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

    /** @type {string|null} */
    let activePanel = null;

    /**
     * Returns the panel element for a given id, or null.
     * @param {string} id
     * @returns {HTMLElement|null}
     */
    const getPanel = (id) => document.getElementById(`mega-panel-${id}`);

    /**
     * Close the active panel and reset all triggers.
     */
    const close = () => {
        if (activePanel) {
            const panel = getPanel(activePanel);
            if (panel) panel.hidden = true;
        }

        triggers.forEach((t) => t.setAttribute('aria-expanded', 'false'));
        overlay?.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        activePanel = null;
    };

    /**
     * Open the panel for the given trigger button.
     * @param {HTMLButtonElement} trigger
     */
    const open = (trigger) => {
        const id = trigger.dataset.megaPanel;
        if (!id) return;

        // Close any currently open panel first
        close();

        const panel = getPanel(id);
        if (!panel) return;

        panel.hidden = false;
        trigger.setAttribute('aria-expanded', 'true');
        overlay?.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        activePanel = id;
    };

    /**
     * Toggle — clicking the same trigger closes it.
     * @param {HTMLButtonElement} trigger
     */
    const toggle = (trigger) => {
        const id = trigger.dataset.megaPanel;
        if (activePanel === id) {
            close();
        } else {
            open(trigger);
        }
    };

    /**
     * Switch to the given tab within the currently open panel.
     * @param {HTMLButtonElement} tabBtn
     */
    const switchTab = (tabBtn) => {
        const panel = tabBtn.closest('.mega-panel');
        if (!panel) return;

        const targetId = tabBtn.dataset.tab;

        // Update tab button states
        panel.querySelectorAll('[role="tab"]').forEach((btn) => {
            const isTarget = /** @type {HTMLButtonElement} */ (btn).dataset.tab === targetId;
            btn.setAttribute('aria-selected', isTarget ? 'true' : 'false');
        });

        // Show/hide tab panels
        panel.querySelectorAll('[role="tabpanel"]').forEach((pane) => {
            const el = /** @type {HTMLElement} */ (pane);
            el.hidden = !el.id.endsWith(`-${targetId}`);
        });
    };

    // ── Event handlers ──────────────────────────────────────────────────

    /** @param {MouseEvent} e */
    const handleTriggerClick = (e) => {
        const trigger = /** @type {HTMLButtonElement} */ (e.currentTarget);
        toggle(trigger);
    };

    /** @param {MouseEvent} e */
    const handleTabClick = (e) => {
        const btn = /** @type {HTMLButtonElement} */ (e.currentTarget);
        switchTab(btn);
    };

    const handleOverlayClick = () => close();

    /** @param {KeyboardEvent} e */
    const handleKeydown = (e) => {
        if (e.key === 'Escape' && activePanel) {
            const closingPanel = activePanel;
            close();
            // Return focus to the trigger that opened the panel
            const trigger = /** @type {HTMLButtonElement|null} */ (
                document.querySelector(`.mega-trigger[data-mega-panel="${closingPanel}"]`)
            );
            trigger?.focus();
        }
    };

    // ── Click outside to close ──────────────────────────────────────────

    /** @param {MouseEvent} e */
    const handleDocClick = (e) => {
        if (!activePanel) return;
        const target = /** @type {Node} */ (e.target);
        const insidePanel   = getPanel(activePanel)?.contains(target);
        const insideTrigger = Array.from(triggers).some((t) => t.contains(target));
        if (!insidePanel && !insideTrigger) close();
    };

    // ── Wire up ─────────────────────────────────────────────────────────

    triggers.forEach((t) => t.addEventListener('click', handleTriggerClick));
    overlay?.addEventListener('click', handleOverlayClick);
    document.addEventListener('keydown', handleKeydown);
    document.addEventListener('click', handleDocClick);

    // Tab buttons inside panels
    const tabBtns = /** @type {NodeListOf<HTMLButtonElement>} */ (
        container.querySelectorAll('[role="tab"]')
    );
    tabBtns.forEach((btn) => btn.addEventListener('click', handleTabClick));

    // ── Cleanup ─────────────────────────────────────────────────────────

    return () => {
        triggers.forEach((t) => t.removeEventListener('click', handleTriggerClick));
        overlay?.removeEventListener('click', handleOverlayClick);
        document.removeEventListener('keydown', handleKeydown);
        document.removeEventListener('click', handleDocClick);
        tabBtns.forEach((btn) => btn.removeEventListener('click', handleTabClick));
    };
};
