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
import { initScrollReveal } from './modules/ScrollReveal.js';

/** @type {Function|null} Cleanup function for mobile menu */
let mobileMenuCleanup = null;

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
  initScrollReveal();
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
    // Reinitialize
    initApp();
  });
}
