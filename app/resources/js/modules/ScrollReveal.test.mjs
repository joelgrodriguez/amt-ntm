import { test } from 'node:test';
import assert from 'node:assert/strict';
import { revealClassFor } from './ScrollReveal.js';

test('revealClassFor: maps data-reveal value to the reveal class', () => {
  assert.equal(revealClassFor('stagger'), 'stagger');
  assert.equal(revealClassFor('image'), 'reveal-image');
  assert.equal(revealClassFor('rule'), 'reveal-rule');
  assert.equal(revealClassFor('left'), 'reveal-left');
  assert.equal(revealClassFor('right'), 'reveal-right');
  assert.equal(revealClassFor('scale'), 'reveal-scale');
});

test('revealClassFor: empty / unknown / "fade" → base reveal class', () => {
  assert.equal(revealClassFor(''), 'reveal');
  assert.equal(revealClassFor('fade'), 'reveal');
  assert.equal(revealClassFor('bogus'), 'reveal');
  assert.equal(revealClassFor(undefined), 'reveal');
});
