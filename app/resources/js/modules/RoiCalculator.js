/**
 * Portable Gutter Machine ROI Calculator.
 *
 * Ported from the standalone single-file prototype in /calculator (issue #131).
 * All rates, formulas, defaults and placeholder copy are carried over
 * unchanged — see calculator/SPEC.md. What changed is the packaging: inline
 * onclick handlers became delegated listeners, the global CSS reset and the
 * base64 logo are gone, and every result node is pre-rendered by
 * template-roi-calculator.php so this module only ever sets textContent and
 * toggles state (no innerHTML anywhere).
 *
 * Scoring is split into a pure compute() so the arithmetic can be reasoned
 * about — and tested — without a DOM.
 *
 * @file RoiCalculator.js
 */

const ROOT_SELECTOR = '[data-roi-calculator]';

/**
 * Machine starting prices. Confirmed current as of issue #131; these are the
 * only pricing figures on the page, so update them here and nowhere else.
 */
const MACHINES = {
  '5in': { label: '5" Machine', price: 9800, sizes: [5] },
  '6in': { label: '6" Machine', price: 10500, sizes: [6] },
  combo: { label: '5"/6" Combo', price: 12300, sizes: [5, 6, null] },
};

/**
 * Coil stock. `lbPerFt` derives $/ft from the buyer's own $/lb — cost per foot
 * is deliberately never hardcoded. `size` gates which machines show the option
 * (null = specialty, combo only).
 */
const MATERIALS = [
  // 5" gutter (coil: 11.75" or 11.875")
  { id: 1, label: 'Aluminum 032 — 11.75" coil (5" gutter)', size: 5, lbPerFt: 0.446 },
  { id: 2, label: 'Aluminum 032 — 11.875" coil (5" gutter)', size: 5, lbPerFt: 0.45 },
  { id: 3, label: 'Aluminum 027 — 11.75" coil (5" gutter)', size: 5, lbPerFt: 0.377 },
  { id: 4, label: 'Aluminum 027 — 11.875" coil (5" gutter)', size: 5, lbPerFt: 0.38 },
  { id: 5, label: 'Steel 26ga — 11.75" coil (5" gutter)', size: 5, lbPerFt: 0.768 },
  { id: 6, label: 'Steel 26ga — 11.875" coil (5" gutter)', size: 5, lbPerFt: 0.775 },
  { id: 7, label: 'Steel 24ga — 11.75" coil (5" gutter)', size: 5, lbPerFt: 1.0 },
  { id: 8, label: 'Steel 24ga — 11.875" coil (5" gutter)', size: 5, lbPerFt: 1.0 },
  // 6" gutter (coil: 15")
  { id: 9, label: 'Aluminum 032 — 15" coil (6" gutter)', size: 6, lbPerFt: 0.571 },
  { id: 10, label: 'Aluminum 027 — 15" coil (6" gutter)', size: 6, lbPerFt: 0.476 },
  { id: 11, label: 'Steel 26ga — 15" coil (6" gutter)', size: 6, lbPerFt: 0.969 },
  { id: 12, label: 'Steel 24ga — 15" coil (6" gutter)', size: 6, lbPerFt: 1.2 },
  // Specialty
  { id: 13, label: 'Steel 24ga — 20" coil (specialty)', size: null, lbPerFt: 1.67 },
];

/** Retail pre-made gutter, $/ft, by gutter size. Auto-fills the comparison. */
const PREMADE_REFERENCE = { 5: 1.74, 6: 3.9 };

const DEFAULT_MACHINE_ID = '5in';

/** Bar chart headroom so the longer bar never pins to 100% of the track. */
const CMP_HEADROOM = 1.15;
/** Headroom when there is only one bar to draw. */
const CMP_SOLO_SCALE = 1.5;
/** Weeks per month used for the break-even badge. */
const WEEKS_PER_MONTH = 4;

const currency = new Intl.NumberFormat('en-US', {
  style: 'currency',
  currency: 'USD',
  minimumFractionDigits: 2,
  maximumFractionDigits: 2,
});

const decimal = new Intl.NumberFormat('en-US');

/**
 * @param {number|null} n
 * @returns {string} `$1,234.56`, or an em dash when there is nothing to show.
 */
function money(n) {
  return typeof n === 'number' && Number.isFinite(n) ? currency.format(n) : '—';
}

/**
 * @param {number|null} n
 * @returns {string} `9,600`, or an em dash when there is nothing to show.
 */
function feet(n) {
  return typeof n === 'number' && Number.isFinite(n) ? decimal.format(n) : '—';
}

/**
 * Read a number input, treating blank/garbage/negative as "not answered".
 * `type=number` still accepts pasted negatives, and a negative $/lb would
 * produce a negative cost per foot and a fictional saving.
 *
 * @param {HTMLInputElement|null} input
 * @returns {number|null}
 */
function positiveNumber(input) {
  const n = Number.parseFloat(input?.value ?? '');
  return Number.isFinite(n) && n > 0 ? n : null;
}

/**
 * Every derived figure on the page, from the raw inputs. Pure.
 *
 * Each output is null when its inputs are incomplete, which is what drives the
 * placeholder copy — the calculator deliberately shows nothing rather than
 * guessing at a coil price.
 *
 * @param {{machineId: string, material: object|null, monthlyFt: number, costPerPound: number|null, preMadeCost: number|null, sellingPrice: number|null}} input
 */
export function compute({ machineId, material, monthlyFt, costPerPound, preMadeCost, sellingPrice }) {
  const machine = MACHINES[machineId] ?? MACHINES[DEFAULT_MACHINE_ID];

  const machineCpf = material && costPerPound !== null ? costPerPound * material.lbPerFt : null;
  const monthlyCost = machineCpf !== null ? monthlyFt * machineCpf : null;
  const monthlyRevenue = sellingPrice !== null ? monthlyFt * sellingPrice : null;
  const monthlySavings =
    preMadeCost !== null && machineCpf !== null ? monthlyFt * (preMadeCost - machineCpf) : null;

  // Only a positive saving can ever pay a machine off.
  const paybackMonths =
    monthlySavings !== null && monthlySavings > 0 ? machine.price / monthlySavings : null;
  const paybackWeeks =
    paybackMonths !== null ? machine.price / (monthlySavings / WEEKS_PER_MONTH) : null;

  return {
    machine,
    machineCpf,
    monthlyCost,
    monthlyRevenue,
    monthlySavings,
    savingsPerFt: preMadeCost !== null && machineCpf !== null ? preMadeCost - machineCpf : null,
    paybackMonths,
    paybackWeeks,
  };
}

export function initRoiCalculator() {
  const root = document.querySelector(ROOT_SELECTOR);
  if (!root) return () => {};

  const el = {
    machineGrid: root.querySelector('[data-roi-machines]'),
    material: root.querySelector('[data-roi-material]'),
    monthlyFt: root.querySelector('[data-roi-feet]'),
    costPerPound: root.querySelector('[data-roi-cost-lb]'),
    preMade: root.querySelector('[data-roi-premade]'),
    sellingPrice: root.querySelector('[data-roi-price]'),

    derivedValue: root.querySelector('[data-roi-derived-value]'),
    derivedNote: root.querySelector('[data-roi-derived-note]'),

    cmpPlaceholder: root.querySelector('[data-roi-cmp-placeholder]'),
    cmpRows: root.querySelector('[data-roi-cmp-rows]'),
    cmpMachineFill: root.querySelector('[data-roi-cmp-machine-fill]'),
    cmpMachineAmt: root.querySelector('[data-roi-cmp-machine-amt]'),
    cmpPremadeRow: root.querySelector('[data-roi-cmp-premade-row]'),
    cmpPremadeFill: root.querySelector('[data-roi-cmp-premade-fill]'),
    cmpPremadeAmt: root.querySelector('[data-roi-cmp-premade-amt]'),
    cmpNote: root.querySelector('[data-roi-cmp-note]'),

    outFeet: root.querySelector('[data-roi-out-feet]'),
    outMaterialCost: root.querySelector('[data-roi-out-material-cost]'),
    outRevenue: root.querySelector('[data-roi-out-revenue]'),
    outSavings: root.querySelector('[data-roi-out-savings]'),
    outSavingsSub: root.querySelector('[data-roi-out-savings-sub]'),

    paybackFigures: root.querySelector('[data-roi-payback-figures]'),
    paybackMonths: root.querySelector('[data-roi-payback-months]'),
    paybackWeeks: root.querySelector('[data-roi-payback-weeks]'),
    paybackBasis: root.querySelector('[data-roi-payback-basis]'),
    paybackMessage: root.querySelector('[data-roi-payback-message]'),
  };

  if (!el.machineGrid || !el.material) return () => {};

  const controller = new AbortController();
  const { signal } = controller;

  let selectedMachineId = DEFAULT_MACHINE_ID;
  /** True once the buyer types their own pre-made cost; suppresses auto-fill. */
  let preMadeUserEdited = false;

  function selectedMaterial() {
    const id = Number.parseInt(el.material.value, 10);
    return MATERIALS.find((m) => m.id === id) ?? null;
  }

  /** Filter the coil list to what the selected machine can actually run. */
  function buildMaterialOptions() {
    const { sizes } = MACHINES[selectedMachineId];
    const previous = el.material.value;

    el.material.replaceChildren(
      ...MATERIALS.filter((m) => sizes.includes(m.size)).map((m) => {
        const option = document.createElement('option');
        option.value = String(m.id);
        option.textContent = m.label;
        return option;
      })
    );

    // Keep the buyer's coil selected when switching to a machine that still
    // offers it, rather than silently resetting them to the top of the list.
    // Falling back explicitly (rather than leaning on the browser selecting
    // the first option for us) keeps selectedMaterial() honest.
    const options = [...el.material.options];
    const keep = options.find((o) => o.value === previous) ?? options[0];
    if (keep) keep.selected = true;
  }

  function autoFillPreMade() {
    if (preMadeUserEdited) return;
    const material = selectedMaterial();
    const reference = material ? PREMADE_REFERENCE[material.size] : undefined;
    el.preMade.value = reference === undefined ? '' : reference.toFixed(2);
  }

  /**
   * Set a result figure, shrinking it to hint size when we are showing a
   * "fill this in first" prompt instead of a number.
   *
   * @param {HTMLElement|null} node
   * @param {string} text
   * @param {boolean} isHint
   */
  function setFigure(node, text, isHint) {
    if (!node) return;
    node.textContent = text;
    node.classList.toggle('is-hint', isHint);
  }

  function renderComparison(material, result) {
    const preMadeCost = positiveNumber(el.preMade);

    if (!material || result.machineCpf === null) {
      el.cmpRows.hidden = true;
      el.cmpPlaceholder.hidden = false;
      el.cmpPlaceholder.textContent = material
        ? 'Enter your coil cost ($/lb) above to see cost comparison'
        : 'Select a material to see cost comparison';
      return;
    }

    el.cmpPlaceholder.hidden = true;
    el.cmpRows.hidden = false;

    // Scale both bars against the larger value plus headroom, so the longer
    // bar reads as long rather than maxed out.
    const ceiling = preMadeCost
      ? Math.max(result.machineCpf, preMadeCost) * CMP_HEADROOM
      : result.machineCpf * CMP_SOLO_SCALE;

    el.cmpMachineFill.style.width = `${Math.min(100, (result.machineCpf / ceiling) * 100)}%`;
    el.cmpMachineFill.textContent = `${money(result.machineCpf)}/ft`;
    el.cmpMachineAmt.textContent = money(result.machineCpf);

    if (preMadeCost === null) {
      el.cmpPremadeRow.hidden = true;
      el.cmpNote.textContent = 'Enter your pre-made cost above to see savings comparison';
      el.cmpNote.classList.remove('is-positive', 'is-negative');
      return;
    }

    el.cmpPremadeRow.hidden = false;
    el.cmpPremadeFill.style.width = `${Math.min(100, (preMadeCost / ceiling) * 100)}%`;
    el.cmpPremadeFill.textContent = `${money(preMadeCost)}/ft`;
    el.cmpPremadeAmt.textContent = money(preMadeCost);

    const perFoot = result.savingsPerFt;
    const saving = perFoot > 0;
    el.cmpNote.textContent = saving
      ? `▼ You save ${money(Math.abs(perFoot))}/ft by making your own`
      : `Machine-made costs ${money(Math.abs(perFoot))}/ft more at this coil selection`;
    el.cmpNote.classList.toggle('is-positive', saving);
    el.cmpNote.classList.toggle('is-negative', !saving);
  }

  function renderPayback(result) {
    const monthlyFt = positiveNumber(el.monthlyFt) ?? 0;

    if (result.paybackMonths !== null) {
      el.paybackFigures.hidden = false;
      el.paybackMessage.hidden = true;
      el.paybackMonths.textContent = `${result.paybackMonths.toFixed(1)} months`;
      el.paybackWeeks.textContent = `~${result.paybackWeeks.toFixed(1)} weeks to break even`;
      el.paybackBasis.textContent =
        `Based on saving ${money(result.savingsPerFt)}/ft × ${feet(monthlyFt)} ft/month ` +
        `vs. ${money(result.machine.price)} machine cost`;
      return;
    }

    el.paybackFigures.hidden = true;
    el.paybackMessage.hidden = false;

    // Distinguish "not enough information yet" from "this coil never pays off",
    // because only one of the two is the buyer's problem to fix.
    const unprofitable = result.monthlySavings !== null && result.monthlySavings <= 0;
    el.paybackMessage.textContent = unprofitable
      ? 'Payback calculation not available — machine-made costs more than pre-made at selected settings. Try a different material or enter your actual distributor cost.'
      : 'Enter your coil cost ($/lb) above to calculate payback period';
    el.paybackMessage.classList.toggle('is-negative', unprofitable);
  }

  function calculate() {
    const material = selectedMaterial();
    const monthlyFt = positiveNumber(el.monthlyFt) ?? 0;
    const costPerPound = positiveNumber(el.costPerPound);
    const preMadeCost = positiveNumber(el.preMade);
    const sellingPrice = positiveNumber(el.sellingPrice);

    const result = compute({
      machineId: selectedMachineId,
      material,
      monthlyFt,
      costPerPound,
      preMadeCost,
      sellingPrice,
    });

    // Derived $/ft, plus the arithmetic that produced it.
    if (result.machineCpf !== null) {
      el.derivedValue.textContent = `${money(result.machineCpf)}/ft`;
      el.derivedNote.textContent = `${money(costPerPound)}/lb × ${material.lbPerFt} lb/ft`;
    } else {
      el.derivedValue.textContent = '—';
      el.derivedNote.textContent = 'Enter $/lb above to calculate';
    }

    renderComparison(material, result);

    setFigure(el.outFeet, monthlyFt > 0 ? feet(monthlyFt) : '—', false);
    setFigure(el.outMaterialCost, money(result.monthlyCost), false);
    setFigure(
      el.outRevenue,
      result.monthlyRevenue !== null ? money(result.monthlyRevenue) : 'Enter selling price above',
      result.monthlyRevenue === null
    );

    if (result.monthlySavings === null) {
      setFigure(el.outSavings, 'Enter pre-made cost above', true);
      el.outSavingsSub.textContent = '';
    } else if (result.monthlySavings >= 0) {
      setFigure(el.outSavings, money(result.monthlySavings), false);
      el.outSavingsSub.textContent = `vs. buying pre-made at ${money(preMadeCost)}/ft`;
    } else {
      setFigure(el.outSavings, `${money(Math.abs(result.monthlySavings))} more`, true);
      el.outSavingsSub.textContent =
        'machine-made costs more with this material (try a different coil)';
    }

    renderPayback(result);
  }

  // Machine picker — delegated, so the buttons stay plain markup.
  el.machineGrid.addEventListener(
    'click',
    (event) => {
      const button = event.target.closest('[data-roi-machine]');
      if (!button || !el.machineGrid.contains(button)) return;

      selectedMachineId = button.dataset.roiMachine;
      el.machineGrid.querySelectorAll('[data-roi-machine]').forEach((b) => {
        b.setAttribute('aria-pressed', String(b === button));
      });

      buildMaterialOptions();
      autoFillPreMade();
      calculate();
    },
    { signal }
  );

  // Changing coil resets the pre-made reference price, matching the prototype:
  // the Home Depot figure is size-specific, so a 5"→6" switch should re-quote.
  el.material.addEventListener(
    'change',
    () => {
      preMadeUserEdited = false;
      autoFillPreMade();
      calculate();
    },
    { signal }
  );

  el.preMade.addEventListener(
    'input',
    () => {
      preMadeUserEdited = true;
      calculate();
    },
    { signal }
  );

  [el.monthlyFt, el.costPerPound, el.sellingPrice].forEach((input) => {
    input?.addEventListener('input', calculate, { signal });
  });

  buildMaterialOptions();
  autoFillPreMade();
  calculate();

  return () => controller.abort();
}
