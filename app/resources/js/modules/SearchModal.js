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

const isMacPlatform = () => {
  if (typeof navigator === 'undefined') {
    return false;
  }
  const platform = navigator.userAgentData?.platform ?? navigator.platform ?? '';
  return /Mac|iPhone|iPad|iPod/i.test(platform);
};

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
  const suggestions = modal.querySelectorAll('[data-search-modal-suggestion]');
  const helperText = modal.querySelector('[data-search-modal-helper]');
  const shortcutHint = modal.querySelector('[data-search-modal-shortcut] kbd');
  let activeTrigger = null;

  // Hint glyph: Cmd K on mac, Ctrl K everywhere else.
  if (shortcutHint instanceof HTMLElement) {
    shortcutHint.textContent = isMacPlatform() ? '⌘ K' : 'Ctrl K';
  }

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
    const hasText = input.value.trim() !== '';
    clearButton.hidden = !hasText;

    if (helperText instanceof HTMLElement) {
      const next = hasText ? helperText.dataset.helperTyped : helperText.dataset.helperDefault;
      if (typeof next === 'string') {
        helperText.textContent = next;
      }
    }
  };

  const openModal = (event) => {
    if (event) {
      event.preventDefault();
      activeTrigger = event.currentTarget instanceof HTMLElement ? event.currentTarget : null;
    }

    if (!modal.open) {
      modal.showModal();
    }

    document.body.classList.add('search-modal-is-open');
    updateClearVisibility();

    window.setTimeout(() => {
      if (input instanceof HTMLInputElement) {
        input.focus();
        input.select();
      }
    }, 0);
  };

  const closeModal = () => {
    if (modal.open) {
      modal.close();
    }
  };

  const handleClose = () => {
    document.body.classList.remove('search-modal-is-open');

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

  // Suggestion click. Rather than navigating away immediately, we mirror
  // the suggestion's intent into the form and submit it. Lets the user
  // see the active chip light up before the page changes; also means
  // we go through the same submit path as a manual search.
  const handleSuggestionClick = (event) => {
    const anchor = event.currentTarget;
    if (!(anchor instanceof HTMLAnchorElement)) {
      return;
    }
    event.preventDefault();

    if (input instanceof HTMLInputElement) {
      input.value = anchor.dataset.query ?? '';
    }
    setActiveChip(anchor.dataset.postType ?? '');
    updateClearVisibility();
    modal.querySelector('form')?.submit();
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
  suggestions.forEach((suggestion) => suggestion.addEventListener('click', handleSuggestionClick));

  modal.addEventListener('click', handleDialogClick);
  modal.addEventListener('close', handleClose);

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
    suggestions.forEach((suggestion) => suggestion.removeEventListener('click', handleSuggestionClick));

    modal.removeEventListener('click', handleDialogClick);
    modal.removeEventListener('close', handleClose);

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
