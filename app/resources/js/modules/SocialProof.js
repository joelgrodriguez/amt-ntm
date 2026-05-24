/**
 * Social Proof Testimonial Slider
 *
 * Manual-only testimonial carousel with chrome-bar dot navigation.
 * No autoplay: skeptical 50+ contractors don't want a carousel
 * advancing under them while they're reading a quote.
 *
 * Slides are stacked absolute-over-relative inside .social-proof__track
 * so we can opacity-fade between them instead of display-toggle.
 *
 * @file SocialProof.js
 *
 * @usage Front Page (front-page.php)
 * @usage Single Machine Product (single-machine.php)
 * @template templates/parts/front-page/social-proof.php
 * @template templates/woo/product/parts/testimonials.php
 */

let abortController = null;

const DOT_ACTIVE = ['bg-red', 'w-3'];
const DOT_INACTIVE = ['bg-blue-300', 'w-1'];
const SLIDE_ACTIVE = ['relative', 'opacity-100'];
const SLIDE_INACTIVE = ['absolute', 'inset-0', 'opacity-0', 'pointer-events-none'];

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

  section.addEventListener(
    'click',
    (e) => {
      const dot = e.target.closest('.social-proof__dot');
      if (!dot) return;

      const index = parseInt(dot.dataset.index, 10);
      goToSlide(index);
    },
    { signal }
  );
}

/**
 * Cleanup function for HMR support.
 */
export function cleanup() {
  if (abortController) {
    abortController.abort();
    abortController = null;
  }
}
