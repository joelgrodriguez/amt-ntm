import { test } from 'node:test';
import assert from 'node:assert/strict';
import { localMachineSuggestions, reconcileResults } from './SearchModal.js';

const manifest = {
  limit: 5,
  machines: [
    { key: 'ssq3-multipro', title: 'SSQ3 MultiPro', url: '/machines/roof-wall-panel-machines/ssq3-roof-panel-machine/', subtype: 'product' },
    { key: 'ssq-ii-multipro', title: 'SSQ II MultiPro', url: '/machines/roof-wall-panel-machines/ssq-roof-panel-machine/', subtype: 'product' },
    { key: 'ssh-multipro', title: 'SSH MultiPro', url: '/machines/roof-wall-panel-machines/ssh-roof-panel-machine/', subtype: 'product' },
    { key: 'ssr-multipro-jr', title: 'SSR MultiPro Jr.', url: '/machines/roof-wall-panel-machines/ssr-roof-panel-machine/', subtype: 'product' },
    { key: '5vc-5v-crimp', title: '5VC-5V Crimp', url: '/machines/roof-wall-panel-machines/5vc-5v-crimp-roof-panel-machine/', subtype: 'product' },
    { key: 'wav-wall-panel', title: 'WAV Wall Panel Machine', url: '/machines/roof-wall-panel-machines/wav-wall-panel-machine/', subtype: 'product' },
    { key: 'mach-ii-combo-gutter', title: 'MACH II 5"/6" Combo Gutter Machine', url: '/machines/gutter-machines/mach-ii-5-6-combo-gutter-machine/', subtype: 'product' },
    { key: 'mach-ii-5-gutter', title: 'MACH II 5" Gutter Machine', url: '/machines/gutter-machines/mach-ii-5-gutter-machine/', subtype: 'product' },
    { key: 'mach-ii-6-gutter', title: 'MACH II 6" Gutter Machine', url: '/machines/gutter-machines/mach-ii-6-gutter-machine/', subtype: 'product' },
    { key: 'bg7-box-gutter', title: 'BG7 Box Gutter Machine', url: '/machines/gutter-machines/bg7-box-gutter-machine/', subtype: 'product' },
  ],
  exactGroups: [
    { keys: ['mach-ii-combo-gutter'], patterns: ['\\bgm\\s*5\\s*6\\b(?!\\s*\\d)', '\\bmach\\s*(?:ii|2)\\s*5\\s*(?:/|\\s)?\\s*6\\b', '\\bmach\\s*(?:ii|2)\\s*combo\\b'] },
    { keys: ['mach-ii-5-gutter'], patterns: ['\\bgm\\s*5\\b(?!\\s*\\d)', '\\bmach\\s*(?:ii|2)\\s*5\\b(?!\\s*\\d)'] },
    { keys: ['mach-ii-6-gutter'], patterns: ['\\bgm\\s*6\\b(?!\\s*\\d)', '\\bmach\\s*(?:ii|2)\\s*6\\b(?!\\s*\\d)'] },
    { keys: ['ssq3-multipro'], patterns: ['\\bssq\\b(?!\\s*(?:ii|2|3|[0-9][a-z0-9]*))'] },
    { keys: ['ssq3-multipro'], patterns: ['\\bssq\\s*3\\b', '\\bq\\s*3\\b'] },
    { keys: ['ssq-ii-multipro'], patterns: ['\\bssq\\s*(?:ii|2)\\b'] },
    { keys: ['mach-ii-combo-gutter', 'mach-ii-5-gutter', 'mach-ii-6-gutter'], patterns: ['\\bmach\\s*(?:ii|2)\\b(?!\\s*(?:5|6|combo))'], family: true },
    { keys: ['bg7-box-gutter'], patterns: ['\\bbg\\s*7\\b'] },
  ],
  categoryGroups: [
    { phrases: ['gutter machine', 'seamless gutter', 'box gutter machine'], keys: ['mach-ii-combo-gutter', 'mach-ii-5-gutter', 'mach-ii-6-gutter', 'bg7-box-gutter'] },
    { phrases: ['roof panel machine', 'standing seam machine'], keys: ['ssq3-multipro', 'ssh-multipro', 'ssr-multipro-jr', '5vc-5v-crimp', 'wav-wall-panel'] },
  ],
  modifierGroups: [
    { phrases: ['manual', 'manuals'] },
    { phrases: ['service', 'repair'] },
    { phrases: ['cart', 'cover', 'accessory'] },
  ],
};

test('localMachineSuggestions: specific MACH aliases beat the broad family', () => {
  assert.equal(localMachineSuggestions('mach ii 5', '', manifest)[0].machineKey, 'mach-ii-5-gutter');
  assert.equal(localMachineSuggestions('mach ii 6', '', manifest)[0].machineKey, 'mach-ii-6-gutter');
  assert.equal(localMachineSuggestions('mach ii', '', manifest)[0].machineKey, 'mach-ii-combo-gutter');
});

test('localMachineSuggestions: Q3 support and scoped subtype behavior', () => {
  assert.equal(localMachineSuggestions('Q3', '', manifest)[0].machineKey, 'ssq3-multipro');
  assert.equal(localMachineSuggestions('Q3', 'product', manifest)[0].machineKey, 'ssq3-multipro');
  assert.deepEqual(localMachineSuggestions('Q3', 'manual', manifest), []);
});

test('localMachineSuggestions: category matches use active machine sets only', () => {
  const keys = localMachineSuggestions('roof panel machine', '', manifest).map((item) => item.machineKey);
  assert.deepEqual(keys, ['ssq3-multipro', 'ssh-multipro', 'ssr-multipro-jr', '5vc-5v-crimp', 'wav-wall-panel']);
});

test('reconcileResults: hydrates local machines with tracked URLs and deduplicates top five', () => {
  const local = localMachineSuggestions('SSQ3', '', manifest);
  const remote = [
    { id: 101, title: 'SSQ3 MultiPro', url: '/machines/roof-wall-panel-machines/ssq3-roof-panel-machine/?_rt=abc&_rt_nonce=nonce', subtype: 'product' },
    { id: 202, title: 'SSQ3 Manual', url: '/learning-center/manual/ssq3/?_rt=def&_rt_nonce=nonce', subtype: 'manual' },
    { id: 303, title: 'Other', url: '/other/?_rt=ghi&_rt_nonce=nonce', subtype: 'post' },
  ];

  const merged = reconcileResults(local, remote, 'SSQ3', manifest);
  assert.equal(merged.length, 3);
  assert.equal(merged[0].id, 101);
  assert.match(merged[0].url, /_rt=/);
});

test('reconcileResults: modifier queries keep REST relevance before local machines', () => {
  const local = localMachineSuggestions('SSQ3 manual', '', manifest);
  const remote = [
    { id: 202, title: 'SSQ3 Manual', url: '/learning-center/manual/ssq3/?_rt=def&_rt_nonce=nonce', subtype: 'manual' },
    { id: 101, title: 'SSQ3 MultiPro', url: '/machines/roof-wall-panel-machines/ssq3-roof-panel-machine/?_rt=abc&_rt_nonce=nonce', subtype: 'product' },
  ];

  const merged = reconcileResults(local, remote, 'SSQ3 manual', manifest);
  assert.equal(merged[0].subtype, 'manual');
});
