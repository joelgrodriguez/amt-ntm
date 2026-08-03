/**
 * Hydrate Wistia facades only after an explicit play action.
 */

function addAutoplay(url) {
  const parsed = new URL(url, window.location.href);
  parsed.searchParams.set('autoplay', '1');
  parsed.searchParams.set('videoFoam', 'true');
  return parsed.toString();
}

export function initVideoFacades() {
  const controller = new AbortController();
  const { signal } = controller;

  document.querySelectorAll('[data-video-facade]').forEach((facade) => {
    const button = facade.querySelector('.video-facade__button');
    const source = facade.dataset.videoSrc;

    if (!(button instanceof HTMLButtonElement) || !source) {
      return;
    }

    button.addEventListener(
      'click',
      () => {
        const iframe = document.createElement('iframe');
        iframe.src = addAutoplay(source);
        iframe.title = button.getAttribute('aria-label') || 'Video';
        iframe.allow = 'autoplay; fullscreen';
        iframe.allowFullscreen = true;
        iframe.setAttribute('allowtransparency', 'true');
        iframe.setAttribute('frameborder', '0');
        iframe.setAttribute('scrolling', 'no');
        iframe.setAttribute('referrerpolicy', 'strict-origin-when-cross-origin');

        facade.replaceChildren(iframe);
        facade.classList.add('video-facade--loaded');
      },
      { once: true, signal }
    );
  });

  return () => controller.abort();
}
