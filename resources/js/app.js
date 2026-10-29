import './bootstrap';
import 'bootstrap';
import { Offcanvas } from 'bootstrap';

const root = document.documentElement;
const themeButton = document.querySelector('[data-theme-toggle]');
const themeIcon = document.querySelector('[data-theme-icon]');
const searchInputs = [...document.querySelectorAll('[data-site-search]')];
const searchForms = [...document.querySelectorAll('[data-search-form]')];
const searchClearButtons = [...document.querySelectorAll('[data-search-clear]')];
const searchResultPanels = [...document.querySelectorAll('[data-search-results]')];
const filterButtons = [...document.querySelectorAll('[data-content-filter]')];
const watchlistFilter = document.querySelector('[data-watchlist-filter]');
const videoModal = document.getElementById('videoModal');
const videoFrame = document.querySelector('[data-video-frame]');
const videoTitle = document.getElementById('videoModalLabel');
const siteSidebar = document.getElementById('siteSidebar');
const mobileSearchPanel = document.getElementById('mobileSearchPanel');
const themeStorageKey = 'site-theme-v2';
const watchlistStorageKey = 'site-watchlist';
let searchQuery = searchInputs.find((input) => input.value.trim() !== '')?.value.trim() ?? '';
let searchSuggestionTimer = null;
let searchSuggestionController = null;

const setTheme = (theme) => {
    root.setAttribute('data-bs-theme', theme);
    window.localStorage.setItem(themeStorageKey, theme);

    if (themeIcon) {
        themeIcon.className = theme === 'dark' ? 'bi bi-sun-fill' : 'bi bi-moon-stars-fill';
    }
};

setTheme(window.localStorage.getItem(themeStorageKey) ?? 'dark');

themeButton?.addEventListener('click', () => {
    setTheme(root.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark');
});

const currentFilter = () => document.querySelector('[data-content-filter].active')?.dataset.contentFilter ?? 'all';

const syncSearchControls = (source = null) => {
    searchInputs.forEach((input) => {
        if (input !== source) input.value = searchQuery;
    });

    searchClearButtons.forEach((button) => {
        button.hidden = searchQuery === '';
    });
};

const escapeHtml = (value) => String(value ?? '')
    .replaceAll('&', '&amp;')
    .replaceAll('<', '&lt;')
    .replaceAll('>', '&gt;')
    .replaceAll('"', '&quot;')
    .replaceAll("'", '&#039;');

const hideSearchResults = (except = null) => {
    searchResultPanels.forEach((panel) => {
        if (panel !== except) {
            panel.hidden = true;
            panel.innerHTML = '';
        }
    });
};

const setActiveSearchResult = (panel, index) => {
    const items = [...panel.querySelectorAll('[data-search-result-item]')];
    if (!items.length) return;

    const safeIndex = ((index % items.length) + items.length) % items.length;
    items.forEach((item, itemIndex) => item.classList.toggle('is-active', itemIndex === safeIndex));
    panel.dataset.activeIndex = String(safeIndex);
    items[safeIndex].scrollIntoView({ block: 'nearest' });
};

const renderSearchResults = (form, panel, query, results) => {
    if (!results.length) {
        panel.innerHTML = `<div class="site-search-results__empty"><i class="bi bi-search"></i><span>No results for “${escapeHtml(query)}”</span></div>`;
        panel.dataset.activeIndex = '-1';
        panel.hidden = false;
        hideSearchResults(panel);
        return;
    }

    const groups = new Map();
    results.forEach((result) => {
        const group = result.type || 'Content';
        if (!groups.has(group)) groups.set(group, []);
        groups.get(group).push(result);
    });

    const groupHtml = [...groups.entries()].map(([group, items]) => `
        <div class="site-search-results__group">
            <div class="site-search-results__heading">${escapeHtml(group)}</div>
            ${items.map((result) => `
                <a class="site-search-result" href="${escapeHtml(result.url)}" data-search-result-item>
                    <img class="site-search-result__image" src="${escapeHtml(result.image_url)}" alt="" loading="lazy">
                    <span class="site-search-result__copy">
                        <strong>${escapeHtml(result.title)}</strong>
                        ${result.excerpt ? `<small>${escapeHtml(result.excerpt)}</small>` : ''}
                    </span>
                    <i class="bi bi-arrow-right-short" aria-hidden="true"></i>
                </a>
            `).join('')}
        </div>
    `).join('');

    const allResultsUrl = new URL(form.action, window.location.origin);
    allResultsUrl.searchParams.set('q', query);

    panel.innerHTML = `${groupHtml}<a class="site-search-results__all" href="${escapeHtml(allResultsUrl.toString())}"><i class="bi bi-search me-2"></i>See all results for “${escapeHtml(query)}”</a>`;
    panel.dataset.activeIndex = '-1';
    panel.hidden = false;
    hideSearchResults(panel);
};

const loadSearchSuggestions = async (input) => {
    const form = input.closest('[data-search-form]');
    const panel = form?.querySelector('[data-search-results]');
    const endpoint = form?.dataset.searchSuggestionsUrl;
    const query = input.value.trim();

    if (!form || !panel || !endpoint) {
        return;
    }

    searchSuggestionController?.abort();
    searchSuggestionController = new AbortController();

    panel.innerHTML = `<div class="site-search-results__loading"><span class="spinner-border spinner-border-sm" aria-hidden="true"></span><span>${query === '' ? 'Loading recent content…' : 'Searching…'}</span></div>`;
    panel.hidden = false;
    hideSearchResults(panel);

    try {
        const url = new URL(endpoint, window.location.origin);
        url.searchParams.set('q', query);
        const response = await fetch(url, {
            headers: { Accept: 'application/json' },
            signal: searchSuggestionController.signal,
        });

        if (!response.ok) throw new Error('Search request failed');
        const payload = await response.json();

        if (input.value.trim() !== query) return;
        renderSearchResults(form, panel, query, Array.isArray(payload.results) ? payload.results : []);
    } catch (error) {
        if (error.name === 'AbortError') return;
        panel.hidden = true;
        panel.innerHTML = '';
    }
};

const scheduleSearchSuggestions = (input) => {
    window.clearTimeout(searchSuggestionTimer);
    searchSuggestionTimer = window.setTimeout(() => loadSearchSuggestions(input), 220);
};

const applyContentFilters = () => {
    const query = searchQuery.toLocaleLowerCase();
    const filter = currentFilter();

    document.querySelectorAll('[data-content-card]').forEach((card) => {
        const searchable = (card.dataset.searchable ?? '').toLocaleLowerCase();
        const type = card.dataset.contentType ?? '';
        const matchesSearch = query === '' || searchable.includes(query);
        const matchesFilter = filter === 'all' || type === filter;
        const watchlistMode = root.dataset.watchlistMode === 'true';
        const matchesWatchlist = !watchlistMode || card.dataset.watchlisted === 'true';

        card.hidden = !(matchesSearch && matchesFilter && matchesWatchlist);
    });

    syncSearchControls();
};

searchInputs.forEach((input) => {
    input.addEventListener('input', () => {
        searchQuery = input.value.trim();
        syncSearchControls(input);
        applyContentFilters();

        if (input.closest('[data-search-form]')?.dataset.searchMode === 'global') {
            scheduleSearchSuggestions(input);
        }
    });

    input.addEventListener('focus', () => {
        if (input.closest('[data-search-form]')?.dataset.searchMode === 'global') {
            scheduleSearchSuggestions(input);
        }
    });

    input.addEventListener('keydown', (event) => {
        const panel = input.closest('[data-search-form]')?.querySelector('[data-search-results]');
        if (!panel || panel.hidden) return;

        const items = [...panel.querySelectorAll('[data-search-result-item]')];
        const activeIndex = Number(panel.dataset.activeIndex ?? -1);

        if (event.key === 'ArrowDown' && items.length) {
            event.preventDefault();
            setActiveSearchResult(panel, activeIndex + 1);
        } else if (event.key === 'ArrowUp' && items.length) {
            event.preventDefault();
            setActiveSearchResult(panel, activeIndex <= 0 ? items.length - 1 : activeIndex - 1);
        } else if (event.key === 'Enter' && activeIndex >= 0 && items[activeIndex]) {
            event.preventDefault();
            window.location.href = items[activeIndex].href;
        } else if (event.key === 'Escape') {
            panel.hidden = true;
            panel.innerHTML = '';
        }
    });
});

searchForms.forEach((form) => {
    form.addEventListener('submit', (event) => {
        if (form.dataset.searchMode === 'global') {
            return;
        }

        event.preventDefault();
        const input = form.querySelector('[data-site-search]');
        searchQuery = input?.value.trim() ?? searchQuery;
        applyContentFilters();
        Offcanvas.getInstance(mobileSearchPanel)?.hide();
        document.getElementById('latest')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
});

searchClearButtons.forEach((button) => {
    button.addEventListener('click', () => {
        searchQuery = '';
        syncSearchControls();
        applyContentFilters();
        hideSearchResults();
        const input = button.closest('form')?.querySelector('[data-site-search]');
        input?.focus();
        if (input) scheduleSearchSuggestions(input);
    });
});

filterButtons.forEach((button) => {
    button.addEventListener('click', () => {
        filterButtons.forEach((candidate) => candidate.classList.remove('active'));
        button.classList.add('active');
        root.dataset.watchlistMode = 'false';
        watchlistFilter?.classList.remove('is-current');
        applyContentFilters();
    });
});

const parseWatchlist = () => {
    try {
        return new Set(JSON.parse(window.localStorage.getItem(watchlistStorageKey) ?? '[]'));
    } catch {
        return new Set();
    }
};

const saveWatchlist = (watchlist) => {
    window.localStorage.setItem(watchlistStorageKey, JSON.stringify([...watchlist]));
};

const syncWatchlist = () => {
    const watchlist = parseWatchlist();

    document.querySelectorAll('[data-content-card]').forEach((card) => {
        const isSaved = watchlist.has(card.dataset.cardId);
        card.dataset.watchlisted = String(isSaved);
        card.querySelectorAll('[data-watch-button]').forEach((button) => {
            button.setAttribute('aria-pressed', String(isSaved));
            const icon = button.querySelector('i');
            if (icon) icon.className = isSaved ? 'bi bi-bookmark-fill' : 'bi bi-bookmark';
        });
    });
};

document.addEventListener('click', (event) => {
    if (!event.target.closest('[data-search-form]')) hideSearchResults();

    const sidebarLink = event.target.closest('#siteSidebar a, #siteSidebar button');
    if (sidebarLink && window.innerWidth < 1200 && siteSidebar?.classList.contains('show')) {
        Offcanvas.getOrCreateInstance(siteSidebar).hide();
    }

    const watchButton = event.target.closest('[data-watch-button]');

    if (watchButton) {
        const card = watchButton.closest('[data-content-card]');
        if (!card) return;

        const watchlist = parseWatchlist();
        const cardId = card.dataset.cardId;

        if (watchlist.has(cardId)) watchlist.delete(cardId);
        else watchlist.add(cardId);

        saveWatchlist(watchlist);
        syncWatchlist();
        applyContentFilters();
        return;
    }

    const likeButton = event.target.closest('[data-like-button]');
    if (likeButton) {
        const pressed = likeButton.getAttribute('aria-pressed') === 'true';
        likeButton.setAttribute('aria-pressed', String(!pressed));
        return;
    }

    const copyButton = event.target.closest('[data-copy-title]');
    if (copyButton) {
        navigator.clipboard?.writeText(copyButton.dataset.copyTitle ?? '');
    }
});

watchlistFilter?.addEventListener('click', () => {
    const active = root.dataset.watchlistMode === 'true';
    root.dataset.watchlistMode = String(!active);
    watchlistFilter.classList.toggle('is-current', !active);
    applyContentFilters();
    document.getElementById('latest')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
});

videoModal?.addEventListener('show.bs.modal', (event) => {
    const trigger = event.relatedTarget;
    const url = trigger?.dataset.videoUrl;
    const title = trigger?.dataset.videoTitle;

    if (videoFrame && url) videoFrame.src = url;
    if (videoTitle && title) videoTitle.textContent = title;
});

videoModal?.addEventListener('hidden.bs.modal', () => {
    if (videoFrame) videoFrame.src = 'about:blank';
});

mobileSearchPanel?.addEventListener('shown.bs.offcanvas', () => {
    mobileSearchPanel.querySelector('[data-site-search]')?.focus();
});

document.querySelectorAll('[data-spoiler-content]').forEach((content) => {
    const button = document.querySelector(`[data-bs-target="#${content.id}"][data-spoiler-toggle]`);
    const label = button?.querySelector('[data-spoiler-label]');
    const icon = button?.querySelector('i');

    content.addEventListener('shown.bs.collapse', () => {
        if (label) label.textContent = button.dataset.hideLabel || 'Hide spoilers';
        if (icon) icon.className = 'bi bi-eye-slash me-1';
    });

    content.addEventListener('hidden.bs.collapse', () => {
        if (label) label.textContent = button.dataset.showLabel || 'Show spoilers';
        if (icon) icon.className = 'bi bi-eye me-1';
    });
});

syncWatchlist();
syncSearchControls();
applyContentFilters();
