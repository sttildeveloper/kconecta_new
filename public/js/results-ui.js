(() => {
    const SEARCH_DEBOUNCE_MS = 300;
    let searchTimer;
    let pendingSearch;

    const currentSearchForm = () => document.querySelector('[data-results-search]');
    const filterModal = () => document.querySelector('[data-results-filter-modal]');
    const modalIsOpen = () => filterModal()?.classList.contains('is-open');
    const setActiveFilter = (selector, value) => {
        document.querySelectorAll(selector).forEach((button) => {
            const isActive = button.dataset.resultsSort === value || button.dataset.resultsMinRating === value;
            button.classList.toggle('is-active', isActive);
            button.setAttribute('aria-pressed', String(isActive));
        });
    };
    const closeFilters = () => {
        const modal = filterModal();
        modal?.classList.remove('is-open');
        modal?.setAttribute('aria-hidden', 'true');
        document.querySelector('[data-results-filter-open]')?.setAttribute('aria-expanded', 'false');
    };
    const openFilters = () => {
        const modal = filterModal();
        modal?.classList.add('is-open');
        modal?.setAttribute('aria-hidden', 'false');
        document.querySelector('[data-results-filter-open]')?.setAttribute('aria-expanded', 'true');
        modal?.querySelector('[data-results-filter-close]')?.focus();
    };

    const refreshResults = async () => {
        const form = currentSearchForm();
        if (!form) return;
        const url = new URL(form.action, window.location.origin);
        url.search = new URLSearchParams(new FormData(form)).toString();
        pendingSearch?.abort();
        pendingSearch = new AbortController();
        form.setAttribute('aria-busy', 'true');
        try {
            const response = await fetch(url.toString(), { credentials: 'same-origin', headers: { 'X-Requested-With': 'XMLHttpRequest' }, signal: pendingSearch.signal });
            if (!response.ok) throw new Error(`Search request failed: ${response.status}`);
            const documentResponse = new DOMParser().parseFromString(await response.text(), 'text/html');
            const nextResults = documentResponse.querySelector('.results-list');
            const currentResults = document.querySelector('.results-list');
            if (!nextResults || !currentResults) throw new Error('Search results could not be rendered.');
            currentResults.replaceWith(nextResults);
            window.history.replaceState({}, '', url);
        } catch (error) {
            if (error.name !== 'AbortError') form.submit();
        } finally {
            currentSearchForm()?.removeAttribute('aria-busy');
        }
    };

    document.addEventListener('input', (event) => {
        if (!event.target.matches('#professional-query')) return;
        window.clearTimeout(searchTimer);
        searchTimer = window.setTimeout(refreshResults, SEARCH_DEBOUNCE_MS);
    });
    document.addEventListener('submit', (event) => {
        if (!event.target.matches('[data-results-search]')) return;
        event.preventDefault();
        window.clearTimeout(searchTimer);
        refreshResults();
    });
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && modalIsOpen()) closeFilters();
        const card = event.target.closest('[data-profile-url]');
        if (card && (event.key === 'Enter' || event.key === ' ')) {
            event.preventDefault();
            window.location.assign(card.dataset.profileUrl);
        }
    });
    document.addEventListener('click', (event) => {
        if (event.target.closest('[data-results-filter-open]')) { openFilters(); return; }
        if (event.target.closest('[data-results-filter-close]')) { closeFilters(); return; }
        if (event.target.closest('[data-results-filter-apply]')) { closeFilters(); refreshResults(); return; }
        if (event.target.closest('[data-results-filter-reset]')) {
            const form = currentSearchForm();
            const sortInput = form?.querySelector('[data-results-sort-input]');
            const ratingInput = form?.querySelector('[data-results-min-rating-input]');
            if (sortInput) sortInput.value = 'distance';
            if (ratingInput) ratingInput.value = '0';
            setActiveFilter('[data-results-sort]', 'distance');
            setActiveFilter('[data-results-min-rating]', '0');
            return;
        }
        const sortButton = event.target.closest('[data-results-sort]');
        if (sortButton) {
            const sortInput = currentSearchForm()?.querySelector('[data-results-sort-input]');
            if (!sortInput) return;
            sortInput.value = sortButton.dataset.resultsSort;
            setActiveFilter('[data-results-sort]', sortButton.dataset.resultsSort);
            return;
        }
        const ratingButton = event.target.closest('[data-results-min-rating]');
        if (ratingButton) {
            const ratingInput = currentSearchForm()?.querySelector('[data-results-min-rating-input]');
            if (!ratingInput) return;
            ratingInput.value = ratingButton.dataset.resultsMinRating;
            setActiveFilter('[data-results-min-rating]', ratingButton.dataset.resultsMinRating);
            return;
        }
        const favorite = event.target.closest('[data-favorite]');
        if (favorite) {
            const active = favorite.classList.toggle('is-active');
            favorite.textContent = active ? '♥' : '♡';
            favorite.setAttribute('aria-pressed', String(active));
            return;
        }
        const interactive = event.target.closest('a,button,input,label');
        const card = event.target.closest('[data-profile-url]');
        if (card && !interactive) window.location.assign(card.dataset.profileUrl);
    });
})();
