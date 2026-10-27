import './bootstrap';
import 'bootstrap';
import { Offcanvas } from 'bootstrap';

const root = document.documentElement;
const themeButton = document.querySelector('[data-theme-toggle]');
const themeIcon = document.querySelector('[data-theme-icon]');
const searchInputs = [...document.querySelectorAll('[data-site-search]')];
const searchForms = [...document.querySelectorAll('[data-search-form]')];
const searchClearButtons = [...document.querySelectorAll('[data-search-clear]')];
const filterButtons = [...document.querySelectorAll('[data-content-filter]')];
const watchlistFilter = document.querySelector('[data-watchlist-filter]');
const videoModal = document.getElementById('videoModal');
const videoFrame = document.querySelector('[data-video-frame]');
const videoTitle = document.getElementById('videoModalLabel');
const siteSidebar = document.getElementById('siteSidebar');
const mobileSearchPanel = document.getElementById('mobileSearchPanel');
const themeStorageKey = 'site-theme-v2';
const watchlistStorageKey = 'site-watchlist';
let searchQuery = '';

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
    });
});

searchForms.forEach((form) => {
    form.addEventListener('submit', (event) => {
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
        button.closest('form')?.querySelector('[data-site-search]')?.focus();
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

syncWatchlist();
syncSearchControls();
applyContentFilters();
