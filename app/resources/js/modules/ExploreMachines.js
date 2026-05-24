/**
 * Explore Machines Module
 *
 * Handles tab switching and horizontal scroll navigation
 * for the Explore Machines section.
 *
 * @module ExploreMachines
 *
 * @usage Front Page (front-page.php)
 * @template templates/parts/front-page/explore-machines.php
 * @styles css/explore-machines.css
 */

const DEFAULTS = {
  selector: '.explore-machines',
  scrollAmount: 400,
  debounceDelay: 100,
};

/**
 * Creates a debounced version of a function.
 *
 * @param {Function} fn - Function to debounce
 * @param {number} delay - Delay in milliseconds
 * @returns {Function} Debounced function
 */
function debounce(fn, delay) {
  let timeoutId;
  return function (...args) {
    clearTimeout(timeoutId);
    timeoutId = setTimeout(() => fn.apply(this, args), delay);
  };
}

/**
 * Initializes the Explore Machines functionality.
 *
 * @param {Object} options - Configuration options
 * @returns {Function|void} Cleanup function for HMR, or void if not found
 */
export function initExploreMachines(options = {}) {
  const config = { ...DEFAULTS, ...options };
  const section = document.querySelector(config.selector);

  if (!section) return;

  const tabs = section.querySelectorAll('.explore-machines__tab');
  const panels = section.querySelectorAll('.explore-machines__panel');

  if (tabs.length === 0 || panels.length === 0) return;
  const controller = new AbortController();
  const { signal } = controller;

  /**
   * Switch to a specific tab/panel.
   */
  function switchTab(categorySlug) {
    tabs.forEach((tab) => {
      const isActive = tab.dataset.category === categorySlug;
      tab.classList.toggle('explore-machines__tab--active', isActive);
      tab.setAttribute('aria-selected', String(isActive));
      tab.setAttribute('tabindex', isActive ? '0' : '-1');
    });
    panels.forEach((panel) => {
      const isActive = panel.id === `panel-${categorySlug}`;
      panel.classList.toggle('explore-machines__panel--active', isActive);
      if (isActive) {
        const track = panel.querySelector('.explore-machines__track');
        if (track) {
          track.scrollLeft = 0;
          updateCounter(panel, track);
        }
      }
    });
  }

  /**
   * Update the counter display for a panel.
   */
  function updateCounter(panel, track) {
    const currentEl = panel.querySelector('.explore-machines__current');
    const cards = track.querySelectorAll('.card-product');

    if (!currentEl || cards.length === 0) return;
    const trackRect = track.getBoundingClientRect();
    let visibleIndex = 0;

    cards.forEach((card, index) => {
      const cardRect = card.getBoundingClientRect();
      const cardCenter = cardRect.left + cardRect.width / 2;

      if (cardCenter >= trackRect.left && cardCenter <= trackRect.right) {
        visibleIndex = index;
      }
    });

    currentEl.textContent = String(visibleIndex + 1);
    const prevBtn = panel.querySelector('.explore-machines__arrow--prev');
    const nextBtn = panel.querySelector('.explore-machines__arrow--next');
    const isAtStart = track.scrollLeft <= 0;
    const isAtEnd = track.scrollLeft >= track.scrollWidth - track.clientWidth - 10;

    if (prevBtn) prevBtn.disabled = isAtStart;
    if (nextBtn) nextBtn.disabled = isAtEnd;
  }

  /**
   * Scroll the track in a direction.
   */
  function scrollTrack(panel, direction) {
    const track = panel.querySelector('.explore-machines__track');
    if (!track) return;

    const amount = direction === 'next' ? config.scrollAmount : -config.scrollAmount;
    track.scrollBy({ left: amount, behavior: 'smooth' });
    setTimeout(() => updateCounter(panel, track), 350);
  }

  /**
   * Set up event listeners.
   */
  function setupEventListeners() {
    tabs.forEach((tab) => {
      tab.addEventListener(
        'click',
        () => {
          const category = tab.dataset.category;
          if (category) switchTab(category);
        },
        { signal }
      );
    });
    section.addEventListener(
      'click',
      (e) => {
        const arrow = e.target.closest('.explore-machines__arrow');
        if (!arrow) return;

        const panel = section.querySelector(`#panel-${arrow.dataset.panel}`);
        if (!panel) return;

        const direction = arrow.classList.contains('explore-machines__arrow--next') ? 'next' : 'prev';
        scrollTrack(panel, direction);
      },
      { signal }
    );
    panels.forEach((panel) => {
      const track = panel.querySelector('.explore-machines__track');
      if (track) {
        const debouncedUpdate = debounce(() => updateCounter(panel, track), config.debounceDelay);
        track.addEventListener('scroll', debouncedUpdate, { passive: true, signal });
      }
    });
    section.addEventListener(
      'keydown',
      (e) => {
        if (!e.target.classList.contains('explore-machines__tab')) return;

        const tabArray = Array.from(tabs);
        const currentIndex = tabArray.indexOf(e.target);

        if (e.key === 'ArrowLeft' || e.key === 'ArrowRight') {
          e.preventDefault();
          const nextIndex =
            e.key === 'ArrowRight'
              ? (currentIndex + 1) % tabArray.length
              : (currentIndex - 1 + tabArray.length) % tabArray.length;
          tabArray[nextIndex].focus();
          return;
        }

        if (e.key === 'Home') {
          e.preventDefault();
          tabArray[0].focus();
          return;
        }

        if (e.key === 'End') {
          e.preventDefault();
          tabArray[tabArray.length - 1].focus();
          return;
        }

        if ((e.key === 'Enter' || e.key === ' ') && e.target.dataset.category) {
          e.preventDefault();
          switchTab(e.target.dataset.category);
        }
      },
      { signal }
    );
  }

  /**
   * Initialize counters for all panels.
   */
  function initCounters() {
    panels.forEach((panel) => {
      const track = panel.querySelector('.explore-machines__track');
      if (track) {
        updateCounter(panel, track);
      }
    });
  }
  setupEventListeners();
  initCounters();
  return function cleanup() {
    controller.abort();
  };
}
