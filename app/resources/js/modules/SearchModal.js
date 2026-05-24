/**
 * Header search modal.
 *
 * Click the search icon to open. Keyboard shortcut: Cmd+K on macOS,
 * Ctrl+K elsewhere. The shortcut glyph is computed once on init and
 * written into the tooltip via the data-shortcut attribute so the
 * UI tells the truth about whatever platform the user is on.
 */

const IS_MAC = typeof navigator !== 'undefined'
  && /Mac|iPhone|iPad|iPod/i.test(navigator.platform || navigator.userAgent || '');

const SHORTCUT_LABEL = IS_MAC ? '⌘ K' : 'Ctrl K';

export const initSearchModal = () => {
  const modal = document.querySelector('#site-search-modal');
  const openButtons = document.querySelectorAll('[data-search-modal-open]');

  if (!(modal instanceof HTMLDialogElement) || openButtons.length === 0) {
    return null;
  }

  const closeButtons = modal.querySelectorAll('[data-search-modal-close]');
  const input = modal.querySelector('[data-search-modal-input]');
  let activeTrigger = null;

  // Surface the platform-correct keyboard shortcut on every search trigger
  // so the CSS tooltip can render it without baking a glyph into PHP.
  openButtons.forEach((button) => {
    if (button instanceof HTMLElement) {
      button.dataset.shortcut = SHORTCUT_LABEL;
    }
  });

  const openModal = (event) => {
    if (event && typeof event.preventDefault === 'function') {
      event.preventDefault();
    }
    if (event && event.currentTarget instanceof HTMLElement) {
      activeTrigger = event.currentTarget;
    }

    if (!modal.open) {
      modal.showModal();
    }

    document.body.classList.add('search-modal-is-open');

    window.setTimeout(() => {
      if (input instanceof HTMLInputElement) {
        input.focus();
        input.select();
      }
    }, 0);
  };

  const handleShortcut = (event) => {
    if (event.key !== 'k' && event.key !== 'K') {
      return;
    }
    const wantsModifier = IS_MAC ? event.metaKey : event.ctrlKey;
    if (!wantsModifier) {
      return;
    }

    event.preventDefault();

    if (modal.open) {
      closeModal();
      return;
    }

    activeTrigger = openButtons[0] instanceof HTMLElement ? openButtons[0] : null;
    openModal(null);
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
    }
  };

  const handleBackdropClick = (event) => {
    if (event.target === modal) {
      closeModal();
    }
  };

  openButtons.forEach((button) => {
    button.addEventListener('click', openModal);
  });

  closeButtons.forEach((button) => {
    button.addEventListener('click', closeModal);
  });

  modal.addEventListener('click', handleBackdropClick);
  modal.addEventListener('close', handleClose);
  document.addEventListener('keydown', handleShortcut);

  return () => {
    document.removeEventListener('keydown', handleShortcut);

    openButtons.forEach((button) => {
      button.removeEventListener('click', openModal);
    });

    closeButtons.forEach((button) => {
      button.removeEventListener('click', closeModal);
    });

    modal.removeEventListener('click', handleBackdropClick);
    modal.removeEventListener('close', handleClose);
    document.body.classList.remove('search-modal-is-open');
  };
};
