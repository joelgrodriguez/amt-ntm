/**
 * Social Proof Testimonial Slider
 *
 * Auto-advancing testimonial carousel with dot navigation.
 *
 * @file SocialProof.js
 *
 * @usage Front Page (front-page.php)
 * @template templates/parts/front-page/social-proof.php
 */

const AUTOPLAY_INTERVAL = 6000;

let autoplayTimer = null;
let abortController = null;

/**
 * Initialize the social proof slider.
 */
export function initSocialProof() {
  const section = document.querySelector('.social-proof');
  if (!section) return;

  const slides = section.querySelectorAll('.social-proof__slide');
  const dots = section.querySelectorAll('.social-proof__dot');

  if (slides.length < 2) return;

  let currentIndex = 0;

  // Clean up previous instance (HMR support)
  cleanup();
  abortController = new AbortController();
  const { signal } = abortController;

  /**
   * Show slide at given index.
   * @param {number} index
   */
  function goToSlide(index) {
    // Hide current slide
    slides[currentIndex].classList.add('hidden');
    slides[currentIndex].classList.remove('block');
    dots[currentIndex].classList.remove('bg-secondary', 'w-8');
    dots[currentIndex].classList.add('bg-slate-600', 'w-3');

    currentIndex = index;

    // Show new slide
    slides[currentIndex].classList.remove('hidden');
    slides[currentIndex].classList.add('block');
    dots[currentIndex].classList.remove('bg-slate-600', 'w-3');
    dots[currentIndex].classList.add('bg-secondary', 'w-8');
  }

  /**
   * Advance to next slide.
   */
  function nextSlide() {
    const next = (currentIndex + 1) % slides.length;
    goToSlide(next);
  }

  /**
   * Start autoplay.
   */
  function startAutoplay() {
    stopAutoplay();
    autoplayTimer = setInterval(nextSlide, AUTOPLAY_INTERVAL);
  }

  /**
   * Stop autoplay.
   */
  function stopAutoplay() {
    if (autoplayTimer) {
      clearInterval(autoplayTimer);
      autoplayTimer = null;
    }
  }

  /**
   * Reset autoplay timer (called after user interaction).
   */
  function resetAutoplay() {
    startAutoplay();
  }

  // Dot click handler (event delegation)
  section.addEventListener(
    'click',
    (e) => {
      const dot = e.target.closest('.social-proof__dot');
      if (!dot) return;

      const index = parseInt(dot.dataset.index, 10);
      if (index !== currentIndex) {
        goToSlide(index);
        resetAutoplay();
      }
    },
    { signal }
  );

  // Pause on hover
  section.addEventListener('mouseenter', stopAutoplay, { signal });
  section.addEventListener('mouseleave', startAutoplay, { signal });

  // Pause on focus within
  section.addEventListener('focusin', stopAutoplay, { signal });
  section.addEventListener('focusout', startAutoplay, { signal });

  // Respect reduced motion
  const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
  if (!prefersReducedMotion.matches) {
    startAutoplay();
  }

  prefersReducedMotion.addEventListener(
    'change',
    (e) => {
      if (e.matches) {
        stopAutoplay();
      } else {
        startAutoplay();
      }
    },
    { signal }
  );
}

/**
 * Cleanup function for HMR support.
 */
export function cleanup() {
  if (autoplayTimer) {
    clearInterval(autoplayTimer);
    autoplayTimer = null;
  }
  if (abortController) {
    abortController.abort();
    abortController = null;
  }
}
