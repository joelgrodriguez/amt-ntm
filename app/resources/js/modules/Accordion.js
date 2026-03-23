/**
 * Accordion Module
 *
 * Single-open behavior for native <details> accordion groups.
 * Add [data-accordion-group] to the parent container.
 * First <details> opens on init; clicking another closes the rest.
 *
 * @module Accordion
 */

const GROUP_SELECTOR = '[data-accordion-group]';

/**
 * Initializes all accordion groups on the page.
 *
 * @returns {Function} Cleanup function.
 */
export function initAccordion() {
  const groups = document.querySelectorAll(GROUP_SELECTOR);
  if (!groups.length) return () => {};

  const controller = new AbortController();

  groups.forEach((group) => {
    const items = [...group.querySelectorAll(':scope > details')];
    if (!items.length) return;

    // Ensure first item is open
    if (!items.some((d) => d.open)) {
      items[0].open = true;
    }

    items.forEach((detail) => {
      // Prevent closing the active item — one must always be open
      detail.querySelector('summary')?.addEventListener('click', (e) => {
        if (detail.open) e.preventDefault();
      }, { signal: controller.signal });

      // Close siblings when one opens
      detail.addEventListener('toggle', () => {
        if (!detail.open) return;
        items.forEach((other) => {
          if (other !== detail && other.open) {
            other.open = false;
          }
        });
      }, { signal: controller.signal });
    });
  });

  return () => controller.abort();
}
