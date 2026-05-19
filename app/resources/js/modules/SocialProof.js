/**
 * Social Proof Testimonial Slider
 *
 * Auto-advancing testimonial carousel with dot navigation.
 * Autoplay only, no hover/focus pause (intentional per design).
 * Reduced-motion users get manual nav (autoplay disabled).
 *
 * @file SocialProof.js
 *
 * @usage Front Page (front-page.php)
 * @usage Single Machine Product (single-machine.php)
 * @template templates/parts/front-page/social-proof.php
 * @template templates/woo/product/parts/testimonials.php
 */

const AUTOPLAY_INTERVAL = 4000;

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

  cleanup();
  abortController = new AbortController();
  const { signal } = abortController;

  /**
   * Show slide at given index.
   * @param {number} index
   */
  function goToSlide(index) {
    // Hide current
    slides[currentIndex].classList.add('hidden');
    slides[currentIndex].setAttribute('aria-hidden', 'true');
    dots[currentIndex].classList.remove('bg-blue-500', 'w-8');
    dots[currentIndex].classList.add('bg-blue-200', 'w-3');
    dots[currentIndex].removeAttribute('aria-current');

    currentIndex = index;

    // Show new
    slides[currentIndex].classList.remove('hidden');
    slides[currentIndex].removeAttribute('aria-hidden');
    dots[currentIndex].classList.remove('bg-blue-200', 'w-3');
    dots[currentIndex].classList.add('bg-blue-500', 'w-8');
    dots[currentIndex].setAttribute('aria-current', 'true');
  }

  function nextSlide() {
    goToSlide((currentIndex + 1) % slides.length);
  }

  function startAutoplay() {
    stopAutoplay();
    autoplayTimer = setInterval(nextSlide, AUTOPLAY_INTERVAL);
  }

  function stopAutoplay() {
    if (autoplayTimer) {
      clearInterval(autoplayTimer);
      autoplayTimer = null;
    }
  }

  // Dot click handler (event delegation). Resets the autoplay timer
  // so the user gets the full interval to read the slide they picked.
  section.addEventListener(
    'click',
    (e) => {
      const dot = e.target.closest('.social-proof__dot');
      if (!dot) return;

      const index = parseInt(dot.dataset.index, 10);
      if (index !== currentIndex) {
        goToSlide(index);
        startAutoplay();
      }
    },
    { signal }
  );

  // Respect reduced motion: no autoplay.
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
