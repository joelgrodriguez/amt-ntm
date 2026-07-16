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
 *   - [data-quiz-results]                — results screen (rendered into here)
 *   - [data-quiz-lead]                   — lead-capture screen wrapping the
 *                                          HubSpot form (revealed after results)
 *   - [data-quiz-restart]                — button that resets to intro
 *
 * The questions, scoring, and recommendation tree live in this module as data
 * so the whole quiz is one file with no server round-trip. Lead capture is the
 * theme's existing HubSpot form (rendered by the template), not rebuilt here.
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
    model: 'SSQ3™ MultiPro',
    url: '/machines/roof-wall-panel-machines/ssq3-multipro/',
    article: { title: 'The True Cost of an SSQ3 MultiPro Roof & Wall Panel Machine', url: '/learning-center/cost-of-an-ssq3-multipro-roof-wall-panel-machine/' },
  },
  SSH: {
    model: 'SSH™ MultiPro',
    url: '/machines/roof-wall-panel-machines/ssh-roof-panel-machine/',
    article: { title: 'SSH MultiPro Roof Panel Machine: A Solid Portable Rollformer', url: '/learning-center/ssh-multipro-roof-panel-machine-a-solid-portable-roll-former/' },
  },
  SSR: {
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
 * Initialize the readiness quiz.
 * @returns {Function} cleanup
 */
export function initReadinessQuiz() {
  const root = document.querySelector(ROOT_SELECTOR);
  if (!root) return () => {};

  const intro = root.querySelector('[data-quiz-intro]');
  const questionsScreen = root.querySelector('[data-quiz-questions]');
  const resultsScreen = root.querySelector('[data-quiz-results]');
  const leadScreen = root.querySelector('[data-quiz-lead]');
  const progressFill = root.querySelector('[data-quiz-progress]');
  const progressLabel = root.querySelector('[data-quiz-progress-label]');

  if (!questionsScreen || !resultsScreen) return () => {};

  const controller = new AbortController();
  const { signal } = controller;

  /** @type {Record<string, {points:number, value?:string}>} */
  let answers = {};
  let index = 0;

  const show = (el) => el && el.removeAttribute('hidden');
  const hide = (el) => el && el.setAttribute('hidden', '');

  function renderQuestion() {
    const q = QUESTIONS[index];
    const total = QUESTIONS.length;

    if (progressFill) progressFill.style.width = `${Math.round((index / total) * 100)}%`;
    if (progressLabel) progressLabel.textContent = `Question ${index + 1} of ${total}`;

    const options = q.options
      .map(
        (opt, i) =>
          `<button type="button" class="quiz-option" data-quiz-option="${i}">${esc(opt.label)}</button>`
      )
      .join('');

    questionsScreen.innerHTML = `
      <p class="quiz-question__label">${esc(q.label)}</p>
      <h2 class="quiz-question__title">${esc(q.question)}</h2>
      ${q.description ? `<p class="quiz-question__desc">${esc(q.description)}</p>` : ''}
      <div class="quiz-options" role="group" aria-label="${esc(q.question)}">${options}</div>
      ${index > 0 ? '<button type="button" class="quiz-back" data-quiz-back>← Back</button>' : ''}
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

    const back = questionsScreen.querySelector('[data-quiz-back]');
    if (back) {
      back.addEventListener(
        'click',
        () => {
          index = Math.max(0, index - 1);
          renderQuestion();
        },
        { signal }
      );
    }
  }

  function finish() {
    const score = scoreOf(answers);
    const band = bandOf(score);
    const machine = recommend(score, answers);
    const display = Math.min(score, 100);

    hide(questionsScreen);
    if (progressFill) progressFill.style.width = '100%';
    if (progressLabel) progressLabel.textContent = 'Complete';

    resultsScreen.innerHTML = `
      <p class="quiz-results__label">Your readiness</p>
      <p class="quiz-results__score">${display}<span class="quiz-results__score-max">/100</span></p>
      <h2 class="quiz-results__level">${esc(band.level)}</h2>
      <p class="quiz-results__desc">${esc(band.description)}</p>
      <div class="quiz-recommendation">
        <p class="quiz-recommendation__label">Recommended machine</p>
        <h3 class="quiz-recommendation__model">${esc(machine.model)}</h3>
        <div class="quiz-recommendation__actions">
          <a class="btn btn-primary btn-sm" href="${esc(machine.url)}">View the ${esc(machine.model)}</a>
          <a class="btn btn-outline-dark btn-sm" href="${esc(machine.article.url)}">${esc(machine.article.title)}</a>
        </div>
      </div>
    `;
    show(resultsScreen);
    show(leadScreen);
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

  // Restart
  const restartBtn = root.querySelector('[data-quiz-restart]');
  if (restartBtn) {
    restartBtn.addEventListener(
      'click',
      () => {
        answers = {};
        index = 0;
        hide(resultsScreen);
        hide(leadScreen);
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
