/**
 * Readiness Quiz Module
 *
 * Panel Machine Readiness Assessment — a self-contained multi-step quiz that
 * scores a visitor's operation and recommends an NTM machine. Ported from the
 * retired Abacus.AI app; full logic is documented in
 * docs/specs/readiness-quiz-spec.md.
 *
 * Markup contract:
 *   - [data-readiness-quiz]              — root container (module no-ops if absent)
 *   - [data-quiz-intro]                  — intro screen (Start button inside)
 *   - [data-quiz-start]                  — button that begins the quiz
 *   - [data-quiz-questions]              — question screen (rendered into here)
 *   - [data-quiz-progress]               — progress bar fill element (width set)
 *   - [data-quiz-progress-label]         — "Question X of Y" text
 *   - [data-quiz-card]                   — intro/questions/results shell; hidden
 *                                          while the lead form gate is showing
 *                                          so an empty card does not flash
 *   - [data-quiz-results]                — results screen (rendered into here
 *                                          only after the lead form is submitted)
 *   - [data-quiz-lead]                   — lead-capture gate wrapping the
 *                                          HubSpot form (primary panel after
 *                                          questions; results unlock on submit)
 *   - [data-quiz-restart]                — button that resets to intro
 *
 * The questions, scoring, and recommendation tree live in this module as data
 * so the whole quiz is one file with no server round-trip. Lead capture is the
 * theme's existing HubSpot form (rendered by the template). HubspotForms.js
 * dispatches `hubspot:formSubmitted` on the form mount; this module listens
 * and reveals results only after a successful submission.
 *
 * @module ReadinessQuiz
 */

const ROOT_SELECTOR = '[data-readiness-quiz]';

/**
 * Quiz questions. Each option carries `points` (summed into the score) and,
 * where the recommendation tree needs it, a `value` (read by value, not score).
 * @see docs/specs/readiness-quiz-spec.md
 */
const QUESTIONS = [
  {
    id: 'annual_volume',
    label: 'Annual Panel Volume',
    question:
      'Based on an average roof size of ~2,000 sq ft (20 squares), approximately how many square feet of panels does your business install annually?',
    options: [
      { label: 'Under 20,000 sq ft (~10 roofs)', points: 5, value: 'under_20k' },
      { label: '20,000–60,000 sq ft (~10–30 roofs)', points: 10, value: '20k_60k' },
      { label: '60,000–100,000 sq ft (~30–50 roofs)', points: 15, value: '60k_100k' },
      { label: 'Over 100,000 sq ft (50+ roofs)', points: 20, value: 'over_100k' },
    ],
  },
  {
    id: 'job_type',
    label: 'Job Type',
    question: 'What types of projects does your business primarily handle?',
    options: [
      { label: 'Residential only', points: 5, value: 'residential' },
      { label: 'Commercial only', points: 10, value: 'commercial' },
      { label: 'Both residential and commercial', points: 15, value: 'both' },
    ],
  },
  {
    id: 'jobs_per_year',
    label: 'Number of Jobs Per Year',
    question: 'How many roofing jobs does your business complete annually?',
    options: [
      { label: 'Under 25 jobs', points: 5 },
      { label: '25–75 jobs', points: 10 },
      { label: '75–150 jobs', points: 15 },
      { label: 'Over 150 jobs', points: 20 },
    ],
  },
  {
    id: 'portability_needs',
    label: 'Machine Portability Needs',
    question: 'How important is the ability to move equipment to different job sites?',
    options: [
      { label: 'We work exclusively in our shop', points: 2 },
      { label: 'Mostly shop, occasional jobsite', points: 5 },
      { label: 'We need frequent jobsite capability', points: 10 },
    ],
  },
  {
    id: 'delivery_delays',
    label: 'Delivery Delays',
    question:
      'How often are your panel deliveries delayed, causing you to wait to complete jobs?',
    options: [
      { label: 'Rarely — deliveries are almost always on time', points: 2 },
      { label: 'Occasionally — delays happen a few times a year', points: 5 },
      { label: 'Frequently — we regularly wait on panels to finish jobs', points: 8 },
      { label: 'Constantly — delivery delays are a major bottleneck for us', points: 10 },
    ],
  },
  {
    id: 'machine_operator',
    label: 'Machine Operator',
    question: 'Do you have a crew member available to operate a rollforming machine?',
    description: 'Training is free for all new machine owners at our facility in Aurora, Colorado.',
    options: [
      { label: 'Yes, we have someone ready', points: 10 },
      { label: 'Not yet, but we could assign someone', points: 7 },
      { label: 'No, staffing an operator would be a challenge', points: 3 },
    ],
  },
  {
    id: 'panel_profiles',
    label: 'Panel Profile Offerings',
    question: 'How diverse are the panel profiles you offer to your customers?',
    options: [
      { label: 'We primarily stick to 1–2 profiles', points: 2 },
      { label: 'We offer many different profiles', points: 10 },
    ],
  },
  {
    id: 'panel_sizes',
    label: 'Panel Sizes',
    question: 'What panel rib heights do you typically work with?',
    options: [
      { label: '1” – 1.5” rib height', points: 5, value: 'small' },
      { label: 'Up to 2.5” rib height', points: 10, value: 'large' },
    ],
  },
  {
    id: 'additional_profiles',
    label: 'Additional Profiles',
    question:
      'Do you plan to include board and batten and/or soffit, flush wall, or underdeck profiles in your catalog?',
    options: [
      { label: 'No', points: 0, value: 'no' },
      { label: 'Yes', points: 5, value: 'yes' },
    ],
  },
  {
    id: 'power_source',
    label: 'Power Source',
    question: 'What power source would best fit your jobsite needs?',
    options: [
      { label: 'Gas powered', points: 5, value: 'gas' },
      { label: 'Electric powered', points: 5, value: 'electric' },
      { label: 'Both gas and electric', points: 10, value: 'both' },
    ],
  },
  {
    id: 'hydraulics',
    label: 'Hydraulic Features',
    question:
      'How important are automated features that cut panels precisely to length with a hydraulic shear vs. manually operating the shear to cut the panels?',
    options: [
      { label: 'Not important — manual operation is fine', points: 0, value: 'no' },
      { label: 'Nice to have', points: 5, value: 'nice' },
      { label: 'Very important — we need hydraulic capabilities', points: 10, value: 'yes' },
    ],
  },
  {
    id: 'notching',
    label: 'Notching Capability',
    question:
      'Notching cuts the legs of panels with fold-over hems automatically. Do you need this capability?',
    options: [
      { label: 'No', points: 0, value: 'no' },
      { label: 'Yes', points: 10, value: 'yes' },
    ],
  },
  {
    id: 'budget',
    label: 'Budget',
    question: 'What is your budget for a portable rollforming machine?',
    options: [
      { label: '$50,000 to $70,000', points: 3, value: 'low' },
      { label: '$70,000 to $100,000', points: 7, value: 'mid' },
      { label: '$100,000 to $130,000+', points: 10, value: 'high' },
    ],
  },
];

/** Readiness bands by total score. First band whose ceiling the score is under. */
const BANDS = [
  { max: 30, level: 'Not Ready', description: 'Your current volume and operations may not justify the investment in portable rollforming equipment at this time.' },
  { max: 55, level: 'Somewhat Ready', description: 'You have some operational indicators that could benefit from portable equipment, but growth may be needed first.' },
  { max: 80, level: 'Ready', description: 'Your operations show strong indicators for adopting portable rollforming equipment.' },
  { max: Infinity, level: 'Highly Ready', description: 'Your business is an excellent candidate for portable rollforming equipment. Let’s find the right machine for you.' },
];

/**
 * Machine recommendations, keyed by model. URLs are theme-relative so
 * \Standard\Url\internal() / the site's own domain resolves them.
 */
const MACHINES = {
  SSQ3: {
    key: 'SSQ3',
    model: 'SSQ3™ MultiPro',
    url: '/machines/roof-wall-panel-machines/ssq3-multipro/',
    article: { title: 'The True Cost of an SSQ3 MultiPro Roof & Wall Panel Machine', url: '/learning-center/cost-of-an-ssq3-multipro-roof-wall-panel-machine/' },
  },
  SSH: {
    key: 'SSH',
    model: 'SSH™ MultiPro',
    url: '/machines/roof-wall-panel-machines/ssh-roof-panel-machine/',
    article: { title: 'SSH MultiPro Roof Panel Machine: A Solid Portable Rollformer', url: '/learning-center/ssh-multipro-roof-panel-machine-a-solid-portable-roll-former/' },
  },
  SSR: {
    key: 'SSR',
    model: 'SSR™ MultiPro Jr.',
    url: '/machines/roof-wall-panel-machines/ssr-multipro-jr-roof-panel-machine/',
    article: { title: 'The Budget-Friendly Cost of the SSR MultiPro Jr. Roof Panel Machine', url: '/learning-center/ntm-ssr-multipro-jr-best-budget-portable-rollformer/' },
  },
};

/**
 * Sum the points of every selected answer.
 * @param {Record<string, {points:number, value?:string}>} answers
 * @returns {number}
 */
function scoreOf(answers) {
  return Object.values(answers).reduce((sum, a) => sum + (a?.points ?? 0), 0);
}

/**
 * Map a total score to its readiness band.
 * @param {number} score
 */
function bandOf(score) {
  return BANDS.find((b) => score < b.max) ?? BANDS[BANDS.length - 1];
}

/**
 * Recommend a machine from the answer values. First matching branch wins.
 * Mirrors results-display.tsx getMachineRecommendation (see spec).
 * @param {number} score
 * @param {Record<string, {points:number, value?:string}>} answers
 * @returns {{model:string, url:string, article:{title:string, url:string}}}
 */
function recommend(score, answers) {
  const val = (id) => answers[id]?.value;
  const smallPanels = val('panel_sizes') === 'small';
  const hyd = val('hydraulics');
  const wantsHydraulics = hyd === 'yes' || hyd === 'nice';
  const noHydraulics = hyd === 'no';
  const wantsNotching = val('notching') === 'yes';
  const hasAdditional = val('additional_profiles') === 'yes';
  const jobType = val('job_type');
  const isCommercial = jobType === 'commercial' || jobType === 'both';

  if (wantsNotching) return MACHINES.SSQ3;              // 1: only machine with notching
  if (smallPanels && noHydraulics) return MACHINES.SSR; // 2
  if (smallPanels && wantsHydraulics) return MACHINES.SSH; // 3
  if (hasAdditional) return MACHINES.SSQ3;              // 4: multi-profile
  if (score >= 60 && isCommercial) return MACHINES.SSQ3; // 5
  if (!smallPanels) return MACHINES.SSQ3;               // 6: large panels (up to 2.5")
  if (wantsHydraulics) return MACHINES.SSH;             // 7
  return MACHINES.SSR;                                  // 8: fallback
}

/**
 * Escape a string for safe insertion as text content in innerHTML.
 * @param {string} s
 */
function esc(s) {
  const d = document.createElement('div');
  d.textContent = s;
  return d.innerHTML;
}

/**
 * Draw the readiness gauge onto a canvas — a red→yellow→green arc with a
 * needle pointing at the score. Ported from the original app's canvas gauge.
 * The displayed needle/number is floored at 75% (a deliberate design choice
 * from the original so the dial always reads encouragingly).
 * @param {HTMLCanvasElement} canvas
 * @param {number} rawScore
 * @param {number} maxScore
 */
function drawGauge(canvas, rawScore, maxScore = 100) {
  const ctx = canvas.getContext('2d');
  if (!ctx) return;

  const score = Math.max(rawScore, maxScore * 0.75);
  const dpr = window.devicePixelRatio || 1;
  const displaySize = 300;
  canvas.width = displaySize * dpr;
  canvas.height = displaySize * dpr;
  canvas.style.width = `${displaySize}px`;
  canvas.style.height = `${displaySize}px`;
  ctx.scale(dpr, dpr);

  const size = displaySize;
  const cx = size / 2;
  const cy = size / 2;
  const radius = size / 2.5;
  const rootStyles = getComputedStyle(document.documentElement);
  const ink = rootStyles.getPropertyValue('--color-blue-900').trim() || '#0A1322';
  const muted = rootStyles.getPropertyValue('--color-blue-400').trim() || '#5A7691';

  ctx.clearRect(0, 0, size, size);

  const startAngle = Math.PI * 0.75;
  const endAngle = Math.PI * 2.25;
  const totalArc = endAngle - startAngle;

  // Gradient arc (red → yellow → green), drawn in segments.
  const segments = 120;
  for (let i = 0; i < segments; i += 1) {
    const t = i / (segments - 1);
    const segStart = startAngle + totalArc * (i / segments);
    const segEnd = startAngle + totalArc * Math.min((i + 1.5) / segments, 1);
    let r;
    let g;
    let b;
    if (t < 0.5) {
      const lt = t / 0.5;
      r = 220;
      g = Math.round(40 + lt * 160);
      b = Math.round(40 - lt * 20);
    } else {
      const lt = (t - 0.5) / 0.5;
      r = Math.round(220 - lt * 210);
      g = Math.round(200 - lt * 50);
      b = Math.round(20 - lt * 10);
    }
    ctx.beginPath();
    ctx.arc(cx, cy, radius, segStart, segEnd, false);
    ctx.strokeStyle = `rgb(${r}, ${g}, ${b})`;
    ctx.lineWidth = 30;
    ctx.lineCap = i === 0 || i === segments - 1 ? 'round' : 'butt';
    ctx.stroke();
  }

  // Dim the unfilled portion.
  const pct = Math.min(score / maxScore, 1);
  const needleAngle = startAngle + totalArc * pct;
  if (pct < 0.98) {
    ctx.beginPath();
    ctx.arc(cx, cy, radius, needleAngle + 0.01, endAngle + 0.02, false);
    ctx.strokeStyle = 'rgba(228, 236, 243, 0.85)';
    ctx.lineWidth = 32;
    ctx.lineCap = 'round';
    ctx.stroke();
  }

  // Needle.
  const needleLen = radius - 20;
  ctx.shadowColor = 'rgba(0, 0, 0, 0.2)';
  ctx.shadowBlur = 6;
  ctx.shadowOffsetX = 2;
  ctx.shadowOffsetY = 2;
  ctx.beginPath();
  ctx.moveTo(cx, cy);
  ctx.lineTo(cx + needleLen * Math.cos(needleAngle), cy + needleLen * Math.sin(needleAngle));
  ctx.strokeStyle = ink;
  ctx.lineWidth = 4;
  ctx.lineCap = 'round';
  ctx.stroke();
  ctx.shadowColor = 'transparent';

  // Score number + caption, lifted above the hub so the needle never overlaps.
  ctx.textAlign = 'center';
  ctx.font = '700 44px "Noto Sans", system-ui, sans-serif';
  ctx.fillStyle = ink;
  ctx.fillText(`${Math.round(score)}`, cx, cy - 44);
  ctx.font = '13px "Noto Sans", system-ui, sans-serif';
  ctx.fillStyle = muted;
  ctx.fillText(`out of ${maxScore}`, cx, cy - 16);

  // Hub (drawn after text so it sits cleanly at the pivot).
  ctx.beginPath();
  ctx.arc(cx, cy, 12, 0, Math.PI * 2);
  ctx.fillStyle = ink;
  ctx.fill();

  // End labels.
  ctx.font = '700 11px "Noto Sans", system-ui, sans-serif';
  const labelR = radius + 28;
  ctx.fillStyle = '#c81f2b';
  ctx.fillText('0', cx + labelR * Math.cos(startAngle), cy + labelR * Math.sin(startAngle));
  ctx.fillStyle = '#1f7a4d';
  ctx.fillText('100', cx + labelR * Math.cos(endAngle), cy + labelR * Math.sin(endAngle));
}

/**
 * Initialize the readiness quiz.
 * @returns {Function} cleanup
 */
export function initReadinessQuiz() {
  const root = document.querySelector(ROOT_SELECTOR);
  if (!root) return () => {};

  const intro = root.querySelector('[data-quiz-intro]');
  const quizCard = root.querySelector('[data-quiz-card]');
  const questionsScreen = root.querySelector('[data-quiz-questions]');
  const resultsScreen = root.querySelector('[data-quiz-results]');
  const leadScreen = root.querySelector('[data-quiz-lead]');
  const progressFill = root.querySelector('[data-quiz-progress]');
  const progressLabel = root.querySelector('[data-quiz-progress-label]');
  const progressPct = root.querySelector('[data-quiz-progress-pct]');
  const backBtn = root.querySelector('[data-quiz-back]');
  const recCards = root.querySelector('[data-quiz-rec-cards]');
  const leadEyebrow = root.querySelector('[data-quiz-lead-eyebrow]');
  const leadTitle = root.querySelector('[data-quiz-lead-title]');
  const leadDesc = root.querySelector('[data-quiz-lead-desc]');

  if (!questionsScreen || !resultsScreen) return () => {};

  const controller = new AbortController();
  const { signal } = controller;

  /** @type {Record<string, {points:number, value?:string}>} */
  let answers = {};
  let index = 0;
  /** True after a successful HubSpot submit in this page session. */
  let formSubmitted = false;
  /** @type {{score:number, band:object, machine:object}|null} */
  let pendingResult = null;

  const LEAD_COPY = {
    gated: {
      eyebrow: 'Assessment complete',
      title: 'Unlock your readiness results',
      desc: 'Your score and machine recommendation are ready. Share your details to unlock them — an NTM specialist can also follow up with pricing, availability, and next steps.',
    },
    unlocked: {
      eyebrow: 'Thanks for sharing',
      title: 'Talk to a specialist about your recommendation',
      desc: 'Your results are above — an NTM specialist can follow up with pricing, availability, and next steps.',
    },
  };

  const show = (el) => el && el.removeAttribute('hidden');
  const hide = (el) => el && el.setAttribute('hidden', '');

  function setLeadCopy(mode) {
    const copy = LEAD_COPY[mode] ?? LEAD_COPY.gated;
    if (leadEyebrow) leadEyebrow.textContent = copy.eyebrow;
    if (leadTitle) leadTitle.textContent = copy.title;
    if (leadDesc) leadDesc.textContent = copy.desc;
  }

  function markCompleteProgress() {
    if (progressFill) progressFill.style.width = '100%';
    if (progressLabel) progressLabel.textContent = 'Complete';
    if (progressPct) progressPct.textContent = '100%';
  }

  function renderQuestion() {
    const q = QUESTIONS[index];
    const total = QUESTIONS.length;

    const pct = Math.round((index / total) * 100);
    if (progressFill) progressFill.style.width = `${pct}%`;
    if (progressLabel) progressLabel.textContent = `Question ${index + 1} of ${total}`;
    if (progressPct) progressPct.textContent = `${pct}%`;

    const options = q.options
      .map(
        (opt, i) =>
          `<button type="button" class="quiz-option" data-quiz-option="${i}"><span class="quiz-option__dot" aria-hidden="true"></span><span>${esc(opt.label)}</span></button>`
      )
      .join('');

    // Persistent card-anchored back button: shown from Q2, hidden otherwise.
    if (backBtn) {
      if (index > 0) backBtn.removeAttribute('hidden');
      else backBtn.setAttribute('hidden', '');
    }

    questionsScreen.innerHTML = `
      <p class="quiz-question__label">${esc(q.label)}</p>
      <h2 class="quiz-question__title">${esc(q.question)}</h2>
      ${q.description ? `<p class="quiz-question__desc">${esc(q.description)}</p>` : ''}
      <div class="quiz-options" role="group" aria-label="${esc(q.question)}">${options}</div>
    `;

    questionsScreen.querySelectorAll('[data-quiz-option]').forEach((btn) => {
      btn.addEventListener(
        'click',
        () => {
          const opt = q.options[Number(btn.dataset.quizOption)];
          answers[q.id] = { points: opt.points, value: opt.value };
          if (index < QUESTIONS.length - 1) {
            index += 1;
            renderQuestion();
          } else {
            finish();
          }
        },
        { signal }
      );
    });
  }

  /**
   * End of questions: compute the result, then either gate on the lead form
   * or (if already submitted this session) reveal results immediately.
   */
  function finish() {
    const score = scoreOf(answers);
    pendingResult = {
      score,
      band: bandOf(score),
      machine: recommend(score, answers),
    };

    hide(questionsScreen);
    if (backBtn) backBtn.setAttribute('hidden', '');
    markCompleteProgress();

    if (formSubmitted) {
      revealResults();
      return;
    }

    // Gate: hide the empty quiz card so only the lead form is primary.
    hide(resultsScreen);
    hide(quizCard);
    setLeadCopy('gated');
    show(leadScreen);
    leadScreen?.scrollIntoView({ behavior: 'smooth', block: 'start' });
  }

  /**
   * Render the score, readiness band, and machine recommendation.
   * Called only after the lead form is submitted (or on retake after submit).
   */
  function revealResults() {
    if (!pendingResult) return;

    const { score, band, machine } = pendingResult;
    const display = Math.min(score, 100);

    markCompleteProgress();

    resultsScreen.innerHTML = `
      <div class="quiz-results__intro">
        <p class="quiz-results__eyebrow">Your readiness</p>
        <h2 class="quiz-results__headline">Here’s where your operation stands</h2>
      </div>
      <div class="quiz-gauge"><canvas data-quiz-gauge aria-hidden="true"></canvas></div>
      <p class="quiz-results__level" data-band="${esc(band.level)}">${esc(band.level)}</p>
      <p class="quiz-results__desc">${esc(band.description)}</p>
      <div class="quiz-recommendation">
        <p class="quiz-recommendation__label">Recommended machine</p>
        <h3 class="quiz-recommendation__model">${esc(machine.model)}</h3>
        <p class="quiz-recommendation__article">
          <a href="${esc(machine.article.url)}" target="_blank" rel="noopener">${esc(machine.article.title)} ↗</a>
        </p>
        <div class="quiz-recommendation__card" data-quiz-rec-slot></div>
      </div>
    `;

    // Bring the card back with results; keep lead (thank-you) below.
    show(quizCard);
    show(resultsScreen);
    setLeadCopy('unlocked');
    show(leadScreen);

    const gauge = resultsScreen.querySelector('[data-quiz-gauge]');
    if (gauge) drawGauge(gauge, display, 100);

    // Clone the matching pre-rendered product card into the results (clone, not
    // move, so the pool survives a retake), open its links in a new tab.
    const slot = resultsScreen.querySelector('[data-quiz-rec-slot]');
    if (slot && recCards) {
      const source = recCards.querySelector(`[data-quiz-rec-card="${machine.key}"]`);
      if (source) {
        const card = source.cloneNode(true);
        card.removeAttribute('hidden');
        card.removeAttribute('data-quiz-rec-card');
        card.querySelectorAll('a[href]').forEach((a) => {
          a.setAttribute('target', '_blank');
          a.setAttribute('rel', 'noopener');
        });
        slot.appendChild(card);
      }
    }

    quizCard?.scrollIntoView({ behavior: 'smooth', block: 'start' });
  }

  // Unlock results when the quiz lead form is submitted successfully.
  if (leadScreen) {
    leadScreen.addEventListener(
      'hubspot:formSubmitted',
      () => {
        formSubmitted = true;
        if (pendingResult) {
          revealResults();
        }
      },
      { signal }
    );
  }

  // Start button (intro screen is optional; if absent, start immediately)
  const startBtn = root.querySelector('[data-quiz-start]');
  if (startBtn && intro) {
    startBtn.addEventListener(
      'click',
      () => {
        hide(intro);
        show(questionsScreen);
        renderQuestion();
      },
      { signal }
    );
  } else {
    show(questionsScreen);
    renderQuestion();
  }

  // Card-anchored back button (single persistent handler).
  if (backBtn) {
    backBtn.addEventListener(
      'click',
      () => {
        if (index > 0) {
          index -= 1;
          renderQuestion();
        }
      },
      { signal }
    );
  }

  // Restart — formSubmitted stays true so retakes do not re-gate after convert.
  const restartBtn = root.querySelector('[data-quiz-restart]');
  if (restartBtn) {
    restartBtn.addEventListener(
      'click',
      () => {
        answers = {};
        index = 0;
        pendingResult = null;
        resultsScreen.innerHTML = '';
        hide(resultsScreen);
        hide(leadScreen);
        setLeadCopy('gated');
        show(quizCard);
        if (backBtn) backBtn.setAttribute('hidden', '');
        if (progressFill) progressFill.style.width = '0%';
        if (progressLabel) progressLabel.textContent = 'Question 1';
        if (progressPct) progressPct.textContent = '0%';
        if (intro) {
          show(intro);
        } else {
          show(questionsScreen);
          renderQuestion();
        }
      },
      { signal }
    );
  }

  return () => controller.abort();
}
