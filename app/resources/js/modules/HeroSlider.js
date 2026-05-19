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
  autoPlayInterval: 5000,
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

  const realCount = slides.length;

  // Clone the first slide and append to the end so the autoplay
  // loop can continue forward (slide N-1 -> visual slide 0 via the
  // clone, then snap back to the real slide 0 without animation).
  // Without this, advancing past the last slide rewinds visually
  // backwards through every prior slide.
  const firstClone = slides[0].cloneNode(true);
  firstClone.setAttribute('aria-hidden', 'true');
  firstClone.dataset.cloneOf = '0';
  // If the original slide 0 had its image hydrated already, the
  // clone keeps that <img>. If slide 0 was deferred (it isn't,
  // since slide 0 always renders eager), this clone would need
  // hydration. Slide 0 is always eager, so no work needed here.
  track.appendChild(firstClone);

  // State
  let currentIndex = 0;
  let autoPlayTimer = null;
  let isPlaying = false;
  let isSnapping = false;
  let touchStartX = 0;

  // AbortController for clean event listener removal
  const controller = new AbortController();
  const { signal } = controller;

  const prefersReducedMotion = window.matchMedia(
    '(prefers-reduced-motion: reduce)'
  ).matches;

  /**
   * Go to a specific slide. Supports the off-by-one clone position
   * (index === realCount) which animates to the cloned first slide
   * then snaps back to the real index 0 after the transition ends,
   * preserving forward motion direction on loop.
   */
  function goToSlide(index) {
    // Real index for state (segments, aria, hydration). The clone
    // position maps back to real index 0.
    const isCloneTarget = index === realCount;
    const realIndex = isCloneTarget
      ? 0
      : ((index % realCount) + realCount) % realCount;

    currentIndex = isCloneTarget ? realCount : realIndex;

    // Eager-hydrate the real target slide if it's a deferred placeholder.
    hydrateSlide(slides[realIndex]);

    track.style.transform = `translateX(-${currentIndex * 100}%)`;

    // Update segments to reflect the REAL slide (not the clone)
    segments.forEach((segment, i) => {
      const isActive = i === realIndex;
      segment.classList.toggle('hero-slider__segment--active', isActive);
      segment.setAttribute('aria-selected', String(isActive));
    });

    // Update slide visibility for screen readers
    slides.forEach((slide, i) => {
      slide.setAttribute('aria-hidden', String(i !== realIndex));
    });
  }

  /**
   * After animating to the clone, snap (no transition) back to the
   * real slide 0 so the next nextSlide() advances forward naturally.
   */
  function handleTransitionEnd() {
    if (currentIndex !== realCount || isSnapping) return;

    isSnapping = true;
    track.style.transition = 'none';
    currentIndex = 0;
    track.style.transform = 'translateX(0%)';

    // Force reflow, then restore transition next frame.
    void track.offsetHeight;
    requestAnimationFrame(() => {
      track.style.transition = '';
      isSnapping = false;
    });
  }

  function nextSlide() {
    // From the last real slide, advance into the clone position.
    // The transitionend handler snaps back to 0 without animation.
    if (currentIndex === realCount - 1) {
      goToSlide(realCount);
      return;
    }
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

    // Track end-of-transition for the clone-snap on loop
    track.addEventListener('transitionend', handleTransitionEnd, { signal });

    // Segment indicators
    segments.forEach((segment, index) => {
      segment.addEventListener('click', () => handleSegmentClick(index), { signal });
    });

    // Touch events
    slider.addEventListener('touchstart', handleTouchStart, { passive: true, signal });
    slider.addEventListener('touchend', handleTouchEnd, { passive: true, signal });

    // Keyboard navigation
    document.addEventListener('keydown', handleKeydown, { signal });

    // Pause on hover — scoped to the readable text region and the
    // chrome controls only. The slider is full-viewport, so binding
    // hover-pause to the whole element would never let autoplay run.
    // Hovering the photo or spec strip is fine; hovering the title /
    // CTA / dot row pauses (user is reading or about to click).
    const pauseZones = [
      ...slider.querySelectorAll('.hero-slider__content-inner'),
      slider.querySelector('.hero-slider__chrome'),
    ].filter(Boolean);
    pauseZones.forEach((zone) => {
      zone.addEventListener('mouseenter', stopAutoPlay, { signal });
      zone.addEventListener('mouseleave', startAutoPlay, { signal });
    });

    // Focus-within still pauses across the entire slider — keyboard
    // users tabbing through the nav / dots / CTA expect that.
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
   * scrolls back in. Stops setInterval ticks from running (and
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
