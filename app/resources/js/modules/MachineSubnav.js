/**
 * Machine Sub-Navigation Module
 *
 * Sticky sub-nav for machine product pages.
 * Sits in normal flow below the hero/CTA, then sticks to the viewport top
 * when the user scrolls past its original position (sentinel-based).
 *
 * - Highlights the active section link via IntersectionObserver
 * - Smooth scrolls to sections on click
 * - Toggles machine switcher dropdown
 *
 * @module MachineSubnav
 *
 * @usage Single Machine Product (single-machine.php)
 * @see templates/woo/product/parts/subnav.php
 * @see css/woo/machine-subnav.css
 */

/**
 * Initialize machine sub-navigation.
 *
 * Subnav is always fixed to the viewport top and hidden by default
 * (translated above its own height). It only becomes visible once the
 * hero scrolls out of view, sliding down gracefully. When the hero
 * re-enters view (scroll back up), it slides out again so the regular
 * header can do its scroll-up reveal without two bars competing.
 *
 * @returns {Function} Cleanup function.
 */
export function initMachineSubnav() {
  const subnav = document.getElementById('machine-subnav');
  const hero = document.getElementById('machine-hero');

  if (!subnav || !hero) return () => {};

  const controller = new AbortController();
  const { signal } = controller;

  const prefersReducedMotion = window.matchMedia(
    '(prefers-reduced-motion: reduce)'
  ).matches;

  const visibilityObserver = new IntersectionObserver(
    ([entry]) => {
      // Hero in view → hide subnav. Hero out of view → show subnav.
      // is-sticky is the same class name ScrollHeader checks to
      // suppress its scroll-up reveal, so the two bars never stack.
      const shouldShow = !entry.isIntersecting;
      subnav.classList.toggle('is-sticky', shouldShow);
    },
    { threshold: 0 }
  );
  visibilityObserver.observe(hero);

  const links = subnav.querySelectorAll('.machine-subnav__link');
  const sectionIds = [...links].map((link) => link.dataset.section);
  const sections = sectionIds
    .map((id) => document.getElementById(id))
    .filter(Boolean);

  /**
   * Set active link by section ID.
   * @param {string} id
   */
  function setActiveLink(id) {
    links.forEach((link) => {
      link.classList.toggle('is-active', link.dataset.section === id);
    });
  }
  const sectionObserver = new IntersectionObserver(
    (entries) => {
      let topSection = null;
      let topY = Infinity;

      entries.forEach((entry) => {
        if (entry.isIntersecting && entry.boundingClientRect.top < topY) {
          topY = entry.boundingClientRect.top;
          topSection = entry.target.id;
        }
      });

      if (topSection) {
        setActiveLink(topSection);
      }
    },
    {
      rootMargin: '-10% 0px -80% 0px',
      threshold: 0,
    }
  );

  sections.forEach((section) => sectionObserver.observe(section));

  subnav.addEventListener(
    'click',
    (e) => {
      const link = e.target.closest('.machine-subnav__link');
      if (!link) return;

      e.preventDefault();
      const targetId = link.dataset.section;
      const target = document.getElementById(targetId);
      if (!target) return;
      const headerH = parseInt(
        getComputedStyle(document.documentElement).getPropertyValue('--header-height'),
        10
      ) || 48;
      // Sidebar rail sits beside the content (not above it), so its height
      // must not be subtracted from the scroll target. Only the vertical
      // rail at lg: counts as "beside"; below lg: the sidebar variant still
      // renders as a horizontal bar, so keep its height. Evaluated per click
      // so a resize across the breakpoint is always correct.
      const railActive =
        subnav.classList.contains('machine-subnav--sidebar') &&
        window.matchMedia('(min-width: 64rem)').matches;
      const subnavH = railActive ? 0 : subnav.offsetHeight;
      const offset = headerH + subnavH + 16;

      const y = target.getBoundingClientRect().top + window.scrollY - offset;
      window.scrollTo({
        top: y,
        behavior: prefersReducedMotion ? 'auto' : 'smooth',
      });

      setActiveLink(targetId);
    },
    { signal }
  );

  const switcherBtn = subnav.querySelector('.machine-subnav__switcher-btn');
  const dropdown = document.getElementById('machine-subnav-dropdown');

  if (switcherBtn && dropdown) {
    switcherBtn.addEventListener(
      'click',
      () => {
        const isOpen = switcherBtn.getAttribute('aria-expanded') === 'true';
        switcherBtn.setAttribute('aria-expanded', String(!isOpen));
        dropdown.hidden = isOpen;
        if (!isOpen) {
          const firstItem = dropdown.querySelector('.machine-subnav__dropdown-item');
          firstItem?.focus();
        }
      },
      { signal }
    );
    document.addEventListener(
      'click',
      (e) => {
        if (!subnav.contains(e.target)) {
          switcherBtn.setAttribute('aria-expanded', 'false');
          dropdown.hidden = true;
        }
      },
      { signal }
    );
    document.addEventListener(
      'keydown',
      (e) => {
        if (e.key === 'Escape' && !dropdown.hidden) {
          switcherBtn.setAttribute('aria-expanded', 'false');
          dropdown.hidden = true;
          switcherBtn.focus();
        }
      },
      { signal }
    );
  }

  return () => {
    visibilityObserver.disconnect();
    sectionObserver.disconnect();
    controller.abort();
  };
}
