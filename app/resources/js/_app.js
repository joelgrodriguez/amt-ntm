/**
 * Main Application Entry Point
 *
 * Entry point for Vite bundling. Imports CSS for HMR and initializes modules.
 *
 * @file app.js
 */

// Styles
import '../css/_app.css';

// Modules
import { initMobileMenu } from './modules/MobileMenu.js';
import { initMegaMenu } from './modules/MegaMenu.js';
import { initScrollReveal } from './modules/ScrollReveal.js';
import { initTableOfContents } from './modules/TableOfContents.js';
import { init as initScrollHeader } from './modules/ScrollHeader.js';
import { init as initScrollToTop } from './modules/ScrollToTop.js';
import { initHeroSlider } from './modules/HeroSlider.js';
import { initExploreMachines } from './modules/ExploreMachines.js';

/** @type {Function|null} Cleanup function for mobile menu */
let mobileMenuCleanup = null;

/** @type {Function|null} Cleanup function for mega menu */
let megaMenuCleanup = null;

/** @type {Function|null} Cleanup function for table of contents */
let tocCleanup = null;

/** @type {Function|null} Cleanup function for scroll header */
let scrollHeaderCleanup = null;

/** @type {Function|null} Cleanup function for scroll to top */
let scrollToTopCleanup = null;

/** @type {Function|null} Cleanup function for hero slider */
let heroSliderCleanup = null;

/** @type {Function|null} Cleanup function for explore machines */
let exploreMachinesCleanup = null;

/**
 * Executes callback when DOM is ready.
 *
 * @param {Function} callback - Function to execute
 * @returns {void}
 */
const domReady = (callback) => {
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', callback, { once: true });
  } else {
    callback();
  }
};

/**
 * Initializes all application modules.
 *
 * @returns {void}
 */
const initApp = () => {
  mobileMenuCleanup = initMobileMenu();
  megaMenuCleanup = initMegaMenu();
  initScrollReveal();
  tocCleanup = initTableOfContents();
  scrollHeaderCleanup = initScrollHeader();
  scrollToTopCleanup = initScrollToTop();
  heroSliderCleanup = initHeroSlider();
  exploreMachinesCleanup = initExploreMachines();
};

// Bootstrap
domReady(initApp);

// HMR - cleanup and reinitialize on hot reload
if (import.meta.hot) {
  import.meta.hot.accept(() => {
    // Cleanup previous module instances
    if (mobileMenuCleanup) {
      mobileMenuCleanup();
    }
    if (megaMenuCleanup) {
      megaMenuCleanup();
    }
    if (tocCleanup) {
      tocCleanup();
    }
    if (scrollHeaderCleanup) {
      scrollHeaderCleanup();
    }
    if (scrollToTopCleanup) {
      scrollToTopCleanup();
    }
    if (heroSliderCleanup) {
      heroSliderCleanup();
    }
    if (exploreMachinesCleanup) {
      exploreMachinesCleanup();
    }
    // Reinitialize
    initApp();
  });
}
