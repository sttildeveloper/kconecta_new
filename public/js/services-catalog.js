(() => {
    const catalog = document.querySelector('.services-content');
    const results = document.querySelector('[data-service-results]');
    const topBack = document.querySelector('.services-back');
    const selectedService = new URLSearchParams(window.location.search).get('service');
    const localTypeIds = { reformas: 23 };
    const storageKey = 'kconectaSearchLocation';
    let savedLocation = {};
    try { savedLocation = JSON.parse(sessionStorage.getItem(storageKey) || '{}') || {}; } catch { savedLocation = {}; }
    const normalise = (value) => value.normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase().trim();
    const cardsByService = [...document.querySelectorAll('[data-service-card]')];
    const typeIdFor = (card) => card?.dataset.serviceId || localTypeIds[card?.dataset.serviceKey] || '';
    const resultUrlFor = (typeId) => {
        const query = new URLSearchParams({ mode: '1', 'sti[]': String(typeId) });
        ['latitude', 'longitude', 'city', 'province', 'address', 'zoom'].forEach((key) => {
            if (savedLocation[key]) query.set(key, savedLocation[key]);
        });
        return `${window.location.pathname}?${query.toString()}`;
    };

    cardsByService.forEach((card) => {
        const typeId = typeIdFor(card);
        if (typeId) card.href = resultUrlFor(typeId);
    });

    const selectedCard = cardsByService
        .find((card) => card.dataset.serviceKey === selectedService);

    if (selectedService && typeIdFor(selectedCard)) {
        window.location.replace(resultUrlFor(typeIdFor(selectedCard)));
        return;
    }

    if (selectedService === 'lampista' && catalog && results) {
        catalog.hidden = true;
        results.hidden = false;
        topBack.href = window.location.pathname;
        topBack.setAttribute('aria-label', 'Volver a los servicios');
        document.title = 'Resultados: Lampista | Kconecta';
    }

    const catalogForm = document.querySelector('[data-services-search]');
    const catalogInput = document.querySelector('[data-services-query]');
    const catalogCards = cardsByService;
    const catalogStatus = document.querySelector('[data-services-status]');
    if (catalogForm && catalogInput) {
        const filterCatalog = () => {
            const query = normalise(catalogInput.value);
            let visible = 0;
            catalogCards.forEach((card) => {
                const matches = !query || normalise(card.dataset.serviceName || '').includes(query);
                card.hidden = !matches;
                if (matches) visible += 1;
            });
            catalogStatus.textContent = query ? `${visible} servicios encontrados.` : '';
            return visible;
        };
        catalogInput.addEventListener('input', filterCatalog);
        catalogForm.addEventListener('submit', (event) => {
            event.preventDefault();
            const matches = catalogCards.filter((card) => !card.hidden);
            if (matches.length === 1) window.location.assign(matches[0].href);
        });
    }

    const resultForm = document.querySelector('[data-results-search]');
    const resultInput = document.querySelector('#professional-query');
    const professionals = [...document.querySelectorAll('[data-professional-card]')];
    const count = document.querySelector('[data-results-count]');
    const resultStatus = document.querySelector('[data-results-status]');
    const updateProfessionals = () => {
        if (!resultInput) return;
        const query = normalise(resultInput.value);
        const visible = professionals.filter((card) => {
            const matches = !query || normalise(card.dataset.professionalName || '').includes(query);
            card.hidden = !matches;
            return matches;
        }).length;
        if (count) count.textContent = visible;
        if (resultStatus) resultStatus.textContent = `${visible} profesionales de demostración mostrados.`;
    };
    resultInput?.addEventListener('input', updateProfessionals);
    resultForm?.addEventListener('submit', (event) => { event.preventDefault(); updateProfessionals(); });

    document.querySelectorAll('[data-demo-filter]').forEach((button) => button.addEventListener('click', () => {
        button.parentElement.querySelectorAll('button').forEach((item) => item.classList.remove('is-active'));
        button.classList.add('is-active');
    }));
    document.querySelectorAll('[data-demo-favorite]').forEach((button) => button.addEventListener('click', () => {
        const active = button.classList.toggle('is-active');
        button.textContent = active ? '♥' : '♡';
        button.setAttribute('aria-pressed', String(active));
    }));
    document.querySelectorAll('[data-demo-contact], [data-demo-profile]').forEach((button) => button.addEventListener('click', () => {
        if (resultStatus) resultStatus.textContent = 'Acción no disponible: es una interfaz de demostración.';
    }));
})();
