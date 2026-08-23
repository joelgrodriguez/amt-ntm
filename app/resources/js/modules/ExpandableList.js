/**
 * Shared expandable-list disclosure.
 *
 * The control stays after the content so it remains at the bottom in both
 * states. Height is measured in JavaScript because CSS cannot transition to
 * or from an intrinsic grid height.
 *
 * @module ExpandableList
 */

const ROOT_SELECTOR = '[data-expandable-list]';
const COMPACT_SELECTOR = '[data-expandable-list-compact]';
const EXPANDED_SELECTOR = '[data-expandable-list-expanded]';
const CONTENT_SELECTOR = '[data-expandable-list-content]';
const CONTROLS_SELECTOR = '[data-expandable-list-controls]';
const BUTTON_SELECTOR = '[data-expandable-list-button]';
const LABEL_SELECTOR = '[data-expandable-list-label]';
const ENTERING_CLASS = 'expandable-list__expanded--entering';

function setHidden(element, isHidden) {
  element.hidden = isHidden;
  element.classList.toggle('hidden', isHidden);
}

export function initExpandableLists() {
  const groups = Array.from(document.querySelectorAll(ROOT_SELECTOR));

  if (!groups.length) {
    return () => {};
  }

  const controller = new AbortController();
  const { signal } = controller;
  const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
  const timers = new Set();

  groups.forEach((group) => {
    const compactElements = Array.from(group.querySelectorAll(COMPACT_SELECTOR));
    const expandedElements = Array.from(group.querySelectorAll(EXPANDED_SELECTOR));
    const content = group.querySelector(CONTENT_SELECTOR);
    const controls = group.querySelector(CONTROLS_SELECTOR);
    const button = group.querySelector(BUTTON_SELECTOR);
    const label = button?.querySelector(LABEL_SELECTOR);

    if (!expandedElements.length || !content || !controls || !button) {
      return;
    }

    expandedElements.forEach((element) => {
      element.removeAttribute('data-expandable-list-no-js-visible');
    });

    const showLabel = button.dataset.showLabel || label?.textContent || 'See all';
    const collapseLabel = button.dataset.collapseLabel || 'Collapse';
    let isExpanded = button.getAttribute('aria-expanded') === 'true';

    const applyState = () => {
      compactElements.forEach((element) => setHidden(element, isExpanded));
      expandedElements.forEach((element) => setHidden(element, !isExpanded));
      group.dataset.expanded = String(isExpanded);
      button.setAttribute('aria-expanded', String(isExpanded));

      if (label) {
        label.textContent = isExpanded ? collapseLabel : showLabel;
      }
    };

    const clearSize = () => {
      content.style.removeProperty('height');
      content.style.removeProperty('overflow');
      expandedElements.forEach((element) => element.classList.remove(ENTERING_CLASS));
    };

    controls.classList.remove('hidden');
    applyState();

    button.addEventListener('click', () => {
      const startHeight = content.getBoundingClientRect().height;
      isExpanded = !isExpanded;

      if (isExpanded && !reducedMotion.matches) {
        expandedElements.forEach((element) => element.classList.add(ENTERING_CLASS));
      }

      content.style.height = `${startHeight}px`;
      content.style.overflow = 'clip';
      applyState();

      if (reducedMotion.matches) {
        clearSize();
        return;
      }

      const targetHeight = content.scrollHeight;
      requestAnimationFrame(() => {
        content.style.height = `${targetHeight}px`;
        expandedElements.forEach((element) => element.classList.remove(ENTERING_CLASS));
      });

      const timer = window.setTimeout(() => {
        timers.delete(timer);
        clearSize();
      }, 350);
      timers.add(timer);
    }, { signal });
  });

  return () => {
    controller.abort();
    timers.forEach((timer) => window.clearTimeout(timer));
    timers.clear();
  };
}
