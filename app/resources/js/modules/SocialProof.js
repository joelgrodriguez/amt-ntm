/**
 * Social Proof Testimonial Slider
 *
 * Manual-navigation testimonial carousel. Dots are the only nav.
 * No autoplay (intentional: no quote length works for both the 18-word
 * and 40-word slides, and autoplay forces the user to watch instead of
 * engage). Removes the WCAG 2.2.2 pause-control requirement by removing
 * the auto-advance entirely.
 *
 * @file SocialProof.js
 *
 * @usage Front Page (front-page.php)
 * @usage Single Machine Product (single-machine.php)
 * @template templates/parts/front-page/social-proof.php
 * @template templates/woo/product/parts/testimonials.php
 */

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

  // Dot click handler (event delegation)
  section.addEventListener(
    'click',
    (e) => {
      const dot = e.target.closest('.social-proof__dot');
      if (!dot) return;

      const index = parseInt(dot.dataset.index, 10);
      if (index !== currentIndex) {
        goToSlide(index);
      }
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
