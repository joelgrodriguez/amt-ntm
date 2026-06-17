/**
 * RevealMore Module
 *
 * Progressive disclosure for long card grids. A group shows BATCH items, then
 * a "Show more" button reveals the next BATCH on each click, until everything
 * is visible (the button then removes itself). Premium feel: revealed cards
 * fade/rise in with a short stagger; nothing layout-shifts on load because the
 * hidden cards start with `hidden`.
 *
 * Markup contract (set by the template):
 *   <div data-reveal-group data-reveal-batch="6">
 *     <div class="...grid...">
 *       <article data-reveal-item> ... </article>  // repeated
 *     </div>
 *     <div data-reveal-controls>
 *       <button data-reveal-button data-reveal-remaining="34">Show more</button>
 *     </div>
 *   </div>
 *
 * Items beyond the first batch ship with the `hidden` class. JS only ever
 * REMOVES hidden, so with JS off every card is reachable (the button is
 * inside a <noscript>-friendly fallback — see template: button is hidden
 * until JS confirms there's something to hide).
 *
 * @module RevealMore
 */

const PENDING_CLASS = 'reveal-item--pending'; // offset start state
const VISIBLE_CLASS = 'is-visible'; // animated end state

/**
 * Initialize all reveal groups on the page.
 * @returns {Function} Cleanup function
 */
export function initRevealMore() {
  const groups = Array.from(document.querySelectorAll('[data-reveal-group]'));
  const teardowns = [];

  groups.forEach((group) => {
    const batch = Math.max(1, parseInt(group.dataset.revealBatch || '6', 10));
    const button = group.querySelector('[data-reveal-button]');
    const items = Array.from(group.querySelectorAll('[data-reveal-item]'));

    if (!button || items.length <= batch) {
      // Nothing to hide — make sure the control is gone and bail.
      const controls = group.querySelector('[data-reveal-controls]');
      if (controls) controls.remove();
      return;
    }

    // JS confirmed there's overflow: show the control (it ships hidden so a
    // no-JS visitor, who sees every card, never sees a dead button).
    const controls = group.querySelector('[data-reveal-controls]');
    if (controls) controls.classList.remove('hidden');

    let shown = batch;

    function updateRemaining() {
      const remaining = items.length - shown;
      const label = button.querySelector('[data-reveal-count]');
      if (label) label.textContent = String(Math.min(batch, remaining));
    }

    function reveal() {
      const next = items.slice(shown, shown + batch);
      next.forEach((item, i) => {
        // Un-hide with the offset start state in the SAME frame, so the browser
        // never paints the final position first (which would skip the fade).
        item.classList.remove('hidden');
        item.classList.add(PENDING_CLASS);
        // Next frame, drop into place — staggered for a premium cascade.
        requestAnimationFrame(() => {
          window.setTimeout(() => {
            item.classList.remove(PENDING_CLASS);
            item.classList.add(VISIBLE_CLASS);
          }, i * 60);
        });
      });
      shown += next.length;

      if (shown >= items.length) {
        button.removeEventListener('click', reveal);
        if (controls) controls.remove();
        // Move focus to the first newly revealed card for keyboard users.
        const focusTarget = next[0]?.querySelector('a, button');
        if (focusTarget) focusTarget.focus();
      } else {
        updateRemaining();
      }
    }

    updateRemaining();
    button.addEventListener('click', reveal);
    teardowns.push(() => button.removeEventListener('click', reveal));
  });

  return () => teardowns.forEach((fn) => fn());
}
