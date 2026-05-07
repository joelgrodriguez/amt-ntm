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

    /** Pending open scheduled to fire after a close transition finishes. */
    /** @type {{ trigger: HTMLButtonElement, timer: number } | null} */
    let pendingOpen = null;

    /** Matches the close transform duration in layout/mega-menu.css (320ms). */
    const CLOSE_DURATION_MS = 320;

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
            if (panel) {
                panel.classList.add('is-closing');
                panel.classList.remove('is-open');
                panel.setAttribute('aria-hidden', 'true');
                panel.inert = true;
            }
        }

        triggers.forEach((t) => t.setAttribute('aria-expanded', 'false'));
        overlay?.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        activePanel = null;
    };

    /**
     * Open the panel for the given trigger button. Assumes no panel is currently open —
     * call close() first and wait if switching between panels.
     * @param {HTMLButtonElement} trigger
     */
    const open = (trigger) => {
        const id = trigger.dataset.megaPanel;
        if (!id) return;

        const panel = getPanel(id);
        if (!panel) return;

        // Remove is-closing so stagger delays fire fresh, force reflow,
        // then add is-open to trigger the CSS transition — same as mobile menu.
        panel.classList.remove('is-closing');
        panel.setAttribute('aria-hidden', 'false');
        panel.inert = false;
        void panel.offsetHeight; // reflow — commits start state before transition
        panel.classList.add('is-open');

        trigger.setAttribute('aria-expanded', 'true');
        overlay?.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        activePanel = id;
    };

    /** Cancel any queued open (e.g. user clicked a third trigger or closed everything). */
    const cancelPendingOpen = () => {
        if (pendingOpen) {
            clearTimeout(pendingOpen.timer);
            pendingOpen = null;
        }
    };

    /**
     * Toggle — clicking the same trigger closes it. When switching between
     * panels, the current panel fully closes before the next one opens so the
     * transitions don't overlap.
     * @param {HTMLButtonElement} trigger
     */
    const toggle = (trigger) => {
        const id = trigger.dataset.megaPanel;

        // Same trigger clicked while it's open (or its open is pending) → close everything.
        if (activePanel === id || pendingOpen?.trigger === trigger) {
            cancelPendingOpen();
            close();
            return;
        }

        // Switching panels: ensure the current/closing panel fully finishes before opening
        // the new one. If a close is already mid-flight (pendingOpen set), just retarget it.
        if (activePanel) {
            close();
            cancelPendingOpen();
            const timer = window.setTimeout(() => {
                const target = pendingOpen?.trigger;
                pendingOpen = null;
                if (target) open(target);
            }, CLOSE_DURATION_MS);
            pendingOpen = { trigger, timer };
            return;
        }

        // A close is already in flight — let it finish and just retarget the queued open.
        if (pendingOpen) {
            pendingOpen.trigger = trigger;
            return;
        }

        open(trigger);
    };

    /**
     * Switch to the given tab within the currently open panel.
     * @param {HTMLButtonElement} tabBtn
     */
    const switchTab = (tabBtn) => {
        const panel = tabBtn.closest('.mega-panel');
        if (!panel) return;

        const targetId = tabBtn.dataset.tab;

        // Update tab button states and tabindex (tablist pattern: only selected tab is focusable)
        panel.querySelectorAll('[role="tab"]').forEach((btn) => {
            const isTarget = /** @type {HTMLButtonElement} */ (btn).dataset.tab === targetId;
            btn.setAttribute('aria-selected', isTarget ? 'true' : 'false');
            btn.setAttribute('tabindex', isTarget ? '0' : '-1');
        });

        // Show/hide tab panels — IDs follow the pattern: mega-tabpanel-{panelId}-{tabId}
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

    /** Arrow key navigation within a tablist (ARIA APG requirement).
     * @param {KeyboardEvent} e */
    const handleTabKeydown = (e) => {
        if (e.key !== 'ArrowRight' && e.key !== 'ArrowLeft') return;
        const tabList = /** @type {HTMLElement|null} */ (/** @type {HTMLElement} */ (e.currentTarget).closest('[role="tablist"]'));
        if (!tabList) return;
        const tabs = /** @type {HTMLButtonElement[]} */ (Array.from(tabList.querySelectorAll('[role="tab"]')));
        const idx  = tabs.indexOf(/** @type {HTMLButtonElement} */ (e.currentTarget));
        const next = e.key === 'ArrowRight'
            ? tabs[(idx + 1) % tabs.length]
            : tabs[(idx - 1 + tabs.length) % tabs.length];
        if (next) {
            next.focus();
            switchTab(next);
        }
    };

    const handleOverlayClick = () => {
        cancelPendingOpen();
        close();
    };

    /** @param {KeyboardEvent} e */
    const handleKeydown = (e) => {
        if (e.key === 'Escape' && activePanel) {
            const closingPanel = activePanel;
            cancelPendingOpen();
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
        if (!insidePanel && !insideTrigger) {
            cancelPendingOpen();
            close();
        }
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

    // Init tabindex per tablist: first tab is 0, siblings are -1 (ARIA roving tabindex)
    container.querySelectorAll('[role="tablist"]').forEach((tablist) => {
        tablist.querySelectorAll('[role="tab"]').forEach((btn, i) => {
            btn.setAttribute('tabindex', i === 0 ? '0' : '-1');
        });
    });

    tabBtns.forEach((btn) => {
        btn.addEventListener('click', handleTabClick);
        btn.addEventListener('keydown', handleTabKeydown);
    });

    // ── Cleanup ─────────────────────────────────────────────────────────

    return () => {
        cancelPendingOpen();
        triggers.forEach((t) => t.removeEventListener('click', handleTriggerClick));
        overlay?.removeEventListener('click', handleOverlayClick);
        document.removeEventListener('keydown', handleKeydown);
        document.removeEventListener('click', handleDocClick);
        tabBtns.forEach((btn) => {
            btn.removeEventListener('click', handleTabClick);
            btn.removeEventListener('keydown', handleTabKeydown);
        });
    };
};
