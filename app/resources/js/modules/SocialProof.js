/**
 * Social Proof Testimonial Slider
 *
 * Auto-advancing testimonial carousel with chrome-bar dot navigation.
 * Pauses on hover/focus so users can finish reading. Manual nav (dot
 * click) resets the autoplay timer so the chosen slide gets the full
 * interval. Reduced-motion users get manual-only.
 *
 * Slides are grid-stacked (grid-area: 1/1) inside .social-proof__track
 * and cross-fade via opacity. Track auto-sizes to the tallest slide;
 * no layout shift between slides.
 *
 * @file SocialProof.js
 *
 * @usage Front Page (front-page.php)
 * @usage Single Machine Product (single-machine.php)
 * @template templates/parts/front-page/social-proof.php
 * @template templates/woo/product/parts/testimonials.php
 */

const AUTOPLAY_INTERVAL = 6500;

let autoplayTimer = null;
let abortController = null;

const DOT_ACTIVE = ['bg-red', 'w-3'];
const DOT_INACTIVE = ['bg-blue-300', 'w-1'];
const SLIDE_ACTIVE = ['opacity-100'];
const SLIDE_INACTIVE = ['opacity-0', 'pointer-events-none'];

/**
 * Initialize the social proof slider.
 */
export function initSocialProof() {
  const section = document.querySelector('.social-proof');
  if (!section) return;

  const slides = section.querySelectorAll('.social-proof__slide');
  const dots = section.querySelectorAll('.social-proof__dot');
  const currentLabel = section.querySelector('[data-current]');

  if (slides.length < 2) return;

  let currentIndex = 0;

  cleanup();
  abortController = new AbortController();
  const { signal } = abortController;

  const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

  /**
   * Show slide at given index.
   * @param {number} index
   */
  function goToSlide(index) {
    if (index === currentIndex) return;

    slides[currentIndex].classList.remove(...SLIDE_ACTIVE);
    slides[currentIndex].classList.add(...SLIDE_INACTIVE);
    slides[currentIndex].setAttribute('aria-hidden', 'true');
    dots[currentIndex].classList.remove(...DOT_ACTIVE);
    dots[currentIndex].classList.add(...DOT_INACTIVE);
    dots[currentIndex].removeAttribute('aria-current');

    currentIndex = index;

    slides[currentIndex].classList.remove(...SLIDE_INACTIVE);
    slides[currentIndex].classList.add(...SLIDE_ACTIVE);
    slides[currentIndex].removeAttribute('aria-hidden');
    dots[currentIndex].classList.remove(...DOT_INACTIVE);
    dots[currentIndex].classList.add(...DOT_ACTIVE);
    dots[currentIndex].setAttribute('aria-current', 'true');

    if (currentLabel) {
      currentLabel.textContent = String(currentIndex + 1);
    }
  }

  function nextSlide() {
    goToSlide((currentIndex + 1) % slides.length);
  }

  function startAutoplay() {
    stopAutoplay();
    if (prefersReducedMotion.matches) return;
    autoplayTimer = setInterval(nextSlide, AUTOPLAY_INTERVAL);
  }

  function stopAutoplay() {
    if (autoplayTimer) {
      clearInterval(autoplayTimer);
      autoplayTimer = null;
    }
  }

  // Manual nav: jump to the chosen slide and reset the timer so the
  // user gets the full interval to read what they picked.
  section.addEventListener(
    'click',
    (e) => {
      const dot = e.target.closest('.social-proof__dot');
      if (!dot) return;

      const index = parseInt(dot.dataset.index, 10);
      goToSlide(index);
      startAutoplay();
    },
    { signal }
  );

  // Pause on hover/focus so readers can finish a quote.
  section.addEventListener('mouseenter', stopAutoplay, { signal });
  section.addEventListener('mouseleave', startAutoplay, { signal });
  section.addEventListener('focusin', stopAutoplay, { signal });
  section.addEventListener('focusout', startAutoplay, { signal });

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

  startAutoplay();
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
