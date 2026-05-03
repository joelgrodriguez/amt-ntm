/**
 * Front Page Entry Point
 *
 * Loads front-page-only styles and behavior.
 */

import '../css/front-page.css';

import { initHeroSlider } from './modules/HeroSlider.js';
import { initExploreMachines } from './modules/ExploreMachines.js';
import { initSocialProof, cleanup as cleanupSocialProof } from './modules/SocialProof.js';

let heroSliderCleanup = null;
let exploreMachinesCleanup = null;

const domReady = (callback) => {
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', callback, { once: true });
  } else {
    callback();
  }
};

const initFrontPage = () => {
  heroSliderCleanup = initHeroSlider();
  exploreMachinesCleanup = initExploreMachines();
  initSocialProof();
};

domReady(initFrontPage);

if (import.meta.hot) {
  import.meta.hot.accept(() => {
    heroSliderCleanup?.();
    exploreMachinesCleanup?.();
    cleanupSocialProof();
    initFrontPage();
  });
}
