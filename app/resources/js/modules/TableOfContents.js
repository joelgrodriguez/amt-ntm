/**
 * Table of Contents Module
 *
 * Generates a sticky table of contents from post headings with scrollspy
 * highlighting. Uses IntersectionObserver for efficient scroll tracking.
 *
 * @module TableOfContents
 *
 * @usage Single Post (single.php)
 * @styles css/toc.css
 * @see css/single.css - Single post page styles
 */

/** @type {number} Header height plus padding for scroll offset */
const SCROLL_OFFSET = 80;

/** @type {string[]} Heading levels to include in TOC */
const HEADING_SELECTORS = ['h2', 'h3'];

/**
 * Default configuration options.
 * @type {Object}
 */
const defaults = {
  contentSelector: '[data-toc-content]',
  tocListSelector: '#toc-list',
  tocContainerSelector: '#table-of-contents',
  headingSelectors: HEADING_SELECTORS,
  activeClass: 'is-active',
  rootMargin: '-80px 0px -60% 0px',
  threshold: 0,
};

/**
 * Generates a URL-safe slug from text.
 *
 * @param {string} text - Text to slugify
 * @returns {string} URL-safe slug
 */
function slugify(text) {
  return text
    .toString()
    .toLowerCase()
    .trim()
    .replace(/\s+/g, '-')
    .replace(/[^\w-]+/g, '')
    .replace(/--+/g, '-')
    .replace(/^-+/, '')
    .replace(/-+$/, '');
}

/**
 * Initializes the table of contents.
 *
 * @param {Object} options - Configuration options
 * @returns {Function|undefined} Cleanup function for HMR
 */
export function initTableOfContents(options = {}) {
  const config = { ...defaults, ...options };

  const content = document.querySelector(config.contentSelector);
  const tocList = document.querySelector(config.tocListSelector);
  const tocContainer = document.querySelector(config.tocContainerSelector);

  if (!content || !tocList) return;
  const prefersReducedMotion = window.matchMedia(
    '(prefers-reduced-motion: reduce)'
  ).matches;
  const headingSelector = config.headingSelectors.join(', ');
  const headings = content.querySelectorAll(headingSelector);
  if (headings.length < 3) {
    if (tocContainer) tocContainer.hidden = true;
    return;
  }

  /** @type {Map<string, HTMLElement>} Maps heading IDs to TOC link elements */
  const tocLinks = new Map();

  /** @type {HTMLElement|null} Currently active TOC link */
  let activeLink = null;

  /** @type {IntersectionObserver|null} */
  let observer = null;

  /**
   * Builds TOC list from headings.
   */
  const buildTOC = () => {
    const fragment = document.createDocumentFragment();
    let index = 1;

    headings.forEach((heading) => {
      if (!heading.id) {
        const baseSlug = slugify(heading.textContent);
        heading.id = baseSlug || `section-${index}`;
      }

      const level = parseInt(heading.tagName.charAt(1), 10);
      const isNested = level === 3;

      const li = document.createElement('li');
      li.className = `toc__item${isNested ? ' toc__item--nested' : ''}`;

      const link = document.createElement('a');
      link.href = `#${heading.id}`;
      link.className = 'toc__link';
      link.textContent = heading.textContent;
      tocLinks.set(heading.id, link);

      li.appendChild(link);
      fragment.appendChild(li);

      index++;
    });

    tocList.appendChild(fragment);
  };

  /**
   * Sets the active TOC link.
   *
   * @param {string} headingId - ID of the heading to activate
   */
  const setActive = (headingId) => {
    const link = tocLinks.get(headingId);
    if (!link || link === activeLink) return;
    if (activeLink) {
      activeLink.classList.remove(config.activeClass);
      activeLink.removeAttribute('aria-current');
    }
    link.classList.add(config.activeClass);
    link.setAttribute('aria-current', 'true');
    activeLink = link;
  };

  /**
   * IntersectionObserver callback.
   *
   * @param {IntersectionObserverEntry[]} entries
   */
  const handleIntersection = (entries) => {
    const intersecting = entries.filter((entry) => entry.isIntersecting);

    if (intersecting.length > 0) {
      intersecting.sort((a, b) => {
        return a.boundingClientRect.top - b.boundingClientRect.top;
      });

      setActive(intersecting[0].target.id);
    }
  };

  /**
   * Handles TOC link clicks for smooth scrolling with offset.
   *
   * @param {Event} event - Click event
   */
  const handleClick = (event) => {
    const link = event.target.closest('a');
    if (!link) return;

    const targetId = link.getAttribute('href')?.slice(1);
    const target = targetId && document.getElementById(targetId);

    if (target) {
      event.preventDefault();

      const targetPosition = target.getBoundingClientRect().top + window.scrollY;
      const offsetPosition = targetPosition - SCROLL_OFFSET;

      window.scrollTo({
        top: offsetPosition,
        behavior: prefersReducedMotion ? 'auto' : 'smooth',
      });
      history.pushState(null, '', `#${targetId}`);
      setActive(targetId);
      target.setAttribute('tabindex', '-1');
      target.focus({ preventScroll: true });
    }
  };

  /**
   * Sets up IntersectionObserver for scrollspy.
   */
  const setupObserver = () => {
    observer = new IntersectionObserver(handleIntersection, {
      rootMargin: config.rootMargin,
      threshold: config.threshold,
    });

    headings.forEach((heading) => observer.observe(heading));
  };

  /**
   * Handles initial hash in URL.
   */
  const handleInitialHash = () => {
    const hash = window.location.hash.slice(1);
    if (hash && tocLinks.has(hash)) {
      setActive(hash);
    } else if (headings.length > 0) {
      setActive(headings[0].id);
    }
  };
  buildTOC();
  setupObserver();
  handleInitialHash();
  tocList.addEventListener('click', handleClick);

  /**
   * Cleanup function for HMR.
   *
   * @returns {void}
   */
  return () => {
    if (observer) {
      observer.disconnect();
    }
    tocList.removeEventListener('click', handleClick);
    tocList.innerHTML = '';
    tocLinks.clear();
  };
}
