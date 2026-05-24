/**
 * Header search modal.
 *
 * Wires the native <dialog> open/close, the content-type chip group,
 * the clear-text affordance, the live quick-results pane, and the
 * global keyboard shortcuts (`/` and Cmd/Ctrl+K).
 */

const REST_URL = '/wp-json/wp/v2/search';
const RESULTS_LIMIT = 5;
const DEBOUNCE_MS = 180;
const MIN_QUERY_LENGTH = 2;

const POST_TYPE_LABELS = {
  product:    'Machine',
  profile:    'Profile',
  manual:     'Manual',
  post:       'Article',
  video:      'Video',
  resource:   'Resource',
  download:   'Download',
  literature: 'Literature',
  page:       'Page',
  footprint:  'Footprint',
};

const labelForPostType = (subtype) => POST_TYPE_LABELS[subtype] || subtype || 'Result';

const isFormControl = (element) =>
  element instanceof HTMLInputElement ||
  element instanceof HTMLTextAreaElement ||
  element instanceof HTMLSelectElement ||
  (element instanceof HTMLElement && element.isContentEditable);

// Decode the named/numeric HTML entities that the WP REST API hands
// back in titles (e.g. &#8211; → "–", &amp; → "&"). Hand-rolled rather
// than via innerHTML so the title text never re-enters the HTML parser.
const NAMED_ENTITIES = {
  amp: '&', lt: '<', gt: '>', quot: '"', apos: "'", nbsp: ' ',
};
const decodeEntities = (str) => {
  if (typeof str !== 'string') {
    return '';
  }
  return str.replace(/&(#x?[0-9a-f]+|[a-z]+);/gi, (match, body) => {
    if (body[0] === '#') {
      const code = body[1] === 'x' || body[1] === 'X'
        ? parseInt(body.slice(2), 16)
        : parseInt(body.slice(1), 10);
      if (Number.isFinite(code)) {
        try {
          return String.fromCodePoint(code);
        } catch (_) {
          return match;
        }
      }
      return match;
    }
    const lower = body.toLowerCase();
    return Object.prototype.hasOwnProperty.call(NAMED_ENTITIES, lower)
      ? NAMED_ENTITIES[lower]
      : match;
  });
};

export const initSearchModal = () => {
  const modal = document.querySelector('#site-search-modal');
  const openButtons = document.querySelectorAll('[data-search-modal-open]');

  if (!(modal instanceof HTMLDialogElement) || openButtons.length === 0) {
    return null;
  }

  const closeButtons = modal.querySelectorAll('[data-search-modal-close]');
  const input = modal.querySelector('[data-search-modal-input]');
  const clearButton = modal.querySelector('[data-search-modal-clear]');
  const chipGroup = modal.querySelector('[data-search-modal-chips]');
  const chips = chipGroup ? Array.from(chipGroup.querySelectorAll('[data-search-modal-chip]')) : [];
  const postTypeInput = modal.querySelector('[data-search-modal-post-type]');
  let activeTrigger = null;
  let closeTimer = null;

  // Read the panel close duration from CSS so JS stays in sync with the
  // transitions.dev token. Defaults match _root.css.
  const readDurationMs = (varName, fallback) => {
    const raw = getComputedStyle(document.documentElement).getPropertyValue(varName).trim();
    if (raw === '') {
      return fallback;
    }
    const parsed = parseFloat(raw);
    if (Number.isNaN(parsed)) {
      return fallback;
    }
    return raw.endsWith('ms') ? parsed : parsed * 1000;
  };
  const panelCloseMs = readDurationMs('--panel-close-dur', 350);

  const syncPostTypeInput = (value) => {
    if (!(postTypeInput instanceof HTMLInputElement)) {
      return;
    }
    postTypeInput.value = value;
    // Disabled when "All" is chosen so the query string stays clean.
    postTypeInput.disabled = value === '';
  };

  const setActiveChip = (value) => {
    chips.forEach((chip) => {
      const isActive = chip.dataset.value === value;
      chip.setAttribute('aria-pressed', isActive ? 'true' : 'false');
    });
    syncPostTypeInput(value);
  };

  const updateClearVisibility = () => {
    if (!(input instanceof HTMLInputElement) || !(clearButton instanceof HTMLElement)) {
      return;
    }
    clearButton.hidden = input.value.trim() === '';
  };

  /* ──────────────────────────────────────────────────────────────────
     Quick results
     ──────────────────────────────────────────────────────────────── */

  const resultsRegion = modal.querySelector('[data-search-modal-results]');
  const resultsStatus = modal.querySelector('[data-search-modal-results-status]');
  const resultsList = modal.querySelector('[data-search-modal-results-list]');
  const resultsAll = modal.querySelector('[data-search-modal-results-all]');
  const resultsAllLabel = modal.querySelector('[data-search-modal-results-all-label]');
  const form = modal.querySelector('form');

  let debounceTimer = null;
  let abortController = null;
  let activeResultIndex = -1;
  let renderedResults = [];

  const setResultsState = (state) => {
    if (!(resultsRegion instanceof HTMLElement)) {
      return;
    }
    resultsRegion.dataset.state = state;
    resultsRegion.hidden = state === 'idle';
  };

  const setStatus = (text) => {
    if (resultsStatus instanceof HTMLElement) {
      resultsStatus.textContent = text;
    }
  };

  const clearResultsList = () => {
    if (resultsList instanceof HTMLElement) {
      resultsList.innerHTML = '';
    }
    renderedResults = [];
    activeResultIndex = -1;
  };

  const setActiveResult = (index) => {
    if (!(resultsList instanceof HTMLElement)) {
      return;
    }
    const items = resultsList.querySelectorAll('[data-search-modal-result]');
    items.forEach((item, i) => {
      item.setAttribute('data-active', i === index ? 'true' : 'false');
    });
    activeResultIndex = index;
  };

  const showLoading = () => {
    if (!(resultsList instanceof HTMLElement)) {
      return;
    }
    setStatus('Searching…');
    clearResultsList();
    for (let i = 0; i < 3; i += 1) {
      const li = document.createElement('li');
      li.className = 'search-modal__results-skeleton';
      const type = document.createElement('span');
      type.className = 'search-modal__results-skeleton-type';
      const title = document.createElement('span');
      title.className = 'search-modal__results-skeleton-title';
      li.append(type, title);
      resultsList.append(li);
    }
    if (resultsAll instanceof HTMLElement) {
      resultsAll.hidden = true;
    }
    setResultsState('loading');
  };

  const showEmpty = (query) => {
    clearResultsList();
    setStatus(`No matches for "${query}".`);
    if (resultsAll instanceof HTMLElement) {
      resultsAll.hidden = true;
    }
    setResultsState('empty');
  };

  const showError = () => {
    clearResultsList();
    setStatus('Search is unavailable. Press Enter to load results.');
    if (resultsAll instanceof HTMLElement) {
      resultsAll.hidden = true;
    }
    setResultsState('error');
  };

  const showResults = (items, query) => {
    if (!(resultsList instanceof HTMLElement)) {
      return;
    }
    clearResultsList();
    renderedResults = items;

    items.forEach((item, index) => {
      const li = document.createElement('li');

      const link = document.createElement('a');
      link.className = 'search-modal__results-item';
      link.dataset.searchModalResult = '';
      link.dataset.index = String(index);
      link.href = item.url;
      link.setAttribute('role', 'option');
      link.setAttribute('aria-selected', 'false');

      const type = document.createElement('span');
      type.className = 'search-modal__results-type';
      type.textContent = labelForPostType(item.subtype);

      const title = document.createElement('span');
      title.className = 'search-modal__results-title';
      title.textContent = decodeEntities(item.title || '');

      link.append(type, title);
      li.append(link);
      resultsList.append(li);
    });

    setStatus(`${items.length} result${items.length === 1 ? '' : 's'}`);

    // Always offer a "see all" link, even when we capped at RESULTS_LIMIT.
    // We don't know the true total without an extra round-trip; the link
    // posts the same form so the results page does the real counting.
    if (resultsAll instanceof HTMLElement && resultsAllLabel instanceof HTMLElement && form instanceof HTMLFormElement) {
      const url = new URL(form.action, window.location.href);
      url.searchParams.set('s', query);
      const activeChip = chips.find((c) => c.getAttribute('aria-pressed') === 'true');
      const scope = activeChip?.dataset.value ?? '';
      if (scope !== '') {
        url.searchParams.set('post_type', scope);
      }
      resultsAll.href = url.toString();
      resultsAllLabel.textContent = `See all results for "${query}"`;
      resultsAll.hidden = false;
    }

    setResultsState('ready');
  };

  const fetchResults = async (query, scope) => {
    if (abortController !== null) {
      abortController.abort();
    }
    abortController = new AbortController();

    const params = new URLSearchParams({
      search: query,
      per_page: String(RESULTS_LIMIT),
      _fields: 'id,title,url,subtype',
    });
    if (scope !== '') {
      params.set('subtype', scope);
    }

    try {
      const response = await fetch(`${REST_URL}?${params.toString()}`, {
        signal: abortController.signal,
        credentials: 'same-origin',
        headers: { Accept: 'application/json' },
      });
      if (!response.ok) {
        throw new Error(`Search returned ${response.status}`);
      }
      const items = await response.json();
      if (!Array.isArray(items)) {
        throw new Error('Unexpected payload');
      }
      if (items.length === 0) {
        showEmpty(query);
      } else {
        showResults(items, query);
      }
    } catch (error) {
      if (error?.name === 'AbortError') {
        return;
      }
      showError();
    } finally {
      abortController = null;
    }
  };

  const scheduleFetch = () => {
    if (debounceTimer !== null) {
      window.clearTimeout(debounceTimer);
    }
    debounceTimer = window.setTimeout(() => {
      debounceTimer = null;
      if (!(input instanceof HTMLInputElement)) {
        return;
      }
      const query = input.value.trim();
      if (query.length < MIN_QUERY_LENGTH) {
        if (abortController !== null) {
          abortController.abort();
          abortController = null;
        }
        clearResultsList();
        setStatus('');
        setResultsState('idle');
        return;
      }
      const activeChip = chips.find((c) => c.getAttribute('aria-pressed') === 'true');
      const scope = activeChip?.dataset.value ?? '';
      showLoading();
      fetchResults(query, scope);
    }, DEBOUNCE_MS);
  };

  const resetResults = () => {
    if (debounceTimer !== null) {
      window.clearTimeout(debounceTimer);
      debounceTimer = null;
    }
    if (abortController !== null) {
      abortController.abort();
      abortController = null;
    }
    clearResultsList();
    setStatus('');
    setResultsState('idle');
  };

  const openModal = (event) => {
    if (event) {
      event.preventDefault();
      activeTrigger = event.currentTarget instanceof HTMLElement ? event.currentTarget : null;
    }

    if (closeTimer !== null) {
      window.clearTimeout(closeTimer);
      closeTimer = null;
    }

    if (!modal.open) {
      modal.showModal();
    }

    document.body.classList.add('search-modal-is-open');
    updateClearVisibility();

    // dialog opens already at .t-panel-slide's resting state (data-open
    // = "false"). Flip data-open in the next frame so the browser has
    // committed the closed style first; that's what gives us the slide
    // instead of an instant snap.
    window.requestAnimationFrame(() => {
      modal.setAttribute('data-open', 'true');
    });

    window.setTimeout(() => {
      if (input instanceof HTMLInputElement) {
        input.focus();
        input.select();
      }
    }, 0);
  };

  const closeModal = () => {
    if (!modal.open) {
      return;
    }

    // Start the slide-up + fade first, then call .close() once the
    // transition has finished. Closing the dialog immediately would
    // unmount the element before the user saw any motion.
    modal.setAttribute('data-open', 'false');

    if (closeTimer !== null) {
      window.clearTimeout(closeTimer);
    }
    closeTimer = window.setTimeout(() => {
      closeTimer = null;
      if (modal.open) {
        modal.close();
      }
    }, panelCloseMs);
  };

  const handleClose = () => {
    document.body.classList.remove('search-modal-is-open');
    // Belt-and-suspenders: if the dialog closes via Esc / form submit
    // before our timer fires, make sure data-open is reset for next time.
    modal.setAttribute('data-open', 'false');
    resetResults();

    if (activeTrigger instanceof HTMLElement) {
      activeTrigger.focus();
      activeTrigger = null;
    }
  };

  // Backdrop click. The dialog itself receives the click when the user
  // hits the backdrop; the form is the inner stop-zone.
  const handleDialogClick = (event) => {
    if (event.target === modal) {
      closeModal();
    }
  };

  const handleChipClick = (event) => {
    const chip = event.currentTarget;
    if (!(chip instanceof HTMLElement)) {
      return;
    }
    setActiveChip(chip.dataset.value ?? '');
    // Re-fetch with the new scope if there's already a query in play.
    if (input instanceof HTMLInputElement && input.value.trim().length >= MIN_QUERY_LENGTH) {
      scheduleFetch();
    }
    if (input instanceof HTMLInputElement) {
      input.focus();
    }
  };

  const handleClear = () => {
    if (!(input instanceof HTMLInputElement)) {
      return;
    }
    input.value = '';
    updateClearVisibility();
    resetResults();
    input.focus();
  };

  const handleInput = () => {
    updateClearVisibility();
    scheduleFetch();
  };

  // Keyboard nav inside the modal. Down/Up moves through results,
  // Enter on a highlighted result navigates to its URL, Enter with no
  // highlight submits the form (default behavior).
  const handleModalKeydown = (event) => {
    if (!['ArrowDown', 'ArrowUp', 'Enter'].includes(event.key)) {
      return;
    }
    if (renderedResults.length === 0) {
      return;
    }

    if (event.key === 'ArrowDown') {
      event.preventDefault();
      const next = activeResultIndex + 1 >= renderedResults.length ? 0 : activeResultIndex + 1;
      setActiveResult(next);
      return;
    }

    if (event.key === 'ArrowUp') {
      event.preventDefault();
      const next = activeResultIndex <= 0 ? renderedResults.length - 1 : activeResultIndex - 1;
      setActiveResult(next);
      return;
    }

    if (event.key === 'Enter' && activeResultIndex >= 0) {
      event.preventDefault();
      const item = renderedResults[activeResultIndex];
      if (item?.url) {
        window.location.href = item.url;
      }
    }
  };

  // Global shortcuts: `/` to focus the modal, Cmd/Ctrl+K to toggle.
  const handleGlobalKeydown = (event) => {
    const target = event.target;

    if ((event.metaKey || event.ctrlKey) && event.key.toLowerCase() === 'k') {
      event.preventDefault();
      if (modal.open) {
        closeModal();
      } else {
        openModal();
      }
      return;
    }

    if (event.key === '/' && !modal.open && !isFormControl(target)) {
      event.preventDefault();
      openModal();
    }
  };

  openButtons.forEach((button) => button.addEventListener('click', openModal));
  closeButtons.forEach((button) => button.addEventListener('click', closeModal));
  chips.forEach((chip) => chip.addEventListener('click', handleChipClick));

  // Intercept Esc so we get the slide-up close transition instead of
  // dialog.close() snapping the element away.
  const handleCancel = (event) => {
    event.preventDefault();
    closeModal();
  };

  modal.addEventListener('click', handleDialogClick);
  modal.addEventListener('close', handleClose);
  modal.addEventListener('cancel', handleCancel);
  modal.addEventListener('keydown', handleModalKeydown);

  if (input instanceof HTMLInputElement) {
    input.addEventListener('input', handleInput);
  }

  if (clearButton instanceof HTMLElement) {
    clearButton.addEventListener('click', handleClear);
  }

  document.addEventListener('keydown', handleGlobalKeydown);

  // Initial state: sync the hidden post_type input to the preselected chip.
  const preselected = chips.find((chip) => chip.getAttribute('aria-pressed') === 'true');
  syncPostTypeInput(preselected?.dataset.value ?? '');
  updateClearVisibility();

  return () => {
    openButtons.forEach((button) => button.removeEventListener('click', openModal));
    closeButtons.forEach((button) => button.removeEventListener('click', closeModal));
    chips.forEach((chip) => chip.removeEventListener('click', handleChipClick));

    if (closeTimer !== null) {
      window.clearTimeout(closeTimer);
      closeTimer = null;
    }

    modal.removeEventListener('click', handleDialogClick);
    modal.removeEventListener('close', handleClose);
    modal.removeEventListener('cancel', handleCancel);
    modal.removeEventListener('keydown', handleModalKeydown);

    if (input instanceof HTMLInputElement) {
      input.removeEventListener('input', handleInput);
    }
    if (clearButton instanceof HTMLElement) {
      clearButton.removeEventListener('click', handleClear);
    }

    if (debounceTimer !== null) {
      window.clearTimeout(debounceTimer);
    }
    if (abortController !== null) {
      abortController.abort();
    }

    document.removeEventListener('keydown', handleGlobalKeydown);
    document.body.classList.remove('search-modal-is-open');
  };
};
