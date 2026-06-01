/**
 * DitherHero Module
 *
 * Renders an SSQ3 photo as an animated halftone dot field on <canvas>.
 * Pure math helpers are exported separately so they can be unit-tested
 * without a DOM. The init function (added later) wires them to a canvas.
 *
 * @module DitherHero
 */

/**
 * Map pixel brightness (0 = black, 1 = white) to dot radius.
 * Dark pixels render as the largest dots. Input is clamped to [0,1].
 * @param {number} brightness
 * @param {number} maxRadius
 * @returns {number}
 */
export function brightnessToRadius(brightness, maxRadius) {
  const b = Math.min(1, Math.max(0, brightness));
  return (1 - b) * maxRadius;
}

/**
 * Linear-interpolate between two RGB triples. t is clamped to [0,1].
 * @param {[number,number,number]} from
 * @param {[number,number,number]} to
 * @param {number} t
 * @returns {[number,number,number]}
 */
export function lerpColor(from, to, t) {
  const k = Math.min(1, Math.max(0, t));
  return [
    Math.round(from[0] + (to[0] - from[0]) * k),
    Math.round(from[1] + (to[1] - from[1]) * k),
    Math.round(from[2] + (to[2] - from[2]) * k),
  ];
}

/**
 * Build a centered grid of dot anchor points for a given area + spacing.
 * Dots are not re-centered within the area: the first dot sits at (spacing/2, spacing/2)
 * and any remainder at the right/bottom edge is left empty.
 * @param {number} width
 * @param {number} height
 * @param {number} spacing
 * @returns {Array<{x:number,y:number}>}
 */
export function buildGrid(width, height, spacing) {
  const cells = [];
  const cols = Math.floor(width / spacing);
  const rows = Math.floor(height / spacing);
  for (let row = 0; row < rows; row++) {
    for (let col = 0; col < cols; col++) {
      cells.push({ x: col * spacing + spacing / 2, y: row * spacing + spacing / 2 });
    }
  }
  return cells;
}

const DARK = [10, 19, 34];     // --color-blue-900 #0A1322
const LIGHT = [155, 177, 199]; // --color-blue-300 #9BB1C7
const RED = [205, 16, 24];     // --color-red #CD1018

const DESKTOP_SPACING = 7;     // px between dots (CSS px)
const MOBILE_SPACING = 11;     // sparser on small screens
const MAX_RADIUS_FACTOR = 0.62; // dot radius cap relative to spacing
const ASSEMBLE_MS = 1100;      // scatter → resolved duration
const RIPPLE_AMP = 0.6;        // idle ripple amplitude (px)
const CURSOR_RADIUS = 90;      // px influence radius of the pointer
const CURSOR_PUSH = 6;         // px max displacement toward/away cursor

/**
 * Initialize the dithered canvas hero.
 * No-ops (returns a noop cleanup) when the root element is absent so it
 * is safe to call on every page.
 * @returns {() => void} cleanup
 */
export function initDitherHero() {
  const root = document.querySelector('[data-dither-hero]');
  if (!root) {
    return () => {};
  }

  const canvas = root.querySelector('[data-dither-canvas]');
  const img = root.querySelector('[data-dither-img]');
  if (!(canvas instanceof HTMLCanvasElement) || !(img instanceof HTMLImageElement)) {
    return () => {};
  }

  const ctx = canvas.getContext('2d');
  if (!ctx) {
    return () => {};
  }

  const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const finePointer = window.matchMedia('(pointer: fine)').matches;

  let cells = [];          // [{x,y,r,color}] in CSS px
  let raf = 0;
  let startTs = 0;
  let running = false;
  let assembled = false;
  let cachedRect = null;
  let booted = false;
  let visible = true;
  const pointer = { x: -9999, y: -9999, active: false };

  function getRect() {
    if (!cachedRect) cachedRect = canvas.getBoundingClientRect();
    return cachedRect;
  }

  function dpr() {
    return Math.min(2, window.devicePixelRatio || 1);
  }

  // Sample the image once into an offscreen buffer sized to the canvas grid.
  function sample() {
    assembled = false;
    const rect = getRect();
    if (rect.width === 0 || rect.height === 0) {
      return;
    }
    const ratio = dpr();
    canvas.width = Math.round(rect.width * ratio);
    canvas.height = Math.round(rect.height * ratio);
    ctx.setTransform(ratio, 0, 0, ratio, 0, 0);

    const spacing = rect.width < 640 ? MOBILE_SPACING : DESKTOP_SPACING;
    const grid = buildGrid(rect.width, rect.height, spacing);

    // Draw the image (object-fit: cover) into a scratch canvas to read pixels.
    const scratch = document.createElement('canvas');
    scratch.width = Math.max(1, Math.floor(rect.width));
    scratch.height = Math.max(1, Math.floor(rect.height));
    const sctx = scratch.getContext('2d', { willReadFrequently: true });
    if (!sctx) {
      return;
    }
    const scale = Math.max(scratch.width / img.naturalWidth, scratch.height / img.naturalHeight);
    const dw = img.naturalWidth * scale;
    const dh = img.naturalHeight * scale;
    sctx.drawImage(img, (scratch.width - dw) / 2, (scratch.height - dh) / 2, dw, dh);

    let data;
    try {
      data = sctx.getImageData(0, 0, scratch.width, scratch.height).data;
    } catch (e) {
      return; // tainted canvas (cross-origin) — fall back to plain photo
    }

    const maxR = spacing * MAX_RADIUS_FACTOR;
    cells = grid.map((c) => {
      const px = Math.min(scratch.width - 1, Math.max(0, Math.floor(c.x)));
      const py = Math.min(scratch.height - 1, Math.max(0, Math.floor(c.y)));
      const i = (py * scratch.width + px) * 4;
      const lum = (0.299 * data[i] + 0.587 * data[i + 1] + 0.114 * data[i + 2]) / 255;
      return {
        x: c.x,
        y: c.y,
        ox: (Math.sin(px * 12.9898 + py * 78.233) * 43758.5453 % 1) * rect.width, // scatter origin
        oy: (Math.sin(px * 39.346 + py * 11.135) * 24634.6345 % 1) * rect.height,
        r: brightnessToRadius(lum, maxR),
        color: lerpColor(DARK, LIGHT, lum),
      };
    });
    canvas.classList.add('is-ready'); // CSS fades canvas in over the base img
  }

  function draw(ts) {
    if (!startTs) startTs = ts;
    const elapsed = ts - startTs;
    const assemble = reduceMotion ? 1 : Math.min(1, elapsed / ASSEMBLE_MS);
    if (assemble >= 1) { assembled = true; }
    const ease = assemble * (2 - assemble); // easeOutQuad
    const rect = getRect();

    ctx.clearRect(0, 0, rect.width, rect.height);

    for (let k = 0; k < cells.length; k++) {
      const c = cells[k];
      let x = c.ox + (c.x - c.ox) * ease;
      let y = c.oy + (c.y - c.oy) * ease;

      if (!reduceMotion && assemble >= 1) {
        // idle ripple
        const wob = Math.sin((elapsed / 900) + (c.x + c.y) * 0.02) * RIPPLE_AMP;
        y += wob;
        // cursor displacement
        if (finePointer && pointer.active) {
          const dx = x - pointer.x;
          const dy = y - pointer.y;
          const dist = Math.hypot(dx, dy);
          if (dist < CURSOR_RADIUS && dist > 0.001) {
            const f = (1 - dist / CURSOR_RADIUS) * CURSOR_PUSH;
            x += (dx / dist) * f;
            y += (dy / dist) * f;
          }
        }
      }

      const [r, g, b] = c.color;
      ctx.fillStyle = `rgb(${r},${g},${b})`;
      ctx.beginPath();
      ctx.arc(x, y, c.r * (0.4 + 0.6 * ease), 0, Math.PI * 2);
      ctx.fill();
    }

    if (reduceMotion && assemble >= 1) {
      running = false; // single static render, stop the loop
      return;
    }
    if (running && visible) {
      raf = requestAnimationFrame(draw);
    }
  }

  function start() {
    if (running) return;
    running = true;
    if (!assembled) startTs = 0;
    raf = requestAnimationFrame(draw);
  }
  function stop() {
    running = false;
    if (raf) cancelAnimationFrame(raf);
    raf = 0;
  }

  const onMove = (e) => {
    const rect = getRect();
    pointer.x = e.clientX - rect.left;
    pointer.y = e.clientY - rect.top;
    pointer.active = true;
  };
  const onLeave = () => { pointer.active = false; };
  const onScroll = () => { cachedRect = null; };
  const onResize = () => {
    cachedRect = null;
    sample();
    // sample() resizes (and clears) the canvas. Under reduced motion the loop
    // has already stopped, so re-kick a single static render or the field
    // goes blank until some other event happens to restart it.
    if (reduceMotion && visible) {
      start();
    }
  };
  const onVisibility = () => { visible = !document.hidden; if (visible) start(); else stop(); };

  const io = new IntersectionObserver((entries) => {
    visible = entries[0].isIntersecting;
    if (visible) start(); else stop();
  }, { threshold: 0.01 });

  function boot() {
    sample();
    io.observe(root);
    if (finePointer) {
      canvas.addEventListener('pointermove', onMove);
      canvas.addEventListener('pointerleave', onLeave);
    }
    window.addEventListener('resize', onResize);
    window.addEventListener('scroll', onScroll, { passive: true });
    document.addEventListener('visibilitychange', onVisibility);
    start();
  }

  function bootOnce() {
    if (booted) return;
    booted = true;
    boot();
  }

  if (img.complete && img.naturalWidth > 0) {
    bootOnce();
  } else {
    img.addEventListener('load', bootOnce, { once: true });
    img.addEventListener('error', () => {}, { once: true });
  }

  return () => {
    booted = true; // prevent a late load fire from re-booting
    stop();
    io.disconnect();
    canvas.removeEventListener('pointermove', onMove);
    canvas.removeEventListener('pointerleave', onLeave);
    window.removeEventListener('resize', onResize);
    window.removeEventListener('scroll', onScroll);
    document.removeEventListener('visibilitychange', onVisibility);
  };
}
