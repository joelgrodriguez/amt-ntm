const LG_QUERY = '(min-width: 64rem)';

/**
 * Keeps footer <details> sections honest across breakpoints.
 *
 * Mobile gets native collapsed details. Desktop gets forced-open sections and
 * non-tabbable summaries, matching the old static footer columns.
 */
export function initFooterAccordions() {
  const items = Array.from(document.querySelectorAll('[data-footer-accordion]'))
    .filter((item) => item instanceof HTMLDetailsElement);

  if (items.length === 0) {
    return () => {};
  }

  const media = window.matchMedia(LG_QUERY);
  const summaries = items
    .map((item) => item.querySelector('summary'))
    .filter((summary) => summary instanceof HTMLElement);

  const sync = () => {
    items.forEach((item) => {
      if (media.matches) {
        item.dataset.mobileOpen = item.open ? 'true' : 'false';
        item.open = true;
      } else if (item.dataset.mobileOpen) {
        item.open = item.dataset.mobileOpen === 'true';
        delete item.dataset.mobileOpen;
      }
    });

    summaries.forEach((summary) => {
      if (media.matches) {
        summary.setAttribute('tabindex', '-1');
      } else {
        summary.removeAttribute('tabindex');
      }
    });
  };

  const keepDesktopOpen = (event) => {
    const item = event.currentTarget;
    if (media.matches && item instanceof HTMLDetailsElement && !item.open) {
      item.open = true;
    }
  };

  items.forEach((item) => item.addEventListener('toggle', keepDesktopOpen));
  media.addEventListener('change', sync);
  sync();

  return () => {
    media.removeEventListener('change', sync);
    items.forEach((item) => item.removeEventListener('toggle', keepDesktopOpen));
    summaries.forEach((summary) => summary.removeAttribute('tabindex'));
  };
}
