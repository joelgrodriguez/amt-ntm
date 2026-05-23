/**
 * Front Page Entry Point
 *
 * Loads front-page-only styles and behavior.
 */

import '../css/front-page.css';

import { initHeroSlider } from './modules/HeroSlider.js';
import { initSocialProof, cleanup as cleanupSocialProof } from './modules/SocialProof.js';
import { initContactLazy, cleanup as cleanupContactLazy } from './modules/ContactLazy.js';

let heroSliderCleanup = null;

const domReady = (callback) => {
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', callback, { once: true });
  } else {
    callback();
  }
};

const initFrontPage = () => {
  heroSliderCleanup = initHeroSlider();
  initSocialProof();
  initContactLazy();
};

domReady(initFrontPage);

if (import.meta.hot) {
  import.meta.hot.accept(() => {
    heroSliderCleanup?.();
    cleanupSocialProof();
    cleanupContactLazy();
    initFrontPage();
  });
}
