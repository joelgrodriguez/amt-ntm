/**
 * Header search modal.
 */

export const initSearchModal = () => {
  const modal = document.querySelector('#site-search-modal');
  const openButtons = document.querySelectorAll('[data-search-modal-open]');

  if (!(modal instanceof HTMLDialogElement) || openButtons.length === 0) {
    return null;
  }

  const closeButtons = modal.querySelectorAll('[data-search-modal-close]');
  const input = modal.querySelector('[data-search-modal-input]');
  let activeTrigger = null;

  const openModal = (event) => {
    event.preventDefault();
    activeTrigger = event.currentTarget;

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

  return () => {
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
