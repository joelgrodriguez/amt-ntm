/**
 * Profile Gallery
 *
 * Click-to-swap thumbnail strip on single-profile pages. Each [data-profile-gallery]
 * holds buttons carrying a full-size image URL in data-full; clicking one swaps
 * the main image (#profile-main-image) and moves the active state.
 *
 * Progressive enhancement: with JS off the featured image still renders and the
 * thumbnails are inert buttons — nothing breaks.
 *
 * @module ProfileGallery
 * @styles css/pages/single.css
 */

const GALLERY_SELECTOR = '[data-profile-gallery]';
const MAIN_SELECTOR = '#profile-main-image';
const THUMB_SELECTOR = '.profile-gallery__thumb';
const ACTIVE_CLASS = 'is-active';

/**
 * @returns {Function} Cleanup function.
 */
export function initProfileGallery() {
  const gallery = document.querySelector(GALLERY_SELECTOR);
  const main = document.querySelector(MAIN_SELECTOR);
  if (!gallery || !(main instanceof HTMLImageElement)) return () => {};

  const thumbs = Array.from(gallery.querySelectorAll(THUMB_SELECTOR));
  if (!thumbs.length) return () => {};

  const controller = new AbortController();
  const { signal } = controller;

  const setActive = (button) => {
    const full = button.getAttribute('data-full');
    if (!full || full === main.getAttribute('src')) return;

    main.setAttribute('src', full);

    thumbs.forEach((t) => {
      const on = t === button;
      t.classList.toggle(ACTIVE_CLASS, on);
      t.setAttribute('aria-pressed', on ? 'true' : 'false');
    });
  };

  thumbs.forEach((button) => {
    button.addEventListener('click', () => setActive(button), { signal });
  });

  return () => controller.abort();
}
