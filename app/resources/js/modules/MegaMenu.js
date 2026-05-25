/**
 * Mega Menu
 *
 * Manages open/close of full-width desktop mega panels, tab switching
 * within panels, overlay, and keyboard navigation.
 *
 * @file MegaMenu.js
 */

/* Motion is owned by .t-panel-slide (transitions.dev / transitions.css).
 * Switch delay matches --panel-close-dur so panel A finishes leaving before
 * panel B starts arriving. Read from a .mega-panel element (not :root) so
 * the mega-panel's local override of --panel-close-dur is honored. */
const readSwitchDelayMs = () => {
    if (typeof window === 'undefined') return 400;
    const panel = document.querySelector('.mega-panel');
    if (!panel) return 400;
    const raw = getComputedStyle(panel).getPropertyValue('--panel-close-dur').trim();
    const num = parseFloat(raw);
    return Number.isFinite(num) ? num : 400;
};

export const initMegaMenu = () => {
    const triggers  = /** @type {NodeListOf<HTMLButtonElement>} */ (document.querySelectorAll('.mega-trigger'));
    const overlay   = document.getElementById('mega-menu-overlay');
    const container = document.getElementById('mega-menu-container');

    if (!triggers.length || !container) {
        return () => {};
    }

    const SWITCH_DELAY_MS = readSwitchDelayMs();

    /** @type {string|null} */
    let activePanel = null;

    /** Pending open scheduled to fire after a close transition finishes. */
    /** @type {{ trigger: HTMLButtonElement, timer: number } | null} */
    let pendingOpen = null;

    /** @param {string} id */
    const getPanel = (id) => document.getElementById(`mega-panel-${id}`);

    /** Apply the open or closed visual + a11y state to a single panel. */
    /** @param {HTMLElement} panel @param {'open'|'closed'} state */
    const setPanelState = (panel, state) => {
        const open = state === 'open';
        panel.classList.toggle('is-open', open);
        panel.classList.toggle('is-closing', !open);
        panel.setAttribute('aria-hidden', open ? 'false' : 'true');
        panel.inert = !open;
    };

    /** Reset chrome (triggers, overlay, body scroll lock) to the closed state. */
    const resetChrome = () => {
        triggers.forEach((t) => t.setAttribute('aria-expanded', 'false'));
        overlay?.classList.remove('is-open');
        document.body.classList.remove('overflow-hidden', 'mega-open');
    };

    const close = () => {
        if (activePanel) {
            const panel = getPanel(activePanel);
            if (panel) setPanelState(panel, 'closed');
        }
        resetChrome();
        activePanel = null;
    };

    /** @param {HTMLButtonElement} trigger */
    const open = (trigger) => {
        const id = trigger.dataset.megaPanel;
        if (!id) return;

        const panel = getPanel(id);
        if (!panel) return;

        // Clear closing state and force reflow so the open transition (and the
        // staggered child entries) start from a clean slate.
        panel.classList.remove('is-closing');
        void panel.offsetHeight;
        setPanelState(panel, 'open');

        trigger.setAttribute('aria-expanded', 'true');
        overlay?.classList.add('is-open');
        document.body.classList.add('overflow-hidden', 'mega-open');
        activePanel = id;
    };

    const cancelPendingOpen = () => {
        if (!pendingOpen) return;
        clearTimeout(pendingOpen.timer);
        pendingOpen = null;
    };

    /** Close the active panel (if any) and queue an open after the close beat. */
    /** @param {HTMLButtonElement} trigger */
    const queueSwitchTo = (trigger) => {
        // If a switch is already in flight, just retarget it — let the existing
        // timer ride so we don't extend the wait on rapid clicks.
        if (pendingOpen) {
            pendingOpen.trigger = trigger;
            return;
        }

        close();
        const timer = window.setTimeout(() => {
            const target = pendingOpen?.trigger;
            pendingOpen = null;
            if (target) open(target);
        }, SWITCH_DELAY_MS);
        pendingOpen = { trigger, timer };
    };

    /** Toggle — same trigger closes; different trigger queues a switch. */
    /** @param {HTMLButtonElement} trigger */
    const toggle = (trigger) => {
        const id = trigger.dataset.megaPanel;
        const sameAsActive  = activePanel === id;
        const sameAsPending = pendingOpen?.trigger === trigger;

        if (sameAsActive || sameAsPending) {
            cancelPendingOpen();
            close();
            return;
        }

        if (activePanel || pendingOpen) {
            queueSwitchTo(trigger);
            return;
        }

        open(trigger);
    };

    const dismiss = () => {
        cancelPendingOpen();
        close();
    };

    /** @param {HTMLButtonElement} tabBtn */
    const switchTab = (tabBtn) => {
        const panel = tabBtn.closest('.mega-panel');
        if (!panel) return;

        const targetId = tabBtn.dataset.tab;

        // Roving tabindex: only the selected tab is focusable.
        panel.querySelectorAll('[role="tab"]').forEach((btn) => {
            const isTarget = /** @type {HTMLButtonElement} */ (btn).dataset.tab === targetId;
            btn.setAttribute('aria-selected', isTarget ? 'true' : 'false');
            btn.setAttribute('tabindex', isTarget ? '0' : '-1');
        });

        // Tabpanel IDs follow the pattern: mega-tabpanel-{panelId}-{tabId}
        panel.querySelectorAll('[role="tabpanel"]').forEach((pane) => {
            const el = /** @type {HTMLElement} */ (pane);
            el.hidden = !el.id.endsWith(`-${targetId}`);
        });
    };

    // ── Event handlers ──────────────────────────────────────────────────

    /** @param {MouseEvent} e */
    const handleTriggerClick = (e) => toggle(/** @type {HTMLButtonElement} */ (e.currentTarget));

    /** @param {MouseEvent} e */
    const handleTabClick = (e) => switchTab(/** @type {HTMLButtonElement} */ (e.currentTarget));

    /** Arrow key navigation within a tablist (ARIA APG requirement). */
    /** @param {KeyboardEvent} e */
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

    /** @param {KeyboardEvent} e */
    const handleKeydown = (e) => {
        if (e.key !== 'Escape' || !activePanel) return;
        const triggerSelector = `.mega-trigger[data-mega-panel="${activePanel}"]`;
        dismiss();
        /** @type {HTMLButtonElement|null} */
        (document.querySelector(triggerSelector))?.focus();
    };

    /** Close on any click that isn't on an interactive piece of content.
     * Triggers handle themselves (toggle); links/buttons/tabs inside the panel
     * are real content and don't close. Everything else — panel background,
     * sidebar gutter, scrim — closes. */
    /** @param {MouseEvent} e */
    const handleDocClick = (e) => {
        if (!activePanel) return;
        const target = /** @type {Element} */ (e.target);
        if (Array.from(triggers).some((t) => t.contains(target))) return;
        if (target.closest?.('a, button, [role="tab"], input, select, textarea, label')) return;
        dismiss();
    };

    // ── Wire up ─────────────────────────────────────────────────────────

    triggers.forEach((t) => t.addEventListener('click', handleTriggerClick));
    overlay?.addEventListener('click', dismiss);
    document.addEventListener('keydown', handleKeydown);
    document.addEventListener('click', handleDocClick);

    const tabBtns = /** @type {NodeListOf<HTMLButtonElement>} */ (
        container.querySelectorAll('[role="tab"]')
    );

    // Init roving tabindex per tablist: first tab is 0, siblings -1.
    container.querySelectorAll('[role="tablist"]').forEach((tablist) => {
        tablist.querySelectorAll('[role="tab"]').forEach((btn, i) => {
            btn.setAttribute('tabindex', i === 0 ? '0' : '-1');
        });
    });

    tabBtns.forEach((btn) => {
        btn.addEventListener('click', handleTabClick);
        btn.addEventListener('keydown', handleTabKeydown);
    });

    return () => {
        cancelPendingOpen();
        triggers.forEach((t) => t.removeEventListener('click', handleTriggerClick));
        overlay?.removeEventListener('click', dismiss);
        document.removeEventListener('keydown', handleKeydown);
        document.removeEventListener('click', handleDocClick);
        tabBtns.forEach((btn) => {
            btn.removeEventListener('click', handleTabClick);
            btn.removeEventListener('keydown', handleTabKeydown);
        });
    };
};
