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
    const handleTriggerEnter = (e) => {
        const trigger = /** @type {HTMLButtonElement} */ (e.currentTarget);
        open(trigger);
    };

    // Shared leave handler — close only when pointer has left both triggers and all panels
    let leaveTimer = /** @type {ReturnType<typeof setTimeout>|null} */ (null);

    const scheduleClose = () => {
        leaveTimer = setTimeout(close, 100);
    };

    const cancelClose = () => {
        if (leaveTimer !== null) {
            clearTimeout(leaveTimer);
            leaveTimer = null;
        }
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

    triggers.forEach((t) => {
        t.addEventListener('click', handleTriggerClick);
        t.addEventListener('mouseenter', handleTriggerEnter);
        t.addEventListener('mouseleave', scheduleClose);
    });

    // Keep panel open while pointer is inside it; close on leave
    container.querySelectorAll('.mega-panel').forEach((panel) => {
        panel.addEventListener('mouseenter', cancelClose);
        panel.addEventListener('mouseleave', scheduleClose);
    });

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
        triggers.forEach((t) => {
            t.removeEventListener('click', handleTriggerClick);
            t.removeEventListener('mouseenter', handleTriggerEnter);
            t.removeEventListener('mouseleave', scheduleClose);
        });
        container.querySelectorAll('.mega-panel').forEach((panel) => {
            panel.removeEventListener('mouseenter', cancelClose);
            panel.removeEventListener('mouseleave', scheduleClose);
        });
        overlay?.removeEventListener('click', handleOverlayClick);
        document.removeEventListener('keydown', handleKeydown);
        document.removeEventListener('click', handleDocClick);
        tabBtns.forEach((btn) => {
            btn.removeEventListener('click', handleTabClick);
            btn.removeEventListener('keydown', handleTabKeydown);
        });
        cancelClose();
    };
};
