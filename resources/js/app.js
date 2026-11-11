import './bootstrap';
import 'bootstrap';
import { Offcanvas, Collapse } from 'bootstrap';

const root = document.documentElement;
const messages = window.cinehubMessages || {};
const message = (key, english) => messages[key] || english;
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

// A search is an explicit submission or a chosen autocomplete result, never a keystroke.
const newSearchEvent = () => (window.crypto?.randomUUID?.() ?? `${Date.now()}_${Math.random().toString(36).slice(2)}`).replaceAll('-', '');

const trackSelectedSuggestion = (form) => {
    const term = form?.querySelector('[data-site-search]')?.value.trim() ?? '';
    const endpoint = form?.dataset.searchSelectionUrl;
    const token = document.querySelector('meta[name="csrf-token"]')?.content;
    if (!term || !endpoint || !token) return;

    const data = new FormData();
    data.set('_token', token);
    data.set('q', term);
    data.set('search_event', newSearchEvent());
    if (navigator.sendBeacon?.(endpoint, data)) return;
    // Keep the request alive while the browser opens the selected result.
    fetch(endpoint, { method: 'POST', body: data, credentials: 'same-origin', keepalive: true }).catch(() => {});
};

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
        // No matches: hide the autocomplete, but leave the form ready for Enter.
        // The submitted search is recorded even when the results page is empty.
        panel.innerHTML = '';
        panel.dataset.activeIndex = '-1';
        panel.hidden = true;
        return;
    }

    const groups = new Map();
    results.forEach((result) => {
        const group = result.type || message('content', 'Content');
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
    allResultsUrl.searchParams.set('search_event', newSearchEvent());

    panel.innerHTML = `${groupHtml}<a class="site-search-results__all" href="${escapeHtml(allResultsUrl.toString())}"><i class="bi bi-search me-2"></i>${escapeHtml(message('seeAllResultsFor', 'See all results for'))} “${escapeHtml(query)}”</a>`;
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

    panel.innerHTML = `<div class="site-search-results__loading"><span class="spinner-border spinner-border-sm" aria-hidden="true"></span><span>${query === '' ? message('loadingRecent', 'Loading recent content…') : message('searching', 'Searching…')}</span></div>`;
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
            trackSelectedSuggestion(input.closest('[data-search-form]'));
            window.location.href = items[activeIndex].href;
        } else if (event.key === 'Escape') {
            panel.hidden = true;
            panel.innerHTML = '';
        }
    });
});

searchForms.forEach((form) => {
    form.querySelector('[data-search-results]')?.addEventListener('click', (event) => {
        if (event.target.closest('[data-search-result-item]')) {
            trackSelectedSuggestion(form);
        }
    });

    form.addEventListener('submit', (event) => {
        if (form.dataset.searchMode === 'global') {
            // This event id stays in the URL on refresh/pagination but changes
            // when the visitor deliberately submits a new search.
            let eventInput = form.querySelector('input[name="search_event"]');
            if (!eventInput) {
                eventInput = document.createElement('input');
                eventInput.type = 'hidden';
                eventInput.name = 'search_event';
                form.appendChild(eventInput);
            }
            eventInput.value = newSearchEvent();
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
        if (label) label.textContent = button.dataset.hideLabel || message('hideSpoilers', 'Hide spoilers');
        if (icon) icon.className = 'bi bi-eye-slash me-1';
    });

    content.addEventListener('hidden.bs.collapse', () => {
        if (label) label.textContent = button.dataset.showLabel || message('showSpoilers', 'Show spoilers');
        if (icon) icon.className = 'bi bi-eye me-1';
    });
});

syncWatchlist();
syncSearchControls();
applyContentFilters();

// The server always validates authentication, CSRF, and moderation. Forms keep their
// ordinary POST action as a no-JavaScript fallback; with JS they never navigate.
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
const numberFormat = new Intl.NumberFormat(root.lang || 'en');

const submitJson = async (form) => {
    const response = await fetch(form.action, {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfToken,
        },
        credentials: 'same-origin',
        body: new FormData(form),
    });
    const result = await response.json().catch(() => ({}));
    if (!response.ok) throw new Error(result.message || message('actionError', 'Could not complete this action. Please try again.'));
    return result;
};

let feedbackTimeout;
const showActionError = (message) => {
    let notice = document.querySelector('[data-ajax-feedback]');
    if (!notice) {
        notice = document.createElement('div');
        notice.className = 'alert alert-danger position-fixed bottom-0 end-0 m-3 shadow';
        notice.style.zIndex = '1100';
        notice.setAttribute('role', 'alert');
        notice.dataset.ajaxFeedback = '';
        document.body.appendChild(notice);
    }
    notice.textContent = message;
    notice.hidden = false;
    clearTimeout(feedbackTimeout);
    feedbackTimeout = setTimeout(() => { notice.hidden = true; }, 5000);
};

// Clicks on a post like, comment like, favorite or Home like update in place.
// Delegation also works for newly inserted comments and replies.
document.addEventListener('submit', async (event) => {
    const form = event.target.closest('form[data-home-like], form[data-ajax-post-like], form[data-ajax-comment-like], form[data-ajax-favorite], form[data-ajax-interests]');
    if (!form) return;
    event.preventDefault();
    if (form.dataset.busy === '1') return;
    const button = form.querySelector('button[type="submit"]');
    const isInterests = form.matches('[data-ajax-interests]');
    form.dataset.busy = '1';
    if (button) button.disabled = true;

    try {
        const result = await submitJson(form);
        if (form.matches('[data-home-like], [data-ajax-post-like]')) {
            if (typeof result.liked !== 'boolean' || typeof result.likes_count !== 'number') throw new Error(message('serverError', 'Unexpected server response. Please try again.'));
            document.querySelectorAll('form[data-home-like], form[data-ajax-post-like]').forEach((other) => {
                if (other.action !== form.action) return;
                const otherButton = other.querySelector('button');
                if (!otherButton) return;
                otherButton.setAttribute('aria-pressed', String(result.liked));
                const count = otherButton.querySelector('[data-like-count]');
                if (count) count.textContent = numberFormat.format(result.likes_count);
                if (other.matches('[data-ajax-post-like]')) {
                    otherButton.classList.toggle('btn-danger', result.liked);
                    otherButton.classList.toggle('btn-outline-danger', !result.liked);
                    const icon = otherButton.querySelector('[data-like-icon]');
                    if (icon) {
                        icon.classList.toggle('bi-heart-fill', result.liked);
                        icon.classList.toggle('bi-heart', !result.liked);
                    }
                    const label = otherButton.querySelector('[data-like-label]');
                    if (label) label.textContent = result.liked ? other.dataset.onLabel : other.dataset.offLabel;
                }
            });
            document.querySelectorAll('[data-post-like-count]').forEach((count) => {
                count.textContent = numberFormat.format(result.likes_count);
            });
        } else if (form.matches('[data-ajax-comment-like]')) {
            if (typeof result.liked !== 'boolean' || typeof result.likes_count !== 'number') throw new Error(message('serverError', 'Unexpected server response. Please try again.'));
            button?.setAttribute('aria-pressed', String(result.liked));
            const count = button?.querySelector('[data-comment-like-count]');
            if (count) count.textContent = numberFormat.format(result.likes_count);
            const icon = button?.querySelector('[data-comment-like-icon]');
            icon?.classList.toggle('bi-heart-fill', result.liked);
            icon?.classList.toggle('text-danger', result.liked);
            icon?.classList.toggle('bi-heart', !result.liked);
        } else if (form.matches('[data-ajax-favorite]')) {
            if (typeof result.saved !== 'boolean') throw new Error(message('serverError', 'Unexpected server response. Please try again.'));
            button?.setAttribute('aria-pressed', String(result.saved));
            button?.classList.toggle('btn-secondary', result.saved);
            button?.classList.toggle('btn-outline-secondary', !result.saved);
            const icon = button?.querySelector('[data-favorite-icon]');
            icon?.classList.toggle('bi-bookmark-fill', result.saved);
            icon?.classList.toggle('bi-bookmark', !result.saved);
            const label = button?.querySelector('[data-favorite-label]');
            if (label) label.textContent = result.saved ? form.dataset.onLabel : form.dataset.offLabel;
        } else if (isInterests) {
            if (typeof result.message !== 'string') throw new Error(message('serverError', 'Unexpected server response. Please try again.'));
            const status = form.querySelector('[data-interests-status]');
            if (status) {
                status.textContent = result.message;
                status.className = 'small mt-2 text-success';
                status.hidden = false;
            }
        }
    } catch (error) {
        if (isInterests) {
            const status = form.querySelector('[data-interests-status]');
            if (status) {
                status.textContent = error.message || message('interestsError', 'Unable to save your interests.');
                status.className = 'small mt-2 text-danger';
                status.hidden = false;
            }
        } else {
            showActionError(error.message || message('actionError', 'Could not complete this action. Please try again.'));
        }
    } finally {
        form.dataset.busy = '0';
        if (button) button.disabled = false;
    }
});

const addCommentToPage = (result) => {
    if (typeof result.html !== 'string' || !result.html || !Number.isInteger(result.comment_id)) {
        throw new Error(message('serverError', 'Unexpected server response. Please try again.'));
    }
    const list = document.querySelector('[data-comment-list]');
    if (!list) return;
    const fragment = document.createElement('div');
    fragment.innerHTML = result.html; // Blade already escapes comment content on the server.
    if (result.parent_id) {
        const thread = list.querySelector(`[data-comment-thread-id="${Number(result.parent_id)}"]`);
        const replies = thread?.querySelector('[data-comment-replies]');
        if (replies) replies.append(...fragment.childNodes);
        else return;
    } else {
        list.querySelector('[data-comment-empty]')?.remove();
        fragment.dataset.commentThreadId = String(result.comment_id);
        const replies = document.createElement('div');
        replies.dataset.commentReplies = '';
        fragment.appendChild(replies);
        list.prepend(fragment);
    }
    list.querySelectorAll('[data-moderated-comment]').forEach(setupModeratedComment);
    if (result.visibility === 'public' && Number.isInteger(result.approved_count)) {
        document.querySelectorAll('[data-public-comment-count]').forEach((count) => {
            count.textContent = numberFormat.format(result.approved_count);
        });
    }
};

// While typing, check with the server; blocked text cannot be submitted.
// Both original comments and newly added reply forms use the same code.
const setupModeratedComment = (form) => {
    if (form.dataset.moderationBound === '1') return;
    form.dataset.moderationBound = '1';
    const input = form.querySelector('textarea[name="body"]');
    const submit = form.querySelector('[data-comment-submit]');
    const warning = form.querySelector('[data-comment-warning]');
    const success = form.querySelector('[data-comment-success]');
    if (!input || !submit || !warning || !csrfToken) return;

    let timer;
    let controller;
    let checkedText = null;
    let classification = null;
    let sending = false;

    const display = (message) => {
        warning.textContent = message;
        warning.hidden = message === '';
    };

    const check = async () => {
        const text = input.value.trim();
        controller?.abort();
        if (text.length < 5) {
            checkedText = null;
            classification = null;
            submit.disabled = false;
            display('');
            return;
        }
        controller = new AbortController();
        const requestController = controller;
        submit.disabled = true;
        try {
            const response = await fetch(form.dataset.previewUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                credentials: 'same-origin',
                body: JSON.stringify({ body: text }),
                signal: requestController.signal,
            });
            if (!response.ok) throw new Error('moderation-failed');
            const result = await response.json();
            if (input.value.trim() !== text || controller !== requestController) return;
            checkedText = text;
            classification = result.classification;
            if (!['safe', 'review', 'blocked'].includes(classification)) throw new Error('moderation-failed');
            display(classification === 'blocked' ? form.dataset.blockWarning : '');
            submit.disabled = sending || classification === 'blocked';
        } catch (error) {
            if (error.name === 'AbortError' || controller !== requestController) return;
            checkedText = null;
            classification = null;
            display(form.dataset.checkError);
            submit.disabled = true;
        }
    };

    input.addEventListener('input', () => {
        clearTimeout(timer);
        controller?.abort();
        checkedText = null;
        classification = null;
        display('');
        if (success) success.hidden = true;
        submit.disabled = sending || input.value.trim().length >= 5;
        timer = setTimeout(check, 350);
    });

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        if (sending || !form.reportValidity()) return;
        clearTimeout(timer);
        if (checkedText !== input.value.trim() || !['safe', 'review', 'blocked'].includes(classification)) await check();
        if (!['safe', 'review'].includes(classification) || checkedText !== input.value.trim()) return;

        sending = true;
        submit.disabled = true;
        const originalText = input.value;
        try {
            const result = await submitJson(form);
            if (typeof result.html !== 'string' || !result.html) throw new Error(message('serverError', 'Unexpected server response. Please try again.'));
            addCommentToPage(result);
            if (input.value === originalText) {
                form.reset();
                checkedText = null;
                classification = null;
                display('');
            }
            if (success) {
                success.textContent = result.message || '';
                success.hidden = false;
            }
            // A reply is already visible under its parent; collapse its editor without moving the viewport.
            const replyPanel = form.closest('.collapse');
            if (replyPanel) Collapse.getOrCreateInstance(replyPanel).hide();
        } catch (error) {
            display(error.message || form.dataset.checkError);
        } finally {
            sending = false;
            submit.disabled = classification === 'blocked' ||
                (input.value.trim().length >= 5 && checkedText !== input.value.trim());
        }
    });
    if (input.value.trim()) check();
};

document.querySelectorAll('[data-moderated-comment]').forEach(setupModeratedComment);
