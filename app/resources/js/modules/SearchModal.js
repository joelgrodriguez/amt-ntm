/**
 * Header search modal.
 *
 * Wires the native <dialog> open/close, the content-type chip group,
 * the clear-text affordance, the live quick-results pane, and the
 * global keyboard shortcuts (`/` and Cmd/Ctrl+K).
 */

const REST_URL = '/wp-json/standard/v1/search';
const RESULTS_LIMIT = 5;
const DEBOUNCE_MS = 275;
const MIN_QUERY_LENGTH = 2;
const REQUEST_MEMO_TTL_MS = 30_000;
const REQUEST_MEMO_LIMIT = 20;

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

export const normalizeSearchText = (value) => String(value ?? '')
  .normalize('NFKD')
  .replace(/[\u0300-\u036f]/g, '')
  .replace(/[\u2122\u00ae]/gi, '')
  .toLowerCase()
  .replace(/[^a-z0-9]+/g, ' ')
  .replace(/\s+/g, ' ')
  .trim();

const containsPhrase = (normalized, phrase) => {
  const normalizedPhrase = normalizeSearchText(phrase);
  if (normalizedPhrase === '') {
    return false;
  }
  return new RegExp(`(?:^|\\s)${normalizedPhrase.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')}(?:\\s|$)`).test(normalized);
};

const matchesAnyPattern = (normalized, patterns) => patterns.some((pattern) => {
  if (typeof pattern !== 'string' || pattern === '') {
    return false;
  }
  try {
    return new RegExp(pattern).test(normalized);
  } catch (_) {
    return false;
  }
});

const emptyMachineManifest = () => ({
  limit: RESULTS_LIMIT,
  machines: [],
  categories: {},
  exactGroups: [],
  categoryGroups: [],
  modifierGroups: [],
});

const readMachineManifest = (modal) => {
  const node = modal.querySelector('[data-search-modal-machine-manifest]');
  if (!(node instanceof HTMLScriptElement)) {
    return emptyMachineManifest();
  }

  try {
    const parsed = JSON.parse(node.textContent || '{}');
    return {
      limit: Number.isInteger(parsed.limit) ? parsed.limit : RESULTS_LIMIT,
      machines: Array.isArray(parsed.machines) ? parsed.machines : [],
      categories: parsed.categories && typeof parsed.categories === 'object' && !Array.isArray(parsed.categories) ? parsed.categories : {},
      exactGroups: Array.isArray(parsed.exactGroups) ? parsed.exactGroups : [],
      categoryGroups: Array.isArray(parsed.categoryGroups) ? parsed.categoryGroups : [],
      modifierGroups: Array.isArray(parsed.modifierGroups) ? parsed.modifierGroups : [],
    };
  } catch (_) {
    return emptyMachineManifest();
  }
};

const hasModifierIntent = (normalized, manifest) =>
  manifest.modifierGroups.some((group) =>
    Array.isArray(group?.phrases) && group.phrases.some((phrase) => containsPhrase(normalized, phrase)));

const uniquePush = (items, item) => {
  if (item && !items.includes(item)) {
    items.push(item);
  }
};

const machineLookup = (manifest) => {
  const map = new Map();
  manifest.machines.forEach((machine) => {
    if (machine?.key) {
      map.set(machine.key, machine);
    }
  });
  return map;
};

const groupKeysInActiveOrder = (group, manifest, lookup) => {
  const rawKeys = Array.isArray(group?.keys) ? group.keys : [];
  const rawSet = new Set(rawKeys);

  if (group?.family) {
    const activeFamilyKeys = Object.values(manifest.categories || {})
      .flatMap((keys) => (Array.isArray(keys) ? keys : []))
      .filter((key) => rawSet.has(key));
    if (activeFamilyKeys.length > 0) {
      return activeFamilyKeys.filter((key) => lookup.has(key));
    }
  }

  return rawKeys.filter((key) => lookup.has(key));
};

const getExactMachineKeys = (query, manifest, lookup) => {
  const normalized = normalizeSearchText(query);
  const keys = [];
  const add = (key) => uniquePush(keys, key);

  manifest.exactGroups.forEach((group) => {
    if (!Array.isArray(group?.patterns) || !Array.isArray(group?.keys)) {
      return;
    }
    if (matchesAnyPattern(normalized, group.patterns)) {
      groupKeysInActiveOrder(group, manifest, lookup).forEach(add);
    }
  });

  return keys;
};

const getCategoryMachineKeys = (query, manifest, lookup) => {
  const normalized = normalizeSearchText(query);
  const keys = [];

  manifest.categoryGroups.forEach((group) => {
    if (!Array.isArray(group?.phrases)) {
      return;
    }
    if (group.phrases.some((phrase) => containsPhrase(normalized, phrase))) {
      const activeCategoryKeys = typeof group.category === 'string'
        && Array.isArray(manifest.categories?.[group.category])
        ? manifest.categories[group.category]
        : (Array.isArray(group?.keys) ? group.keys : []);
      activeCategoryKeys
        .filter((key) => lookup.has(key))
        .forEach((key) => uniquePush(keys, key));
    }
  });

  return keys;
};

export const localMachineSuggestions = (query, scope, manifest) => {
  if (scope !== '' && scope !== 'product') {
    return [];
  }
  if (query.trim().length < MIN_QUERY_LENGTH || manifest.machines.length === 0) {
    return [];
  }

  const lookup = machineLookup(manifest);
  const keys = [];
  getExactMachineKeys(query, manifest, lookup).forEach((key) => uniquePush(keys, key));
  getCategoryMachineKeys(query, manifest, lookup).forEach((key) => uniquePush(keys, key));

  return keys
    .map((key) => lookup.get(key))
    .filter(Boolean)
    .slice(0, Math.min(manifest.limit, RESULTS_LIMIT))
    .map((machine) => ({
      title: machine.title,
      url: machine.url,
      subtype: machine.subtype || 'product',
      machineKey: machine.key,
      local: true,
    }));
};

const resultDedupeKeys = (item) => {
  const keys = [];
  if (item?.id) {
    keys.push(`id:${item.id}`);
  }
  if (item?.machineKey) {
    keys.push(`machine:${item.machineKey}`);
  }
  if (item?.url) {
    try {
      const origin = globalThis.window?.location?.origin
        || globalThis.location?.origin
        || 'https://example.invalid';
      const parsed = new URL(item.url, origin);
      keys.push(`url:${parsed.pathname.replace(/\/+$/, '')}`);
    } catch (_) {
      // Ignore malformed result URLs; rendering performs its own URL guard.
    }
  }
  if (keys.length === 0 && item?.title) {
    keys.push(`title:${item.subtype || ''}:${normalizeSearchText(item.title)}`);
  }
  return keys;
};

const combineUniqueResults = (primary, secondary, limit = RESULTS_LIMIT) => {
  const results = [];
  const seen = new Set();
  const push = (item) => {
    const keys = resultDedupeKeys(item);
    if (keys.some((key) => seen.has(key))) {
      return;
    }
    keys.forEach((key) => seen.add(key));
    results.push(item);
  };

  primary.forEach(push);
  secondary.forEach(push);

  return results.slice(0, limit);
};

export const shouldHydrateLocalResultClick = (event, item) => {
  if (!item?.local) {
    return false;
  }
  if (event?.metaKey || event?.ctrlKey || event?.shiftKey || event?.altKey) {
    return false;
  }
  if (typeof event?.button === 'number' && event.button !== 0) {
    return false;
  }
  return true;
};

export const reconcileResults = (localItems, remoteItems, query, manifest) => {
  if (localItems.length === 0) {
    return remoteItems.slice(0, RESULTS_LIMIT);
  }

  const remoteByKey = new Map();
  remoteItems.forEach((item) => {
    resultDedupeKeys(item).forEach((key) => remoteByKey.set(key, item));
  });

  const hydratedLocal = localItems.map((item) => {
    const remote = resultDedupeKeys(item)
      .map((key) => remoteByKey.get(key))
      .find(Boolean);
    return remote ? { ...item, ...remote, machineKey: item.machineKey, local: false } : item;
  });

  const normalized = normalizeSearchText(query);
  return hasModifierIntent(normalized, manifest)
    ? combineUniqueResults(remoteItems, hydratedLocal)
    : combineUniqueResults(hydratedLocal, remoteItems);
};

const pruneMemo = (memo) => {
  while (memo.size > REQUEST_MEMO_LIMIT) {
    const oldest = memo.keys().next().value;
    memo.delete(oldest);
  }
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
  const machineManifest = readMachineManifest(modal);
  let lastQuery = '';

  let debounceTimer = null;
  let abortController = null;
  let activeFetchKey = '';
  let latestRequestKey = '';
  let activeResultIndex = -1;
  let renderedResults = [];
  const requestMemo = new Map();

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
      let safeHref = '';
      try {
        const parsed = new URL(item.url, window.location.origin);
        if (parsed.protocol === 'http:' || parsed.protocol === 'https:') {
          safeHref = parsed.href;
        }
      } catch {
        // unparseable URL from the REST response — leave the item unlinked
      }
      if (!safeHref) {
        return; // skip rendering this result
      }
      link.href = safeHref;
      // Stable per-render id so aria-activedescendant on the input has
      // something to point at. The index is enough — we wipe the list
      // every render so collisions across renders don't matter.
      link.id = `site-search-modal-result-${index}`;
      link.setAttribute('role', 'option');
      link.setAttribute('aria-selected', 'false');
      link.tabIndex = -1;

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

    // "Top N results" when we hit the cap (more may exist — Evita: a bare
    // "5 results" reads as "only 5 exist"). Plain "N result(s)" when under the
    // cap, since those are genuinely all the matches.
    const capped = items.length >= RESULTS_LIMIT;
    setStatus(
      capped
        ? `Top ${items.length} results`
        : `${items.length} result${items.length === 1 ? '' : 's'}`
    );

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

  const getSearchState = () => {
    if (!(input instanceof HTMLInputElement)) {
      return null;
    }
    const query = input.value.trim();
    const activeChip = chips.find((c) => c.getAttribute('aria-pressed') === 'true');
    return {
      query,
      scope: activeChip?.dataset.value ?? '',
    };
  };

  const requestKeyFor = (query, scope) => `${normalizeSearchText(query)}|${scope}`;

  const fetchRemoteResults = async (query, scope, force = false) => {
    const key = requestKeyFor(query, scope);
    const now = Date.now();
    const memoized = requestMemo.get(key);
    if (!force && memoized && now - memoized.time < REQUEST_MEMO_TTL_MS) {
      return { key, items: await memoized.promise };
    }

    if (abortController !== null && activeFetchKey !== key) {
      abortController.abort();
    }

    const controller = new AbortController();
    abortController = controller;
    activeFetchKey = key;
    const params = new URLSearchParams({
      search: query,
      per_page: String(RESULTS_LIMIT),
      _fields: 'id,title,url,subtype,machineKey',
    });
    if (scope !== '') {
      params.set('subtype', scope);
    }

    const promise = fetch(`${REST_URL}?${params.toString()}`, {
      signal: controller.signal,
      credentials: 'same-origin',
      headers: { Accept: 'application/json' },
    })
      .then(async (response) => {
        if (!response.ok) {
          throw new Error(`Search returned ${response.status}`);
        }
        const items = await response.json();
        if (!Array.isArray(items)) {
          throw new Error('Unexpected payload');
        }
        return items;
      })
      .then((items) => {
        requestMemo.set(key, { promise: Promise.resolve(items), time: Date.now() });
        pruneMemo(requestMemo);
        return items;
      })
      .catch((error) => {
        requestMemo.delete(key);
        throw error;
      })
      .finally(() => {
        if (abortController === controller) {
          abortController = null;
          activeFetchKey = '';
        }
      });

    requestMemo.set(key, { promise, time: now });
    pruneMemo(requestMemo);

    return { key, items: await promise };
  };

  const renderShortQueryState = () => {
    latestRequestKey = '';
    if (abortController !== null) {
      abortController.abort();
      abortController = null;
      activeFetchKey = '';
    }
    clearResultsList();
    setStatus('');
    setResultsState('idle');
    setChipsRevealed(false);
  };

  const renderImmediateLocalResults = () => {
    const state = getSearchState();
    if (state === null) {
      return;
    }
    const { query, scope } = state;
    if (query.length < MIN_QUERY_LENGTH) {
      renderShortQueryState();
      return;
    }

    setChipsRevealed(true);
    const localItems = localMachineSuggestions(query, scope, machineManifest);
    if (localItems.length === 0) {
      latestRequestKey = requestKeyFor(query, scope);
      clearResultsList();
      setStatus('');
      setResultsState('idle');
      return;
    }

    lastQuery = query;
    latestRequestKey = requestKeyFor(query, scope);
    showResults(localItems, query);
  };

  const runSearch = async ({ force = false } = {}) => {
    const state = getSearchState();
    if (state === null) {
      return;
    }
    const { query, scope } = state;
    if (query.length < MIN_QUERY_LENGTH) {
      renderShortQueryState();
      return;
    }

    setChipsRevealed(true);
    const localItems = localMachineSuggestions(query, scope, machineManifest);
    const key = requestKeyFor(query, scope);
    latestRequestKey = key;
    lastQuery = query;

    if (localItems.length > 0) {
      showResults(localItems, query);
    } else {
      showLoading();
    }

    try {
      const { key: responseKey, items } = await fetchRemoteResults(query, scope, force);
      if (responseKey !== latestRequestKey) {
        return;
      }

      const current = getSearchState();
      if (current === null || requestKeyFor(current.query, current.scope) !== responseKey) {
        return;
      }

      const currentLocalItems = localMachineSuggestions(current.query, current.scope, machineManifest);
      const merged = reconcileResults(currentLocalItems, items, current.query, machineManifest);
      if (merged.length === 0) {
        showEmpty(current.query);
      } else {
        showResults(merged, current.query);
      }
    } catch (error) {
      if (error?.name === 'AbortError' || key !== latestRequestKey) {
        return;
      }
      if (localItems.length === 0) {
        showError();
      }
    }
  };

  const getHydratedResultForLocalItem = async (item) => {
    const state = getSearchState();
    if (state === null || state.query.length < MIN_QUERY_LENGTH) {
      return null;
    }

    const { key: responseKey, items } = await fetchRemoteResults(state.query, state.scope);
    const current = getSearchState();
    if (current === null || requestKeyFor(current.query, current.scope) !== responseKey) {
      return null;
    }

    const localItems = localMachineSuggestions(current.query, current.scope, machineManifest);
    const merged = reconcileResults(localItems, items, current.query, machineManifest);
    const keys = resultDedupeKeys(item);

    return merged.find((candidate) => {
      if (candidate.local) {
        return false;
      }
      const candidateKeys = resultDedupeKeys(candidate);
      return keys.some((key) => candidateKeys.includes(key));
    }) ?? null;
  };

  const navigateToResult = async (item) => {
    if (!item?.url) {
      return;
    }

    if (!item.local) {
      window.location.href = item.url;
      return;
    }

    setStatus('Opening result…');
    try {
      const hydrated = await getHydratedResultForLocalItem(item);
      window.location.href = hydrated?.url || item.url;
    } catch (_) {
      window.location.href = item.url;
    }
  };

  const scheduleFetch = () => {
    if (debounceTimer !== null) {
      window.clearTimeout(debounceTimer);
    }
    debounceTimer = window.setTimeout(() => {
      debounceTimer = null;
      runSearch();
    }, DEBOUNCE_MS);
  };

  const resetResults = () => {
    if (debounceTimer !== null) {
      window.clearTimeout(debounceTimer);
      debounceTimer = null;
    }
    latestRequestKey = '';
    if (abortController !== null) {
      abortController.abort();
      abortController = null;
      activeFetchKey = '';
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
      renderImmediateLocalResults();
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

  const handleResultClick = (event) => {
    const target = event.target;
    if (!(target instanceof Element)) {
      return;
    }

    const link = target.closest('[data-search-modal-result]');
    if (!(link instanceof HTMLElement)) {
      return;
    }

    const index = Number.parseInt(link.dataset.index ?? '', 10);
    const item = Number.isInteger(index) ? renderedResults[index] : null;
    if (!shouldHydrateLocalResultClick(event, item)) {
      return;
    }

    event.preventDefault();
    void navigateToResult(item);
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
    runSearch();
    input.focus();
  };

  // Retry the current field value; lastQuery only gates that an actual
  // search reached the error state before the button appeared.
  const handleRetry = () => {
    if (lastQuery === '') {
      return;
    }
    runSearch({ force: true });
  };

  const handleInput = () => {
    updateClearVisibility();
    renderImmediateLocalResults();
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
      void navigateToResult(item);
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
  if (resultsList instanceof HTMLElement) {
    resultsList.addEventListener('click', handleResultClick);
  }

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
    if (resultsList instanceof HTMLElement) {
      resultsList.removeEventListener('click', handleResultClick);
    }

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
      activeFetchKey = '';
    }
    requestMemo.clear();

    document.removeEventListener('keydown', handleGlobalKeydown);
    document.body.classList.remove('search-modal-is-open');
  };
};
