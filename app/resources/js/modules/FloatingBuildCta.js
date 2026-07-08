/**
 * Floating Build & Configure CTA
 *
 * Reveals the bottom-left configurator shortcut shortly after load with
 * a delayed entrance — no scroll gate. Hides while the mega menu is open.
 *
 * @module FloatingBuildCta
 */

/** Delay before the entrance so the hero settles first. */
const REVEAL_DELAY_MS = 1200;

/**
 * @returns {Function}
 */
export function initFloatingBuildCta() {
  const cta = document.getElementById('floating-build-cta');

  if (!cta) {
    return () => {};
  }

  let revealed = false;
  let revealTimer = null;

  function show() {
    if (document.body.classList.contains('mega-open')) {
      return;
    }

    cta.classList.add('is-visible');
    cta.removeAttribute('aria-hidden');
    revealed = true;
  }

  function hide() {
    cta.classList.remove('is-visible');
    cta.setAttribute('aria-hidden', 'true');
  }

  revealTimer = window.setTimeout(show, REVEAL_DELAY_MS);

  const bodyObserver = new MutationObserver(() => {
    if (document.body.classList.contains('mega-open')) {
      hide();
      return;
    }

    if (revealed) {
      show();
    }
  });
  bodyObserver.observe(document.body, { attributes: true, attributeFilter: ['class'] });

  return () => {
    window.clearTimeout(revealTimer);
    bodyObserver.disconnect();
    hide();
    revealed = false;
  };
}