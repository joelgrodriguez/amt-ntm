/**
 * Machine Product Entry Point
 *
 * Loads machine-product-only styles and behavior.
 */

import '../css/machine-product.css';

import { initCarouselNav } from './modules/CarouselNav.js';
import { initFloatingQuoteCta } from './modules/FloatingQuoteCta.js';
import { initMachineSubnav } from './modules/MachineSubnav.js';
import { initSocialProof, cleanup as cleanupSocialProof } from './modules/SocialProof.js';

let carouselNavCleanup = null;
let floatingQuoteCtaCleanup = null;
let machineSubnavCleanup = null;

const domReady = (callback) => {
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', callback, { once: true });
  } else {
    callback();
  }
};

const initMachineProduct = () => {
  carouselNavCleanup = initCarouselNav();
  floatingQuoteCtaCleanup = initFloatingQuoteCta();
  machineSubnavCleanup = initMachineSubnav();
  initSocialProof();
};

domReady(initMachineProduct);

if (import.meta.hot) {
  import.meta.hot.accept(() => {
    carouselNavCleanup?.();
    floatingQuoteCtaCleanup?.();
    machineSubnavCleanup?.();
    cleanupSocialProof();
    initMachineProduct();
  });
}
