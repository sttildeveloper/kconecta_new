(() => {
    const SEARCH_DEBOUNCE_MS = 300;
    let searchTimer;
    let pendingSearch;

    const currentSearchForm = () => document.querySelector('[data-results-search]');
    const setActiveFilter = (selector, value) => {
        document.querySelectorAll(selector).forEach((button) => {
            const isActive = button.dataset.resultsSort === value || button.dataset.resultsMinRating === value;
            button.classList.toggle('is-active', isActive);
            button.setAttribute('aria-pressed', String(isActive));
        });
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
            const response = await fetch(url.toString(), {
                credentials: 'same-origin',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                signal: pendingSearch.signal,
            });
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

    document.addEventListener('click', (event) => {
        const sortButton = event.target.closest('[data-results-sort]');
        if (sortButton) {
            const sortInput = currentSearchForm()?.querySelector('[data-results-sort-input]');
            if (!sortInput || sortInput.value === sortButton.dataset.resultsSort) return;

            sortInput.value = sortButton.dataset.resultsSort;
            setActiveFilter('[data-results-sort]', sortButton.dataset.resultsSort);
            refreshResults();
            return;
        }

        const ratingButton = event.target.closest('[data-results-min-rating]');
        if (ratingButton) {
            const ratingInput = currentSearchForm()?.querySelector('[data-results-min-rating-input]');
            if (!ratingInput || ratingInput.value === ratingButton.dataset.resultsMinRating) return;

            ratingInput.value = ratingButton.dataset.resultsMinRating;
            setActiveFilter('[data-results-min-rating]', ratingButton.dataset.resultsMinRating);
            refreshResults();
            return;
        }

        const favorite = event.target.closest('[data-favorite]');
        if (favorite) {
            const active = favorite.classList.toggle('is-active');
            favorite.textContent = active ? '♥' : '♡';
            favorite.setAttribute('aria-pressed', String(active));
            return;
        }

        if (event.target.closest('[data-pending-action]')) {
            const status = document.querySelector('[data-results-status]');
            if (status) status.textContent = 'Contacto pendiente de auditoría funcional.';
        }
    });
})();
