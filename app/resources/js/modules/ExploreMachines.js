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
  debounceDelay: 100,
};

/** Direct track children — product cards and profile carousel cards. */
const TRACK_CARD_SELECTOR = '.card-product, .carousel__card';

/* Read --text-swap-dur from CSS so the JS phase timing stays in sync with
 * the shared token in transitions.css. Falls back to 150ms (the default). */
const TEXT_SWAP_DUR_MS = (() => {
  if (typeof window === 'undefined') return 150;
  const raw = getComputedStyle(document.documentElement)
    .getPropertyValue('--text-swap-dur').trim();
  const num = parseFloat(raw);
  return Number.isFinite(num) ? num : 150;
})();

/**
 * Three-phase text swap on a .t-text-swap element. Skips when the new
 * value matches the current value to avoid pointless animations during
 * continuous scrolling.
 *
 * @param {HTMLElement} el
 * @param {string} next
 */
function swapText(el, next) {
  if (el.textContent === next) return;

  el.classList.add('is-exit');
  setTimeout(() => {
    el.textContent = next;
    el.classList.remove('is-exit');
    el.classList.add('is-enter-start');
    void el.offsetHeight; // force reflow so the next removal transitions
    el.classList.remove('is-enter-start');
  }, TEXT_SWAP_DUR_MS);
}

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

  // AbortController for clean event listener removal
  const controller = new AbortController();
  const { signal } = controller;

  /**
   * Switch to a specific tab/panel.
   */
  function switchTab(categorySlug) {
    // Update tabs
    tabs.forEach((tab) => {
      const isActive = tab.dataset.category === categorySlug;
      tab.classList.toggle('explore-machines__tab--active', isActive);
      tab.setAttribute('aria-selected', String(isActive));
      tab.setAttribute('tabindex', isActive ? '0' : '-1');
    });

    // Update panels
    panels.forEach((panel) => {
      const isActive = panel.id === `panel-${categorySlug}`;
      panel.classList.toggle('explore-machines__panel--active', isActive);

      // Reset scroll position and counter when switching
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
    const cards = track.querySelectorAll(TRACK_CARD_SELECTOR);

    if (!currentEl || cards.length === 0) return;

    // Calculate which card is most visible
    const trackRect = track.getBoundingClientRect();
    let visibleIndex = 0;

    cards.forEach((card, index) => {
      const cardRect = card.getBoundingClientRect();
      const cardCenter = cardRect.left + cardRect.width / 2;

      if (cardCenter >= trackRect.left && cardCenter <= trackRect.right) {
        visibleIndex = index;
      }
    });

    swapText(currentEl, String(visibleIndex + 1));

    // Update arrow states
    const prevBtn = panel.querySelector('.explore-machines__arrow--prev');
    const nextBtn = panel.querySelector('.explore-machines__arrow--next');
    const isAtStart = track.scrollLeft <= 0;
    const isAtEnd = track.scrollLeft >= track.scrollWidth - track.clientWidth - 10;

    if (prevBtn) prevBtn.disabled = isAtStart;
    if (nextBtn) nextBtn.disabled = isAtEnd;
  }

  /**
   * Scroll the track in a direction. Snaps to the next/prev card's
   * offsetLeft so the resulting scrollLeft is always a valid snap point.
   * Why: programmatic `scrollBy` + `behavior: smooth` does not consistently
   * trigger `scroll-snap-type: mandatory` on mobile Safari, which leaves the
   * track parked between snap points (card hangs flush to the viewport edge).
   */
  function scrollTrack(panel, direction) {
    const track = panel.querySelector('.explore-machines__track');
    if (!track) return;

    const cards = Array.from(track.querySelectorAll(TRACK_CARD_SELECTOR));
    if (cards.length === 0) return;

    const current = track.scrollLeft;
    const targets = cards.map((card) => card.offsetLeft);

    let next;
    if (direction === 'next') {
      next = targets.find((offset) => offset > current + 1);
      if (next === undefined) next = targets[targets.length - 1];
    } else {
      const prev = [...targets].reverse().find((offset) => offset < current - 1);
      next = prev === undefined ? targets[0] : prev;
    }

    track.scrollTo({ left: next, behavior: 'smooth' });

    // Update counter after scroll completes
    setTimeout(() => updateCounter(panel, track), 350);
  }

  /**
   * Set up event listeners.
   */
  function setupEventListeners() {
    // Tab clicks
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

    // Arrow clicks (using event delegation for efficiency)
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

    // Track scroll events for counter updates (debounced for performance)
    panels.forEach((panel) => {
      const track = panel.querySelector('.explore-machines__track');
      if (track) {
        const debouncedUpdate = debounce(() => updateCounter(panel, track), config.debounceDelay);
        track.addEventListener('scroll', debouncedUpdate, { passive: true, signal });
      }
    });

    // Manual-activation tab pattern: arrow keys move focus only; Enter or
    // Space activates the focused tab. Switching a tab resets its panel's
    // scroll position, so manual activation lets keyboard users compare
    // tabs without losing their place.
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

  // Initialize
  setupEventListeners();
  initCounters();

  // Cleanup function for HMR
  return function cleanup() {
    controller.abort();
  };
}
