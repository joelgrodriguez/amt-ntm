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
import { initAccordion } from './modules/Accordion.js';
import { initCarouselNav } from './modules/CarouselNav.js';
import { initHubspotForms } from './modules/HubspotForms.js';
import { initSearchModal } from './modules/SearchModal.js';
import { initVideoFacade } from './modules/VideoFacade.js';
import { initAvatarGroupHover } from './modules/AvatarGroupHover.js';

/** @type {Function|null} Cleanup function for mobile menu */
let mobileMenuCleanup = null;

/** @type {Function|null} Cleanup function for mega menu */
let megaMenuCleanup = null;

/** @type {Function|null} Cleanup function for table of contents */
let tocCleanup = null;

/** @type {Function|null} Cleanup function for accessories catalog nav */
let catalogNavCleanup = null;

/** @type {Function|null} Cleanup function for scroll header */
let scrollHeaderCleanup = null;

/** @type {Function|null} Cleanup function for scroll to top */
let scrollToTopCleanup = null;

/** @type {Function|null} Cleanup function for accordion */
let accordionCleanup = null;

/** @type {Function|null} Cleanup function for carousel nav */
let carouselNavCleanup = null;

/** @type {Function|null} Cleanup function for HubSpot forms */
let hubspotFormsCleanup = null;

/** @type {Function|null} Cleanup function for search modal */
let searchModalCleanup = null;

/** @type {Function|null} Cleanup function for video facade */
let videoFacadeCleanup = null;

/** @type {Function|null} Cleanup function for avatar group hover */
let avatarGroupHoverCleanup = null;

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
  // Accessories catalog jump-nav uses the same scrollspy in manual mode.
  catalogNavCleanup = initTableOfContents({
    tocListSelector: '#catalog-nav-list',
    tocContainerSelector: '#catalog-nav',
  });
  scrollHeaderCleanup = initScrollHeader();
  scrollToTopCleanup = initScrollToTop();
  accordionCleanup = initAccordion();
  carouselNavCleanup = initCarouselNav();
  hubspotFormsCleanup = initHubspotForms();
  searchModalCleanup = initSearchModal();
  videoFacadeCleanup = initVideoFacade();
  avatarGroupHoverCleanup = initAvatarGroupHover();
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
    if (catalogNavCleanup) {
      catalogNavCleanup();
    }
    if (scrollHeaderCleanup) {
      scrollHeaderCleanup();
    }
    if (scrollToTopCleanup) {
      scrollToTopCleanup();
    }
    if (accordionCleanup) {
      accordionCleanup();
    }
    if (carouselNavCleanup) {
      carouselNavCleanup();
    }
    if (hubspotFormsCleanup) {
      hubspotFormsCleanup();
    }
    if (searchModalCleanup) {
      searchModalCleanup();
    }
    if (videoFacadeCleanup) {
      videoFacadeCleanup();
    }
    if (avatarGroupHoverCleanup) {
      avatarGroupHoverCleanup();
    }
    // Reinitialize
    initApp();
  });
}
