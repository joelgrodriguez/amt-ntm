/**
 * Video Facade
 *
 * Replaces a [data-video-facade] placeholder button with a Wistia iframe
 * on first click. Loads the Wistia E-v1.js helper only when needed so it
 * doesn't sit on the LCP path for visitors who never play the video.
 *
 * Markup contract (from templates/parts/hero-category.php):
 *   <button data-video-facade data-video-url="https://fast.wistia.net/embed/iframe/…">
 *     <img …>  <!-- poster -->
 *     <span class="video-facade__play">…</span>
 *   </button>
 *
 * @file VideoFacade.js
 */

let wistiaScriptLoaded = false;

/**
 * Inject the Wistia helper script exactly once.
 * @returns {void}
 */
const loadWistiaScript = () => {
  if (wistiaScriptLoaded) {
    return;
  }
  const script = document.createElement('script');
  script.src = 'https://fast.wistia.net/assets/external/E-v1.js';
  script.async = true;
  document.head.appendChild(script);
  wistiaScriptLoaded = true;
};

/**
 * Append autoPlay=true (Wistia's casing) to the iframe URL so the video
 * starts as soon as it mounts. The user already opted in by clicking.
 *
 * @param {string} url
 * @returns {string}
 */
const withAutoplay = (url) => {
  const separator = url.includes('?') ? '&' : '?';
  return `${url}${separator}autoPlay=true`;
};

/**
 * Swap a facade button for the actual iframe on click.
 *
 * @param {HTMLButtonElement} button
 * @returns {void}
 */
const activate = (button) => {
  const url = button.dataset.videoUrl;
  if (!url) {
    return;
  }

  const iframe = document.createElement('iframe');
  iframe.src = withAutoplay(url);
  iframe.setAttribute('allow', 'autoplay; fullscreen');
  iframe.setAttribute('allowfullscreen', '');
  iframe.setAttribute('frameborder', '0');
  iframe.setAttribute('scrolling', 'no');
  iframe.setAttribute('name', 'wistia_embed');
  iframe.setAttribute('allowtransparency', 'true');

  loadWistiaScript();
  button.replaceWith(iframe);
};

/**
 * Initialize all video facades on the page.
 *
 * @returns {() => void} cleanup function
 */
export const initVideoFacade = () => {
  const buttons = document.querySelectorAll('[data-video-facade]');
  if (buttons.length === 0) {
    return () => {};
  }

  const handlers = [];

  buttons.forEach((button) => {
    if (!(button instanceof HTMLButtonElement)) {
      return;
    }
    const handler = () => activate(button);
    button.addEventListener('click', handler, { once: true });
    handlers.push({ button, handler });
  });

  return () => {
    handlers.forEach(({ button, handler }) => {
      button.removeEventListener('click', handler);
    });
  };
};
