/**
 * Hero Slider Module
 *
 * Full-viewport hero slider with auto-play, touch support,
 * and keyboard navigation.
 *
 * @module HeroSlider
 */

/**
 * Default options for the hero slider.
 * @type {Object}
 */
const defaults = {
  selector: '.hero-slider',
  autoPlayInterval: 6000,
  transitionDuration: 500,
};

/**
 * Initializes the hero slider functionality.
 *
 * @param {Object} options - Configuration options
 * @param {string} [options.selector] - CSS selector for slider container
 * @param {number} [options.autoPlayInterval] - Auto-play interval in ms
 * @param {number} [options.transitionDuration] - Slide transition duration in ms
 * @returns {Function|void} Cleanup function for HMR, or void if no slider found
 */
export function initHeroSlider(options = {}) {
  const config = { ...defaults, ...options };
  const slider = document.querySelector(config.selector);

  if (!slider) return;

  // Elements
  const track = slider.querySelector('.hero-slider__track');
  const slides = slider.querySelectorAll('.hero-slider__slide');
  const prevBtn = slider.querySelector('.hero-slider__nav--prev');
  const nextBtn = slider.querySelector('.hero-slider__nav--next');
  const dots = slider.querySelectorAll('.hero-slider__dot');
  const progressBar = slider.querySelector('.hero-slider__progress-bar');

  if (!track || slides.length === 0) return;

  // State
  let currentIndex = 0;
  let autoPlayTimer = null;
  let isPlaying = true;
  let touchStartX = 0;
  let touchEndX = 0;

  // Check for reduced motion preference
  const prefersReducedMotion = window.matchMedia(
    '(prefers-reduced-motion: reduce)'
  ).matches;

  /**
   * Go to a specific slide.
   * @param {number} index - Target slide index
   */
  function goToSlide(index) {
    // Handle wrap-around
    if (index < 0) {
      index = slides.length - 1;
    } else if (index >= slides.length) {
      index = 0;
    }

    currentIndex = index;
    track.style.transform = `translateX(-${currentIndex * 100}%)`;

    // Update dots
    dots.forEach((dot, i) => {
      dot.classList.toggle('hero-slider__dot--active', i === currentIndex);
      dot.setAttribute('aria-selected', i === currentIndex ? 'true' : 'false');
    });

    // Update slide visibility for screen readers
    slides.forEach((slide, i) => {
      slide.setAttribute('aria-hidden', i !== currentIndex ? 'true' : 'false');
    });

    // Reset progress bar animation
    if (progressBar && isPlaying) {
      progressBar.style.animation = 'none';
      progressBar.offsetHeight; // Trigger reflow
      progressBar.style.animation = '';
    }
  }

  /**
   * Go to the next slide.
   */
  function nextSlide() {
    goToSlide(currentIndex + 1);
  }

  /**
   * Go to the previous slide.
   */
  function prevSlide() {
    goToSlide(currentIndex - 1);
  }

  /**
   * Start auto-play.
   */
  function startAutoPlay() {
    if (prefersReducedMotion) return;

    stopAutoPlay();
    isPlaying = true;
    slider.classList.add('hero-slider--playing');
    autoPlayTimer = setInterval(nextSlide, config.autoPlayInterval);
  }

  /**
   * Stop auto-play.
   */
  function stopAutoPlay() {
    isPlaying = false;
    slider.classList.remove('hero-slider--playing');
    if (autoPlayTimer) {
      clearInterval(autoPlayTimer);
      autoPlayTimer = null;
    }
  }

  /**
   * Handle touch start event.
   * @param {TouchEvent} e
   */
  function handleTouchStart(e) {
    touchStartX = e.changedTouches[0].screenX;
    stopAutoPlay();
  }

  /**
   * Handle touch end event.
   * @param {TouchEvent} e
   */
  function handleTouchEnd(e) {
    touchEndX = e.changedTouches[0].screenX;
    handleSwipe();
    startAutoPlay();
  }

  /**
   * Process swipe gesture.
   */
  function handleSwipe() {
    const swipeThreshold = 50;
    const diff = touchStartX - touchEndX;

    if (Math.abs(diff) > swipeThreshold) {
      if (diff > 0) {
        nextSlide();
      } else {
        prevSlide();
      }
    }
  }

  /**
   * Handle keyboard navigation.
   * @param {KeyboardEvent} e
   */
  function handleKeydown(e) {
    // Only handle when slider or its children are focused
    if (!slider.contains(document.activeElement)) return;

    switch (e.key) {
      case 'ArrowLeft':
        prevSlide();
        stopAutoPlay();
        break;
      case 'ArrowRight':
        nextSlide();
        stopAutoPlay();
        break;
    }
  }

  /**
   * Handle mouse enter - pause auto-play.
   */
  function handleMouseEnter() {
    stopAutoPlay();
  }

  /**
   * Handle mouse leave - resume auto-play.
   */
  function handleMouseLeave() {
    startAutoPlay();
  }

  /**
   * Handle focus within - pause auto-play for accessibility.
   */
  function handleFocusIn() {
    stopAutoPlay();
  }

  /**
   * Handle focus out - resume auto-play.
   * @param {FocusEvent} e
   */
  function handleFocusOut(e) {
    // Only resume if focus left the slider entirely
    if (!slider.contains(e.relatedTarget)) {
      startAutoPlay();
    }
  }

  // Set up event listeners
  function setupEventListeners() {
    // Navigation buttons
    if (prevBtn) {
      prevBtn.addEventListener('click', () => {
        prevSlide();
        stopAutoPlay();
      });
    }

    if (nextBtn) {
      nextBtn.addEventListener('click', () => {
        nextSlide();
        stopAutoPlay();
      });
    }

    // Dot indicators
    dots.forEach((dot, index) => {
      dot.addEventListener('click', () => {
        goToSlide(index);
        stopAutoPlay();
      });
    });

    // Touch events for swipe
    slider.addEventListener('touchstart', handleTouchStart, { passive: true });
    slider.addEventListener('touchend', handleTouchEnd, { passive: true });

    // Keyboard navigation
    document.addEventListener('keydown', handleKeydown);

    // Pause on hover/focus
    slider.addEventListener('mouseenter', handleMouseEnter);
    slider.addEventListener('mouseleave', handleMouseLeave);
    slider.addEventListener('focusin', handleFocusIn);
    slider.addEventListener('focusout', handleFocusOut);
  }

  // Initialize
  function init() {
    // Set initial ARIA attributes
    slider.setAttribute('role', 'region');
    slider.setAttribute('aria-roledescription', 'carousel');
    slider.setAttribute('aria-label', 'Featured machines');

    slides.forEach((slide, i) => {
      slide.setAttribute('role', 'group');
      slide.setAttribute('aria-roledescription', 'slide');
      slide.setAttribute('aria-label', `Slide ${i + 1} of ${slides.length}`);
      slide.setAttribute('aria-hidden', i !== 0 ? 'true' : 'false');
    });

    dots.forEach((dot, i) => {
      dot.setAttribute('role', 'tab');
      dot.setAttribute('aria-label', `Go to slide ${i + 1}`);
      dot.setAttribute('aria-selected', i === 0 ? 'true' : 'false');
    });

    // Set up event listeners
    setupEventListeners();

    // Start auto-play
    startAutoPlay();

    // Initialize first slide
    goToSlide(0);
  }

  // Cleanup function for HMR
  function cleanup() {
    stopAutoPlay();

    if (prevBtn) {
      prevBtn.replaceWith(prevBtn.cloneNode(true));
    }
    if (nextBtn) {
      nextBtn.replaceWith(nextBtn.cloneNode(true));
    }

    dots.forEach((dot) => {
      dot.replaceWith(dot.cloneNode(true));
    });

    slider.removeEventListener('touchstart', handleTouchStart);
    slider.removeEventListener('touchend', handleTouchEnd);
    slider.removeEventListener('mouseenter', handleMouseEnter);
    slider.removeEventListener('mouseleave', handleMouseLeave);
    slider.removeEventListener('focusin', handleFocusIn);
    slider.removeEventListener('focusout', handleFocusOut);
    document.removeEventListener('keydown', handleKeydown);
  }

  init();

  return cleanup;
}
