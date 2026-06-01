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
