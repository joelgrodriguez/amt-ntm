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
  autoPlayInterval: 7000,
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
  const firstClone = slides[0].cloneNode(true);
  firstClone.setAttribute('aria-hidden', 'true');
  // The clone is purely visual (wrap-around glide): strip the duplicated
  // id and keep its CTA link permanently out of the tab order.
  firstClone.removeAttribute('id');
  firstClone.setAttribute('inert', '');
  firstClone.dataset.cloneOf = '0';
  track.appendChild(firstClone);
  let currentIndex = 0;
  let autoPlayTimer = null;
  let isPlaying = false;
  let userPaused = false;
  let isSnapping = false;
  let touchStartX = 0;
  const controller = new AbortController();
  const { signal } = controller;

  const reducedMotionQuery = window.matchMedia(
    '(prefers-reduced-motion: reduce)'
  );
  userPaused = reducedMotionQuery.matches;

  function syncVideoPlayback() {
    slider.querySelectorAll('video').forEach((video) => {
      if (!(video instanceof HTMLVideoElement)) return;
      if (userPaused) {
        video.pause();
        return;
      }
      video.play().catch(() => {});
    });
  }

  /**
   * Go to a specific slide. Supports the off-by-one clone position
   * (index === realCount) which animates to the cloned first slide
   * then snaps back to the real index 0 after the transition ends,
   * preserving forward motion direction on loop.
   */
  function goToSlide(index) {
    const isCloneTarget = index === realCount;
    const realIndex = isCloneTarget
      ? 0
      : ((index % realCount) + realCount) % realCount;

    currentIndex = isCloneTarget ? realCount : realIndex;
    hydrateSlide(slides[realIndex]);
    // Keep one slide pre-hydrated ahead so autoplay/next never shows a
    // still-loading image, without fetching the whole deck up front.
    hydrateSlide(slides[(realIndex + 1) % realCount]);

    track.style.transform = `translateX(-${currentIndex * 100}%)`;
    segments.forEach((segment, i) => {
      const isActive = i === realIndex;
      segment.classList.toggle('hero-slider__segment--active', isActive);
      segment.setAttribute('aria-selected', String(isActive));
    });
    slides.forEach((slide, i) => {
      const isHidden = i !== realIndex;
      slide.setAttribute('aria-hidden', String(isHidden));
      // Hidden slides contain a focusable CTA link; inert keeps keyboard
      // focus from landing on off-screen content (WCAG 2.4.3 / 4.1.2).
      slide.toggleAttribute('inert', isHidden);
    });
    syncVideoPlayback();
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
    void track.offsetHeight;
    requestAnimationFrame(() => {
      track.style.transition = '';
      isSnapping = false;
    });
  }

  function nextSlide() {
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
    if (userPaused || isPlaying) {
      syncVideoPlayback();
      return;
    }

    isPlaying = true;
    slider.classList.add('hero-slider--playing');
    autoPlayTimer = setInterval(nextSlide, config.autoPlayInterval);
    syncVideoPlayback();
  }

  function stopAutoPlay() {
    if (isPlaying) {
      isPlaying = false;
      slider.classList.remove('hero-slider--playing');
      clearInterval(autoPlayTimer);
      autoPlayTimer = null;
    }
    syncVideoPlayback();
  }

  function setUserPaused(paused) {
    userPaused = paused;
    if (userPaused) {
      stopAutoPlay();
    } else {
      startAutoPlay();
    }
  }

  function handleNavClick(direction) {
    direction === 'next' ? nextSlide() : prevSlide();
    setUserPaused(true);
  }

  function handleSegmentClick(index) {
    goToSlide(index);
    setUserPaused(true);
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
      setUserPaused(true);
    }
  }

  function handleFocusOut(e) {
    if (!slider.contains(e.relatedTarget)) {
      startAutoPlay();
    }
  }

  function setupEventListeners() {
    prevBtn?.addEventListener('click', () => handleNavClick('prev'), { signal });
    nextBtn?.addEventListener('click', () => handleNavClick('next'), { signal });
    track.addEventListener('transitionend', handleTransitionEnd, { signal });
    segments.forEach((segment, index) => {
      segment.addEventListener('click', () => handleSegmentClick(index), { signal });
    });
    slider.addEventListener('touchstart', handleTouchStart, { passive: true, signal });
    slider.addEventListener('touchend', handleTouchEnd, { passive: true, signal });
    document.addEventListener('keydown', handleKeydown, { signal });
    const pauseZones = [
      ...slider.querySelectorAll('.hero__content-inner'),
      slider.querySelector('.hero-slider__chrome'),
    ].filter(Boolean);
    pauseZones.forEach((zone) => {
      zone.addEventListener('mouseenter', stopAutoPlay, { signal });
      zone.addEventListener('mouseleave', startAutoPlay, { signal });
    });
    slider.addEventListener('focusin', stopAutoPlay, { signal });
    slider.addEventListener('focusout', handleFocusOut, { signal });
    reducedMotionQuery.addEventListener('change', (e) => {
      if (e.matches) {
        setUserPaused(true);
      } else {
        startAutoPlay();
      }
    }, { signal });
  }

  function setAriaAttributes() {
    slider.setAttribute('role', 'region');
    slider.setAttribute('aria-roledescription', 'carousel');
    slider.setAttribute('aria-label', 'Featured machines');

    slides.forEach((slide, i) => {
      // Tabbed-carousel pattern (APG): the pagination dots are tabs, so
      // each slide is the tabpanel its dot's aria-controls points at.
      slide.setAttribute('role', 'tabpanel');
      slide.setAttribute('aria-label', `Slide ${i + 1} of ${slides.length}`);
      slide.setAttribute('aria-hidden', String(i !== 0));
      slide.toggleAttribute('inert', i !== 0);
    });

    segments.forEach((segment, i) => {
      segment.setAttribute('role', 'tab');
      segment.setAttribute('aria-label', `Go to slide ${i + 1}`);
      segment.setAttribute('aria-selected', String(i === 0));
    });
  }

  /**
   * Hydrate a single deferred slide's media. The server renders the full
   * responsive markup (srcset/sizes via responsive_image) into an inert
   * <template> per slide; hydration just moves it into the photo region,
   * below the overlay. Nothing downloads until the template is unpacked.
   *
   * No-op if the slide was already hydrated (the template is removed on
   * first hydration). Safe to call multiple times.
   */
  function hydrateSlide(slide) {
    if (!slide) return;

    const template = slide.querySelector('template.hero-slide__media-template');
    if (!template) return;

    const photo = slide.querySelector('.hero__photo');
    if (!photo) {
      template.remove();
      return;
    }

    const media = document.importNode(template.content, true);
    const overlay = photo.querySelector('.hero-overlay');
    photo.insertBefore(media, overlay || photo.firstChild);
    template.remove();

    // Parsed `muted` markup doesn't reliably set the IDL property; enforce
    // it so autoplay isn't blocked, then let syncVideoPlayback() drive play.
    const video = photo.querySelector('video');
    if (video) video.muted = true;
  }

  /**
   * Idle safety net: pre-hydrate only the slide after the current one.
   * goToSlide keeps one slide ahead hydrated from then on, so the deck
   * loads as it's watched instead of all at once on page load.
   */
  function hydrateDeferredSlides() {
    if (realCount < 2) return;
    hydrateSlide(slides[(currentIndex + 1) % realCount]);
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
  setAriaAttributes();
  setupEventListeners();
  syncVideoPlayback();
  startAutoPlay();
  observeViewport();
  if ('requestIdleCallback' in window) {
    window.requestIdleCallback(hydrateDeferredSlides, { timeout: 2000 });
  } else {
    setTimeout(hydrateDeferredSlides, 1000);
  }
  return function cleanup() {
    stopAutoPlay();
    controller.abort();
  };
}
