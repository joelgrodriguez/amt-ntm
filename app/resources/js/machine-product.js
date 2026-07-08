/**
 * Machine Product Entry Point
 *
 * Loads machine-product-only styles and behavior. CarouselNav now lives
 * in _app.js so accessory pages can use the same carousel pattern.
 */

import '../css/machine-product.css';

import { initMachineSubnav } from './modules/MachineSubnav.js';
import { initSocialProof, cleanup as cleanupSocialProof } from './modules/SocialProof.js';
import { initHeroVideo } from './modules/HeroVideo.js';

let machineSubnavCleanup = null;
let heroVideoCleanup = null;

const domReady = (callback) => {
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', callback, { once: true });
  } else {
    callback();
  }
};

const initMachineProduct = () => {
  machineSubnavCleanup = initMachineSubnav();
  heroVideoCleanup = initHeroVideo();
  initSocialProof();
};

domReady(initMachineProduct);

if (import.meta.hot) {
  import.meta.hot.accept(() => {
    machineSubnavCleanup?.();
    heroVideoCleanup?.();
    cleanupSocialProof();
    initMachineProduct();
  });
}
