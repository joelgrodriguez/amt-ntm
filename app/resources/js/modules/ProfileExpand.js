/**
 * Profile Expand Module
 *
 * Toggles a profile carousel into an in-place grid and back again.
 *
 * Markup contract:
 *   <div data-profile-expand
 *        data-profile-expand-show-label="See All 16 Profiles"
 *        data-profile-expand-collapse-label="Collapse Profiles">
 *     <div data-profile-expand-compact>carousel nav or track</div>
 *     <button data-profile-expand-button aria-controls="profiles-grid">
 *       <span data-profile-expand-label>See All 16 Profiles</span>
 *     </button>
 *     <div id="profiles-grid" data-profile-expand-grid hidden>...</div>
 *   </div>
 *
 * @module ProfileExpand
 */

const ROOT_SELECTOR = '[data-profile-expand]';
const COMPACT_SELECTOR = '[data-profile-expand-compact]';
const GRID_SELECTOR = '[data-profile-expand-grid]';
const CONTROLS_SELECTOR = '[data-profile-expand-controls]';
const BUTTON_SELECTOR = '[data-profile-expand-button]';
const LABEL_SELECTOR = '[data-profile-expand-label]';

/**
 * Toggle element visibility with both the semantic hidden attribute and the
 * Tailwind display utility used by server-rendered markup.
 *
 * @param {HTMLElement} element
 * @param {boolean} isHidden
 * @returns {void}
 */
function setHidden(element, isHidden) {
  element.hidden = isHidden;
  element.classList.toggle('hidden', isHidden);
}

/**
 * Initialize all profile expand groups.
 *
 * @returns {Function} Cleanup function.
 */
export function initProfileExpand() {
  const groups = Array.from(document.querySelectorAll(ROOT_SELECTOR));

  if (!groups.length) {
    return () => {};
  }

  const controller = new AbortController();
  const { signal } = controller;

  groups.forEach((group) => {
    const compactElements = Array.from(group.querySelectorAll(COMPACT_SELECTOR));
    const grid = group.querySelector(GRID_SELECTOR);
    const controls = group.querySelector(CONTROLS_SELECTOR);
    const button = group.querySelector(BUTTON_SELECTOR);
    const label = button?.querySelector(LABEL_SELECTOR);

    if (!compactElements.length || !grid || !button) {
      return;
    }

    const showLabel = group.dataset.profileExpandShowLabel || label?.textContent || '';
    const collapseLabel = group.dataset.profileExpandCollapseLabel || 'Collapse Profiles';
    let isExpanded = button.getAttribute('aria-expanded') === 'true';

    const render = () => {
      compactElements.forEach((element) => setHidden(element, isExpanded));
      setHidden(grid, !isExpanded);
      button.setAttribute('aria-expanded', String(isExpanded));

      if (label) {
        label.textContent = isExpanded ? collapseLabel : showLabel;
      }
    };

    if (controls) {
      controls.classList.remove('hidden');
    }

    render();

    button.addEventListener(
      'click',
      () => {
        isExpanded = !isExpanded;
        render();
      },
      { signal }
    );
  });

  return () => controller.abort();
}
