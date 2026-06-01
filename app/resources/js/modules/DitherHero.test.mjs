import { test } from 'node:test';
import assert from 'node:assert/strict';
import { brightnessToRadius, lerpColor, buildGrid } from './DitherHero.js';

test('brightnessToRadius: dark pixel → near max radius', () => {
  // brightness 0 (black) maps to maxR; 1 (white) maps to ~0
  assert.equal(brightnessToRadius(0, 5), 5);
  assert.equal(brightnessToRadius(1, 5), 0);
  assert.ok(Math.abs(brightnessToRadius(0.5, 5) - 2.5) < 1e-9);
});

test('brightnessToRadius: clamps out-of-range input', () => {
  assert.equal(brightnessToRadius(-1, 5), 5);
  assert.equal(brightnessToRadius(2, 5), 0);
});

test('lerpColor: endpoints and midpoint of the blue ramp', () => {
  const dark = [10, 19, 34];   // #0A1322
  const light = [155, 177, 199]; // #9BB1C7
  assert.deepEqual(lerpColor(dark, light, 0), [10, 19, 34]);
  assert.deepEqual(lerpColor(dark, light, 1), [155, 177, 199]);
  // midpoint rounds to nearest int
  assert.deepEqual(lerpColor(dark, light, 0.5), [83, 98, 117]);
});

test('buildGrid: cell count matches dimensions / spacing', () => {
  // 100x50 area, spacing 10 → 10 cols, 5 rows = 50 cells, centered
  const cells = buildGrid(100, 50, 10);
  assert.equal(cells.length, 50);
  assert.deepEqual(cells[0], { x: 5, y: 5 }); // first cell centered in its 10px cell
});
