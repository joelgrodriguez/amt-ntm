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

const isMacPlatform = () => {
  if (typeof navigator === 'undefined') {
    return false;
  }
  const platform = navigator.userAgentData?.platform ?? navigator.platform ?? '';
  return /Mac|iPhone|iPad|iPod/i.test(platform);
};

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

const getShortcutLabel = () => (isMacPlatform() ? '⌘ K' : 'Ctrl K');

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
  const chipsRow = modal.querySelector('[data-search-modal-chips-row]');
  const chips = chipGroup ? Array.from(chipGroup.querySelectorAll('[data-search-modal-chip]')) : [];
  const postTypeInput = modal.querySelector('[data-search-modal-post-type]');
  const shortcutHint = modal.querySelector('[data-search-modal-shortcut] kbd');
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

  // Hint glyph: Cmd K on mac, Ctrl K everywhere else.
  if (shortcutHint instanceof HTMLElement) {
    shortcutHint.textContent = getShortcutLabel();
  }

  // Surface the platform-correct keyboard shortcut on every search trigger
  // so the CSS tooltip can render it without baking a glyph into PHP.
  openButtons.forEach((button) => {
    if (button instanceof HTMLElement) {
      button.dataset.shortcut = getShortcutLabel();
    }
  });
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

  // Chips are post-typing refinement, not pre-typing scope. Reveal them
  // when the user has committed at least MIN_QUERY_LENGTH characters;
  // hide them when the query drops back below that. Pre-revealed at
  // page load if PHP saw an existing $current_query.
  const setChipsRevealed = (revealed) => {
    if (chipsRow instanceof HTMLElement) {
      chipsRow.setAttribute('data-revealed', revealed ? 'true' : 'false');
    }
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
  const popularRegion = modal.querySelector('[data-search-modal-popular]');
  const fallbackRegion = modal.querySelector('[data-search-modal-fallback]');
  const retryButton = modal.querySelector('[data-search-modal-retry]');
  const popularItems = Array.from(modal.querySelectorAll('[data-search-modal-popular-item]'));
  let lastQuery = '';

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

    // Mirror state on the combobox so AT users hear "expanded" when
    // there's a result list to step through. Loading counts as expanded
    // (the listbox is visible with skeletons) so the relationship reads
    // consistently to screen readers.
    if (input instanceof HTMLInputElement) {
      const expanded = state !== 'idle';
      input.setAttribute('aria-expanded', expanded ? 'true' : 'false');
      if (!expanded) {
        input.removeAttribute('aria-activedescendant');
      }
    }

    // Popular-searches row owns the cold open (state = idle). Hide it
    // the moment we have anything else to show; bring it back when
    // we drop back to idle (clear-x, reopen, scope flip with empty query).
    if (popularRegion instanceof HTMLElement) {
      popularRegion.hidden = state !== 'idle';
    }

    // Fallback subblock inside results: visible on empty + error only.
    if (fallbackRegion instanceof HTMLElement) {
      const showFallback = state === 'empty' || state === 'error';
      fallbackRegion.hidden = !showFallback;
      if (retryButton instanceof HTMLElement) {
        retryButton.hidden = state !== 'error';
      }
    }
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
    let activeId = '';
    items.forEach((item, i) => {
      const isActive = i === index;
      item.setAttribute('data-active', isActive ? 'true' : 'false');
      item.setAttribute('aria-selected', isActive ? 'true' : 'false');
      if (isActive && item instanceof HTMLElement && item.id !== '') {
        activeId = item.id;
      }
    });
    activeResultIndex = index;

    // aria-activedescendant points at the highlighted row's id without
    // moving DOM focus off the input — that's the combobox pattern.
    if (input instanceof HTMLInputElement) {
      if (activeId !== '') {
        input.setAttribute('aria-activedescendant', activeId);
      } else {
        input.removeAttribute('aria-activedescendant');
      }
    }
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
      // Stable per-render id so aria-activedescendant on the input has
      // something to point at. The index is enough — we wipe the list
      // every render so collisions across renders don't matter.
      link.id = `site-search-modal-result-${index}`;
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
        setChipsRevealed(false);
        return;
      }
      setChipsRevealed(true);
      const activeChip = chips.find((c) => c.getAttribute('aria-pressed') === 'true');
      const scope = activeChip?.dataset.value ?? '';
      lastQuery = query;
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
    setChipsRevealed(false);
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

  // Finalize the close after the slide transition ends. Idempotent
  // and safe to call from either transitionend or the safety timer.
  const finalizeClose = () => {
    if (closeTimer !== null) {
      window.clearTimeout(closeTimer);
      closeTimer = null;
    }
    modal.removeEventListener('transitionend', handleCloseTransitionEnd);
    if (modal.open) {
      modal.close();
    }
  };

  // Only react to the transform transition on the modal itself —
  // child element transitions (chip hovers, input focus rings) bubble
  // up through this listener and would close the dialog prematurely.
  function handleCloseTransitionEnd(event) {
    if (event.target !== modal) return;
    if (event.propertyName !== 'transform') return;
    finalizeClose();
  }

  const closeModal = () => {
    if (!modal.open) {
      return;
    }

    // Start the slide-up. We finalize on transitionend so the dialog
    // unmounts exactly when the slide visually completes, not on a
    // setTimeout that can fire a frame or two early and produce the
    // "snap at the end" look. The safety timer is a fallback for
    // browsers/edge cases where transitionend doesn't fire (e.g. the
    // user hides the tab mid-transition).
    modal.setAttribute('data-open', 'false');

    modal.removeEventListener('transitionend', handleCloseTransitionEnd);
    modal.addEventListener('transitionend', handleCloseTransitionEnd);

    if (closeTimer !== null) {
      window.clearTimeout(closeTimer);
    }
    // Buffer the safety timer past the transition duration so it only
    // fires if transitionend genuinely fails. 80ms covers a couple of
    // frames at 60Hz; longer would leave the dialog mounted unnecessarily.
    closeTimer = window.setTimeout(finalizeClose, panelCloseMs + 80);
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
    lastQuery = '';
    updateClearVisibility();
    resetResults();
    input.focus();
  };

  // Popular-search items: stuff the curated query into the input, set
  // the scope chip if the entry came with a post_type, kick off a fetch.
  // Treated as if the user typed and committed.
  const handlePopularItem = (event) => {
    const button = event.currentTarget;
    if (!(button instanceof HTMLElement) || !(input instanceof HTMLInputElement)) {
      return;
    }
    const query = button.dataset.query ?? '';
    const scope = button.dataset.postType ?? '';
    if (query === '') {
      return;
    }
    input.value = query;
    setActiveChip(scope);
    updateClearVisibility();
    setChipsRevealed(true);
    // Skip the debounce — the user already committed by tapping.
    if (debounceTimer !== null) {
      window.clearTimeout(debounceTimer);
      debounceTimer = null;
    }
    lastQuery = query;
    showLoading();
    fetchResults(query, scope);
    input.focus();
  };

  // Retry the last fetch when the user hits the error-state button.
  // Uses lastQuery so the input doesn't need to match (the user might
  // be mid-edit when retry shows up).
  const handleRetry = () => {
    if (lastQuery === '') {
      return;
    }
    const activeChip = chips.find((c) => c.getAttribute('aria-pressed') === 'true');
    const scope = activeChip?.dataset.value ?? '';
    showLoading();
    fetchResults(lastQuery, scope);
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
  popularItems.forEach((item) => item.addEventListener('click', handlePopularItem));
  if (retryButton instanceof HTMLElement) {
    retryButton.addEventListener('click', handleRetry);
  }

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
    popularItems.forEach((item) => item.removeEventListener('click', handlePopularItem));
    if (retryButton instanceof HTMLElement) {
      retryButton.removeEventListener('click', handleRetry);
    }

    if (closeTimer !== null) {
      window.clearTimeout(closeTimer);
      closeTimer = null;
    }

    modal.removeEventListener('click', handleDialogClick);
    modal.removeEventListener('close', handleClose);
    modal.removeEventListener('cancel', handleCancel);
    modal.removeEventListener('keydown', handleModalKeydown);
    modal.removeEventListener('transitionend', handleCloseTransitionEnd);

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
