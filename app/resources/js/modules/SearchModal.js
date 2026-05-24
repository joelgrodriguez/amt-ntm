/**
 * Header search modal.
 *
 * Wires the native <dialog> open/close, the content-type chip group,
 * the clear-text affordance, the helper-text swap, and the global
 * keyboard shortcuts (`/` and Cmd/Ctrl+K).
 */

const isFormControl = (element) =>
  element instanceof HTMLInputElement ||
  element instanceof HTMLTextAreaElement ||
  element instanceof HTMLSelectElement ||
  (element instanceof HTMLElement && element.isContentEditable);

export const initSearchModal = () => {
  const modal = document.querySelector('#site-search-modal');
  const openButtons = document.querySelectorAll('[data-search-modal-open]');

  if (!(modal instanceof HTMLDialogElement) || openButtons.length === 0) {
    return null;
  }

  const closeButtons = modal.querySelectorAll('[data-search-modal-close]');
  const input = modal.querySelector('[data-search-modal-input]');
  const clearButton = modal.querySelector('[data-search-modal-clear]');
  const chipGroup = modal.querySelector('[data-search-modal-chips]');
  const chips = chipGroup ? Array.from(chipGroup.querySelectorAll('[data-search-modal-chip]')) : [];
  const postTypeInput = modal.querySelector('[data-search-modal-post-type]');
  let activeTrigger = null;
  let closeTimer = null;

  // Read the panel close duration from CSS so JS stays in sync with the
  // transitions.dev token. Defaults match _root.css.
  const readDurationMs = (varName, fallback) => {
    const raw = getComputedStyle(document.documentElement).getPropertyValue(varName).trim();
    if (raw === '') {
      return fallback;
    }
    const parsed = parseFloat(raw);
    if (Number.isNaN(parsed)) {
      return fallback;
    }
    return raw.endsWith('ms') ? parsed : parsed * 1000;
  };
  const panelCloseMs = readDurationMs('--panel-close-dur', 350);

  const syncPostTypeInput = (value) => {
    if (!(postTypeInput instanceof HTMLInputElement)) {
      return;
    }
    postTypeInput.value = value;
    // Disabled when "All" is chosen so the query string stays clean.
    postTypeInput.disabled = value === '';
  };

  const setActiveChip = (value) => {
    chips.forEach((chip) => {
      const isActive = chip.dataset.value === value;
      chip.setAttribute('aria-pressed', isActive ? 'true' : 'false');
    });
    syncPostTypeInput(value);
  };

  const updateClearVisibility = () => {
    if (!(input instanceof HTMLInputElement) || !(clearButton instanceof HTMLElement)) {
      return;
    }
    clearButton.hidden = input.value.trim() === '';
  };

  const openModal = (event) => {
    if (event) {
      event.preventDefault();
      activeTrigger = event.currentTarget instanceof HTMLElement ? event.currentTarget : null;
    }

    if (closeTimer !== null) {
      window.clearTimeout(closeTimer);
      closeTimer = null;
    }

    if (!modal.open) {
      modal.showModal();
    }

    document.body.classList.add('search-modal-is-open');
    updateClearVisibility();

    // dialog opens already at .t-panel-slide's resting state (data-open
    // = "false"). Flip data-open in the next frame so the browser has
    // committed the closed style first; that's what gives us the slide
    // instead of an instant snap.
    window.requestAnimationFrame(() => {
      modal.setAttribute('data-open', 'true');
    });

    window.setTimeout(() => {
      if (input instanceof HTMLInputElement) {
        input.focus();
        input.select();
      }
    }, 0);
  };

  const closeModal = () => {
    if (!modal.open) {
      return;
    }

    // Start the slide-up + fade first, then call .close() once the
    // transition has finished. Closing the dialog immediately would
    // unmount the element before the user saw any motion.
    modal.setAttribute('data-open', 'false');

    if (closeTimer !== null) {
      window.clearTimeout(closeTimer);
    }
    closeTimer = window.setTimeout(() => {
      closeTimer = null;
      if (modal.open) {
        modal.close();
      }
    }, panelCloseMs);
  };

  const handleClose = () => {
    document.body.classList.remove('search-modal-is-open');
    // Belt-and-suspenders: if the dialog closes via Esc / form submit
    // before our timer fires, make sure data-open is reset for next time.
    modal.setAttribute('data-open', 'false');

    if (activeTrigger instanceof HTMLElement) {
      activeTrigger.focus();
      activeTrigger = null;
    }
  };

  // Backdrop click. The dialog itself receives the click when the user
  // hits the backdrop; the form is the inner stop-zone.
  const handleDialogClick = (event) => {
    if (event.target === modal) {
      closeModal();
    }
  };

  const handleChipClick = (event) => {
    const chip = event.currentTarget;
    if (!(chip instanceof HTMLElement)) {
      return;
    }
    setActiveChip(chip.dataset.value ?? '');
    if (input instanceof HTMLInputElement) {
      input.focus();
    }
  };

  const handleClear = () => {
    if (!(input instanceof HTMLInputElement)) {
      return;
    }
    input.value = '';
    updateClearVisibility();
    input.focus();
  };

  const handleInput = () => {
    updateClearVisibility();
  };

  // Global shortcuts: `/` to focus the modal, Cmd/Ctrl+K to toggle.
  const handleGlobalKeydown = (event) => {
    const target = event.target;

    if ((event.metaKey || event.ctrlKey) && event.key.toLowerCase() === 'k') {
      event.preventDefault();
      if (modal.open) {
        closeModal();
      } else {
        openModal();
      }
      return;
    }

    if (event.key === '/' && !modal.open && !isFormControl(target)) {
      event.preventDefault();
      openModal();
    }
  };

  openButtons.forEach((button) => button.addEventListener('click', openModal));
  closeButtons.forEach((button) => button.addEventListener('click', closeModal));
  chips.forEach((chip) => chip.addEventListener('click', handleChipClick));

  // Intercept Esc so we get the slide-up close transition instead of
  // dialog.close() snapping the element away.
  const handleCancel = (event) => {
    event.preventDefault();
    closeModal();
  };

  modal.addEventListener('click', handleDialogClick);
  modal.addEventListener('close', handleClose);
  modal.addEventListener('cancel', handleCancel);

  if (input instanceof HTMLInputElement) {
    input.addEventListener('input', handleInput);
  }

  if (clearButton instanceof HTMLElement) {
    clearButton.addEventListener('click', handleClear);
  }

  document.addEventListener('keydown', handleGlobalKeydown);

  // Initial state: sync the hidden post_type input to the preselected chip.
  const preselected = chips.find((chip) => chip.getAttribute('aria-pressed') === 'true');
  syncPostTypeInput(preselected?.dataset.value ?? '');
  updateClearVisibility();

  return () => {
    openButtons.forEach((button) => button.removeEventListener('click', openModal));
    closeButtons.forEach((button) => button.removeEventListener('click', closeModal));
    chips.forEach((chip) => chip.removeEventListener('click', handleChipClick));

    if (closeTimer !== null) {
      window.clearTimeout(closeTimer);
      closeTimer = null;
    }

    modal.removeEventListener('click', handleDialogClick);
    modal.removeEventListener('close', handleClose);
    modal.removeEventListener('cancel', handleCancel);

    if (input instanceof HTMLInputElement) {
      input.removeEventListener('input', handleInput);
    }
    if (clearButton instanceof HTMLElement) {
      clearButton.removeEventListener('click', handleClear);
    }

    document.removeEventListener('keydown', handleGlobalKeydown);
    document.body.classList.remove('search-modal-is-open');
  };
};
