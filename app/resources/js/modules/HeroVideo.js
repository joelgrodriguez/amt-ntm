/**
 * Hero Video — lazy, considerate autoplay.
 *
 * The machine-product hero ships an MP4 for some machines (MACH II). The
 * old template autoplayed it unconditionally with preload="metadata",
 * burning bandwidth for a contractor reading the page on cellular.
 *
 * This module:
 *  - Reads `data-hero-video-src` and only attaches a <source> + play
 *    when the user has opted into motion (prefers-reduced-motion: no-pref)
 *    AND is not on a low-data connection (Save-Data, "2g"/"slow-2g").
 *  - Uses IntersectionObserver so the file is only fetched once the
 *    hero is actually on screen (fold visit). If the user scrolls past
 *    fast, nothing downloads.
 *  - Leaves the poster image visible as the static fallback otherwise.
 *
 * The template renders the <video> element with no <source> child and
 * preload="none". This module attaches the source and triggers load+play
 * when conditions allow.
 */

const ATTR_SRC = 'data-hero-video-src';
const ATTR_TYPE = 'data-hero-video-type';

function isReducedMotion() {
  return window.matchMedia('(prefers-reduced-motion: reduce)').matches;
}

function isLowDataConnection() {
  const conn = navigator.connection || navigator.mozConnection || navigator.webkitConnection;
  if (!conn) return false;
  if (conn.saveData === true) return true;
  if (typeof conn.effectiveType === 'string') {
    return conn.effectiveType === '2g' || conn.effectiveType === 'slow-2g';
  }
  return false;
}

function attachAndPlay(video) {
  const src = video.getAttribute(ATTR_SRC);
  if (!src) return;
  const type = video.getAttribute(ATTR_TYPE) || 'video/mp4';

  if (video.querySelector('source')) return;
  const source = document.createElement('source');
  source.src = src;
  source.type = type;
  video.appendChild(source);
  video.load();

  const playAttempt = video.play();
  if (playAttempt && typeof playAttempt.catch === 'function') {
    playAttempt.catch(() => {
      // Autoplay rejected — poster stays, no harm.
    });
  }
}

export function initHeroVideo() {
  const videos = document.querySelectorAll(`video[${ATTR_SRC}]`);
  if (videos.length === 0) return () => {};

  if (isReducedMotion() || isLowDataConnection()) {
    // Leave posters in place. Nothing to clean up.
    return () => {};
  }

  if (typeof IntersectionObserver !== 'function') {
    // Old browser without IO — fall back to immediate attach.
    videos.forEach(attachAndPlay);
    return () => {};
  }

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (!entry.isIntersecting) return;
      attachAndPlay(entry.target);
      observer.unobserve(entry.target);
    });
  }, { rootMargin: '0px', threshold: 0.1 });

  videos.forEach((video) => observer.observe(video));

  return () => observer.disconnect();
}
