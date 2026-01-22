/**
 * Hero Slider Module
 *
 * Full-viewport hero slider with auto-play, touch support,
 * and keyboard navigation.
 *
 * @module HeroSlider
 *
 * @usage Front Page (front-page.php)
 * @template templates/parts/front-page/hero-slider.php
 * @styles css/hero-slider.css
 */

const DEFAULTS = {
  selector: '.hero-slider',
  autoPlayInterval: 8000,
  swipeThreshold: 50,
};

/**
 * Initializes the hero slider functionality.
 *
 * @param {Object} options - Configuration options
 * @param {string} [options.selector] - CSS selector for slider container
 * @param {number} [options.autoPlayInterval] - Auto-play interval in ms
 * @returns {Function|void} Cleanup function for HMR, or void if no slider found
 */
export function initHeroSlider(options = {}) {
  const config = { ...DEFAULTS, ...options };
  const slider = document.querySelector(config.selector);

  if (!slider) return;

  const track = slider.querySelector('.hero-slider__track');
  const slides = slider.querySelectorAll('.hero-slider__slide');
  const prevBtn = slider.querySelector('.hero-slider__nav--prev');
  const nextBtn = slider.querySelector('.hero-slider__nav--next');
  const segments = slider.querySelectorAll('.hero-slider__segment');

  if (!track || slides.length === 0) return;

  // State
  let currentIndex = 0;
  let autoPlayTimer = null;
  let isPlaying = false;
  let touchStartX = 0;

  // AbortController for clean event listener removal
  const controller = new AbortController();
  const { signal } = controller;

  const prefersReducedMotion = window.matchMedia(
    '(prefers-reduced-motion: reduce)'
  ).matches;

  /**
   * Go to a specific slide.
   */
  function goToSlide(index) {
    // Wrap around
    currentIndex = ((index % slides.length) + slides.length) % slides.length;
    track.style.transform = `translateX(-${currentIndex * 100}%)`;

    // Update segments
    segments.forEach((segment, i) => {
      const isActive = i === currentIndex;
      const fill = segment.querySelector('.hero-slider__segment-fill');

      segment.classList.toggle('hero-slider__segment--active', isActive);
      segment.setAttribute('aria-selected', String(isActive));

      // Reset animation on active segment
      if (fill) {
        fill.style.animation = 'none';
        fill.offsetHeight; // Trigger reflow
        fill.style.animation = '';
      }
    });

    // Update slide visibility for screen readers
    slides.forEach((slide, i) => {
      slide.setAttribute('aria-hidden', String(i !== currentIndex));
    });
  }

  function nextSlide() {
    goToSlide(currentIndex + 1);
  }

  function prevSlide() {
    goToSlide(currentIndex - 1);
  }

  function startAutoPlay() {
    if (prefersReducedMotion || isPlaying) return;

    isPlaying = true;
    slider.classList.add('hero-slider--playing');
    autoPlayTimer = setInterval(nextSlide, config.autoPlayInterval);
  }

  function stopAutoPlay() {
    if (!isPlaying) return;

    isPlaying = false;
    slider.classList.remove('hero-slider--playing');
    clearInterval(autoPlayTimer);
    autoPlayTimer = null;
  }

  function handleNavClick(direction) {
    direction === 'next' ? nextSlide() : prevSlide();
    stopAutoPlay();
  }

  function handleSegmentClick(index) {
    goToSlide(index);
    stopAutoPlay();
  }

  function handleTouchStart(e) {
    touchStartX = e.changedTouches[0].screenX;
    stopAutoPlay();
  }

  function handleTouchEnd(e) {
    const diff = touchStartX - e.changedTouches[0].screenX;

    if (Math.abs(diff) > config.swipeThreshold) {
      diff > 0 ? nextSlide() : prevSlide();
    }

    startAutoPlay();
  }

  function handleKeydown(e) {
    if (!slider.contains(document.activeElement)) return;

    if (e.key === 'ArrowLeft' || e.key === 'ArrowRight') {
      e.key === 'ArrowRight' ? nextSlide() : prevSlide();
      stopAutoPlay();
    }
  }

  function handleFocusOut(e) {
    if (!slider.contains(e.relatedTarget)) {
      startAutoPlay();
    }
  }

  function setupEventListeners() {
    // Navigation buttons
    prevBtn?.addEventListener('click', () => handleNavClick('prev'), { signal });
    nextBtn?.addEventListener('click', () => handleNavClick('next'), { signal });

    // Segment indicators
    segments.forEach((segment, index) => {
      segment.addEventListener('click', () => handleSegmentClick(index), { signal });
    });

    // Touch events
    slider.addEventListener('touchstart', handleTouchStart, { passive: true, signal });
    slider.addEventListener('touchend', handleTouchEnd, { passive: true, signal });

    // Keyboard navigation
    document.addEventListener('keydown', handleKeydown, { signal });

    // Pause on hover/focus
    slider.addEventListener('mouseenter', stopAutoPlay, { signal });
    slider.addEventListener('mouseleave', startAutoPlay, { signal });
    slider.addEventListener('focusin', stopAutoPlay, { signal });
    slider.addEventListener('focusout', handleFocusOut, { signal });
  }

  function setAriaAttributes() {
    slider.setAttribute('role', 'region');
    slider.setAttribute('aria-roledescription', 'carousel');
    slider.setAttribute('aria-label', 'Featured machines');

    slides.forEach((slide, i) => {
      slide.setAttribute('role', 'group');
      slide.setAttribute('aria-roledescription', 'slide');
      slide.setAttribute('aria-label', `Slide ${i + 1} of ${slides.length}`);
      slide.setAttribute('aria-hidden', String(i !== 0));
    });

    segments.forEach((segment, i) => {
      segment.setAttribute('role', 'tab');
      segment.setAttribute('aria-label', `Go to slide ${i + 1}`);
      segment.setAttribute('aria-selected', String(i === 0));
    });
  }

  // Initialize
  setAriaAttributes();
  setupEventListeners();
  startAutoPlay();

  // Cleanup function for HMR
  return function cleanup() {
    stopAutoPlay();
    controller.abort();
  };
}
