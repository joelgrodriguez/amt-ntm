/**
 * Accordion Module
 *
 * Carbon-style accordion with single-open behavior.
 * One item is always expanded. Clicking the open item does nothing.
 * Manages max-height, icon rotation, and aria-expanded state.
 *
 * @module Accordion
 */

/** @type {string} Selector for accordion containers */
const ACCORDION_SELECTOR = '[data-accordion]';

/** @type {string} Selector for accordion items */
const ITEM_SELECTOR = '[data-accordion-item]';

/** @type {string} Selector for accordion triggers */
const TRIGGER_SELECTOR = '[data-accordion-trigger]';

/** @type {string} Selector for accordion content panels */
const CONTENT_SELECTOR = '[data-accordion-content]';

/** @type {string} Selector for the chevron icon wrapper */
const ICON_SELECTOR = '.cds-accordion-icon';

/** @type {string} Class added to the active/open item */
const ACTIVE_CLASS = 'is-active';

/**
 * Closes an accordion item.
 *
 * @param {Element} item - The accordion item element
 */
const closeItem = (item) => {
  const trigger = item.querySelector(TRIGGER_SELECTOR);
  const content = item.querySelector(CONTENT_SELECTOR);
  const icon = item.querySelector(ICON_SELECTOR);

  item.classList.remove(ACTIVE_CLASS);
  if (content) content.style.maxHeight = null;
  if (trigger) trigger.setAttribute('aria-expanded', 'false');
  if (icon) icon.style.transform = '';
};

/**
 * Opens an accordion item.
 *
 * @param {Element} item - The accordion item element
 */
const openItem = (item) => {
  const trigger = item.querySelector(TRIGGER_SELECTOR);
  const content = item.querySelector(CONTENT_SELECTOR);
  const icon = item.querySelector(ICON_SELECTOR);

  item.classList.add(ACTIVE_CLASS);
  if (content) content.style.maxHeight = content.scrollHeight + 'px';
  if (trigger) trigger.setAttribute('aria-expanded', 'true');
  if (icon) icon.style.transform = 'rotate(180deg)';
};

/**
 * Initializes all accordion instances on the page.
 *
 * @returns {Function|undefined} Cleanup function for HMR.
 */
export function initAccordion() {
  const accordions = document.querySelectorAll(ACCORDION_SELECTOR);

  if (!accordions.length) return;

  const controller = new AbortController();

  accordions.forEach((accordion) => {
    const items = [...accordion.querySelectorAll(ITEM_SELECTOR)];

    // Attach click handlers
    items.forEach((item) => {
      const trigger = item.querySelector(TRIGGER_SELECTOR);
      if (!trigger) return;

      trigger.addEventListener('click', () => {
        // Skip if already open
        if (item.classList.contains(ACTIVE_CLASS)) return;

        // Close all others
        items.forEach(closeItem);

        // Open this one
        openItem(item);
      }, { signal: controller.signal });
    });

    // Open the first item on init
    if (items.length) {
      openItem(items[0]);
    }
  });

  return () => {
    controller.abort();
  };
}
