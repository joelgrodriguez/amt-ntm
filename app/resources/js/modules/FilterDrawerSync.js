/**
 * The filter sidebar renders twice (mobile drawer + desktop rail) with
 * identical radio names in one form. Browsers keep only the LAST
 * server-checked radio per group — the rail — so the mobile drawer shows
 * active filters as unchecked. Re-assert the server state (defaultChecked)
 * onto whichever copy is visible, on load and when the breakpoint flips.
 */

const LG = '(min-width: 64rem)';

let mql = null;
let handler = null;

function sync() {
  const visibleScope = mql.matches
    ? 'aside .filter-sidebar'
    : '.filter-drawer-body';

  document.querySelectorAll(`${visibleScope} input[type="radio"], ${visibleScope} input[type="checkbox"]`)
    .forEach((input) => {
      if (input.defaultChecked && !input.checked) {
        input.checked = true;
      }
    });
}

export function initFilterDrawerSync() {
  cleanup();

  if (!document.querySelector('.filter-drawer-body input')) {
    return; // link-mode sidebars or no sidebar at all
  }

  mql = window.matchMedia(LG);
  handler = () => sync();
  mql.addEventListener('change', handler);
  sync();

  return cleanup;
}

function cleanup() {
  if (mql && handler) {
    mql.removeEventListener('change', handler);
  }
  mql = null;
  handler = null;
}
