/**
 * Floating Build & Configure CTA
 *
 * Reveals the bottom-left configurator shortcut after scrolling past
 * the page hero. Uses IntersectionObserver when a scroll anchor exists;
 * falls back to a scroll threshold otherwise.
 *
 * @module FloatingBuildCta
 */

const SCROLL_THRESHOLD = 300;

/**
 * @param {string} anchorId
 * @returns {HTMLElement|null}
 */
function resolveScrollAnchor(anchorId) {
  if (!anchorId) {
    return null;
  }

  const direct = document.getElementById(anchorId);
  if (direct) {
    return direct;
  }

  const titled = document.getElementById(`${anchorId}-title`);
  return titled?.closest('section') ?? null;
}

/**
 * @returns {Function}
 */
export function initFloatingBuildCta() {
  const cta = document.getElementById('floating-build-cta');

  if (!cta) {
    return () => {};
  }

  const anchor = resolveScrollAnchor(cta.dataset.scrollAnchor ?? '');
  let ticking = false;
  let scrollFallbackCleanup = () => {};

  const show = () => cta.classList.add('is-visible');
  const hide = () => cta.classList.remove('is-visible');

  function setVisible(isVisible) {
    const megaMenuOpen = document.body.classList.contains('mega-open');

    if (isVisible && !megaMenuOpen) {
      show();
    } else {
      hide();
    }
  }

  function updateFromScroll() {
    setVisible(window.scrollY > SCROLL_THRESHOLD);
    ticking = false;
  }

  function onScroll() {
    if (!ticking) {
      requestAnimationFrame(updateFromScroll);
      ticking = true;
    }
  }

  function bindScrollFallback() {
    updateFromScroll();
    const bodyObserver = new MutationObserver(updateFromScroll);
    bodyObserver.observe(document.body, { attributes: true, attributeFilter: ['class'] });
    window.addEventListener('scroll', onScroll, { passive: true });

    return () => {
      bodyObserver.disconnect();
      window.removeEventListener('scroll', onScroll);
    };
  }

  if (anchor) {
    const observer = new IntersectionObserver(
      ([entry]) => {
        setVisible(!entry.isIntersecting);
      },
      { threshold: 0 }
    );

    observer.observe(anchor);

    const bodyObserver = new MutationObserver(() => {
      if (document.body.classList.contains('mega-open')) {
        hide();
      }
    });
    bodyObserver.observe(document.body, { attributes: true, attributeFilter: ['class'] });

    return () => {
      observer.disconnect();
      bodyObserver.disconnect();
    };
  }

  scrollFallbackCleanup = bindScrollFallback();

  return () => {
    scrollFallbackCleanup();
  };
}