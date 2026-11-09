(() => {
    const catalog = document.querySelector('.services-content');
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

    const otherServicesLink = document.querySelector('[data-other-services-link]');
    if (otherServicesLink) {
        const query = new URLSearchParams({ mode: '1', other_services: '1' });
        ['latitude', 'longitude', 'city', 'province', 'address', 'zoom'].forEach((key) => {
            if (savedLocation[key]) query.set(key, savedLocation[key]);
        });
        otherServicesLink.href = `${window.location.pathname}?${query.toString()}`;
    }

    const selectedCard = cardsByService
        .find((card) => card.dataset.serviceKey === selectedService);

    if (selectedService && typeIdFor(selectedCard)) {
        window.location.replace(resultUrlFor(typeIdFor(selectedCard)));
        return;
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

})();
