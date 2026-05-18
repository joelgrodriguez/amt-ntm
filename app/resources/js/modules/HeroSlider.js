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
  const pauseBtn = slider.querySelector('.hero-slider__pause');

  if (!track || slides.length === 0) return;

  // State
  let currentIndex = 0;
  let autoPlayTimer = null;
  let isPlaying = false;
  let userPaused = false;
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

    // Eager-hydrate the target slide if it's still a deferred placeholder.
    // requestIdleCallback may not have fired yet on slow devices.
    hydrateSlide(slides[currentIndex]);

    track.style.transform = `translateX(-${currentIndex * 100}%)`;

    // Update segments
    segments.forEach((segment, i) => {
      const isActive = i === currentIndex;
      segment.classList.toggle('hero-slider__segment--active', isActive);
      segment.setAttribute('aria-selected', String(isActive));
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
    if (prefersReducedMotion || isPlaying || userPaused) return;

    isPlaying = true;
    slider.classList.add('hero-slider--playing');
    autoPlayTimer = setInterval(nextSlide, config.autoPlayInterval);
  }

  function togglePause() {
    userPaused = !userPaused;
    slider.classList.toggle('hero-slider--paused', userPaused);

    if (userPaused) {
      stopAutoPlay();
      if (pauseBtn) {
        pauseBtn.setAttribute('aria-label', pauseBtn.dataset.labelPlay || 'Resume');
      }
    } else {
      if (pauseBtn) {
        pauseBtn.setAttribute('aria-label', pauseBtn.dataset.labelPause || 'Pause');
      }
      startAutoPlay();
    }
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

    // Pause / play toggle
    pauseBtn?.addEventListener('click', togglePause, { signal });

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

  /**
   * Hydrate a single deferred slide's media (image + optional video).
   * Media gets injected INSIDE the slide's photo region (which also
   * holds the overlay + content stack), not into the slide root —
   * the slide root now contains photo + spec band as siblings.
   *
   * No-op if the slide was already hydrated (data attrs are deleted
   * on first hydration). Safe to call multiple times.
   */
  function hydrateSlide(slide) {
    if (!slide || !slide.dataset.imageUrl) return;

    const photo = slide.querySelector('.hero-slider__photo');
    if (!photo) return;

    const imageUrl = slide.dataset.imageUrl;
    const imageAlt = slide.dataset.imageAlt || '';
    const videoUrl = slide.dataset.videoUrl;

    if (videoUrl) {
      const video = document.createElement('video');
      video.className = 'hero-slider__media hero-slider__video';
      video.autoplay = true;
      video.muted = true;
      video.loop = true;
      video.playsInline = true;
      if (imageUrl) video.poster = imageUrl;
      const source = document.createElement('source');
      source.src = videoUrl;
      source.type = 'video/mp4';
      video.appendChild(source);
      photo.insertBefore(video, photo.firstChild);
    }

    if (imageUrl) {
      const img = document.createElement('img');
      img.className = 'hero-slider__media hero-slider__image';
      img.src = imageUrl;
      img.alt = imageAlt;
      img.loading = 'lazy';
      img.decoding = 'async';
      // Insert before .hero-overlay so z-index stack stays correct.
      const overlay = photo.querySelector('.hero-overlay');
      photo.insertBefore(img, overlay || photo.firstChild);
    }

    delete slide.dataset.imageUrl;
    delete slide.dataset.imageAlt;
    delete slide.dataset.videoUrl;
  }

  /**
   * Hydrate all remaining deferred slides. Called from requestIdleCallback
   * as a safety net; goToSlide eager-hydrates on user interaction.
   */
  function hydrateDeferredSlides() {
    slides.forEach(hydrateSlide);
  }

  /**
   * Pause autoplay when the slider scrolls off-screen, resume when it
   * scrolls back in. Stops 8s setInterval ticks from running (and
   * hydrating slides via goToSlide) while no one is watching.
   *
   * Separate from the user-pause state: viewport invisibility does not
   * flip userPaused. If the user explicitly paused, startAutoPlay()
   * already short-circuits, so the observer's call is a no-op there.
   */
  function observeViewport() {
    if (!('IntersectionObserver' in window)) return;

    const observer = new IntersectionObserver(
      ([entry]) => {
        if (entry.isIntersecting) {
          startAutoPlay();
        } else {
          stopAutoPlay();
        }
      },
      { threshold: 0 }
    );
    observer.observe(slider);
    signal.addEventListener('abort', () => observer.disconnect());
  }

  // Initialize
  setAriaAttributes();
  setupEventListeners();
  startAutoPlay();
  observeViewport();

  // Hydrate non-first slides after the page is idle (post-LCP).
  if ('requestIdleCallback' in window) {
    window.requestIdleCallback(hydrateDeferredSlides, { timeout: 2000 });
  } else {
    setTimeout(hydrateDeferredSlides, 1000);
  }

  // Cleanup function for HMR
  return function cleanup() {
    stopAutoPlay();
    controller.abort();
  };
}
