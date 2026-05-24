/**
 * Avatar Group Hover
 *
 * Distance-falloff hover lift for any .t-avatar-group, paired with the
 * .t-avatar CSS in transitions.css. Hover an item to lift it and gently
 * lift its neighbors; mouseleave returns everyone with a bouncy spring.
 *
 * Direction-aware easing trick: set transition-timing-function inline
 * BEFORE writing the CSS variables so hover-in and mouseleave can use
 * different curves on the same property.
 *
 * @module AvatarGroupHover
 */

const ROOT_SELECTOR = '.t-avatar-group';
const ITEM_SELECTOR = '.t-avatar';

/**
 * @returns {Function} Cleanup function.
 */
export function initAvatarGroupHover() {
  const groups = document.querySelectorAll(ROOT_SELECTOR);
  if (!groups.length) return () => {};

  const controller = new AbortController();
  const { signal } = controller;

  const cs = getComputedStyle(document.documentElement);
  const num = (name, fb) => {
    const v = parseFloat(cs.getPropertyValue(name));
    return Number.isFinite(v) ? v : fb;
  };
  const ease = (name, fb) => cs.getPropertyValue(name).trim() || fb;

  groups.forEach((root) => {
    const items = Array.from(root.querySelectorAll(ITEM_SELECTOR));
    if (!items.length) return;

    /**
     * @param {number|null} activeIdx — null on mouseleave; resets state
     * @param {'in'|'out'} phase
     */
    function setShifts(activeIdx, phase) {
      const lift    = num('--avatar-lift', -4);
      const falloff = num('--avatar-falloff', 0.45);
      const scale   = num('--avatar-scale', 1.05);
      const tf      = phase === 'out'
        ? ease('--avatar-ease-out', 'cubic-bezier(0.34, 3.85, 0.64, 1)')
        : ease('--avatar-ease-in',  'cubic-bezier(0.22, 1, 0.36, 1)');

      items.forEach((el, i) => {
        el.style.transitionTimingFunction = tf;
        if (activeIdx == null) {
          el.style.setProperty('--shift', '0px');
          el.style.setProperty('--scale-active', '1');
          return;
        }
        const d = Math.abs(i - activeIdx);
        el.style.setProperty(
          '--shift',
          (lift * Math.pow(falloff, d)).toFixed(3) + 'px'
        );
        el.style.setProperty(
          '--scale-active',
          i === activeIdx ? String(scale) : '1'
        );
      });
    }

    items.forEach((el, i) => {
      el.addEventListener('mouseenter', () => setShifts(i, 'in'), { signal });
    });
    root.addEventListener('mouseleave', () => setShifts(null, 'out'), { signal });
  });

  return () => controller.abort();
}
