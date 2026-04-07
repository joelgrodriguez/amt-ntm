/**
 * Machine Sub-Navigation Module
 *
 * Sticky sub-nav for machine product pages.
 * - Shows after scrolling past the hero
 * - Highlights the active section link via IntersectionObserver
 * - Smooth scrolls to sections on click
 * - Toggles machine switcher dropdown
 *
 * @module MachineSubnav
 *
 * @usage Single Machine Product (single-machine.php)
 * @see templates/woo/product/parts/subnav.php
 */

/**
 * Initialize machine sub-navigation.
 * @returns {Function} Cleanup function.
 */
export function initMachineSubnav() {
  const subnav = document.getElementById('machine-subnav');
  const hero = document.getElementById('machine-hero');

  if (!subnav || !hero) return () => {};

  const controller = new AbortController();
  const { signal } = controller;

  // ── Sticky visibility ────────────────────────────────────────────

  const heroObserver = new IntersectionObserver(
    ([entry]) => {
      subnav.classList.toggle('is-visible', !entry.isIntersecting);
    },
    { threshold: 0 }
  );
  heroObserver.observe(hero);

  // ── Active section tracking ──────────────────────────────────────

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

  // Observe each section — the one nearest the top of the viewport wins
  const sectionObserver = new IntersectionObserver(
    (entries) => {
      // Find the topmost visible section
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
      // Trigger when sections cross the top ~20% of the viewport
      rootMargin: '-10% 0px -80% 0px',
      threshold: 0,
    }
  );

  sections.forEach((section) => sectionObserver.observe(section));

  // ── Smooth scroll on anchor click ────────────────────────────────

  subnav.addEventListener(
    'click',
    (e) => {
      const link = e.target.closest('.machine-subnav__link');
      if (!link) return;

      e.preventDefault();
      const targetId = link.dataset.section;
      const target = document.getElementById(targetId);
      if (!target) return;

      // Account for header + subnav height
      const offset =
        (parseInt(getComputedStyle(document.documentElement).getPropertyValue('--header-height'), 10) || 48) + 48 + 16;

      const y = target.getBoundingClientRect().top + window.scrollY - offset;
      window.scrollTo({ top: y, behavior: 'smooth' });

      setActiveLink(targetId);
    },
    { signal }
  );

  // ── Machine switcher dropdown ────────────────────────────────────

  const switcherBtn = subnav.querySelector('.machine-subnav__switcher-btn');
  const dropdown = document.getElementById('machine-subnav-dropdown');

  if (switcherBtn && dropdown) {
    switcherBtn.addEventListener(
      'click',
      () => {
        const isOpen = switcherBtn.getAttribute('aria-expanded') === 'true';
        switcherBtn.setAttribute('aria-expanded', String(!isOpen));
        dropdown.hidden = isOpen;
      },
      { signal }
    );

    // Close on outside click
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

    // Close on Escape
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

  // ── Cleanup ──────────────────────────────────────────────────────

  return () => {
    heroObserver.disconnect();
    sectionObserver.disconnect();
    controller.abort();
  };
}
