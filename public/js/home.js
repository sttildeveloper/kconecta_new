(() => {
    const storageKey = 'kconectaSearchLocation';
    const locationForm = document.querySelector('[data-home-search]');
    const locationButton = document.querySelector('[data-home-use-location]');
    const locationStatus = document.querySelector('[data-home-location-status]');
    const addressForm = document.querySelector('[data-home-address]');

    const saveLocation = (location) => sessionStorage.setItem(storageKey, JSON.stringify(location));
    const openCatalog = (url) => window.location.assign(url);
    const setStatus = (message, error = false) => {
        if (!locationStatus) return;
        locationStatus.textContent = message;
        locationStatus.style.color = error ? '#a12b36' : '';
    };

    if (locationForm && locationButton) {
        locationButton.addEventListener('click', () => {
            if (!navigator.geolocation) {
                setStatus('Tu navegador no permite obtener la ubicación.', true);
                return;
            }
            locationButton.disabled = true;
            setStatus('Obteniendo tu ubicación…');
            navigator.geolocation.getCurrentPosition(
                ({ coords }) => {
                    saveLocation({
                        latitude: String(coords.latitude),
                        longitude: String(coords.longitude),
                        zoom: locationForm.querySelector('[data-home-zoom]')?.value || '13',
                    });
                    setStatus('Ubicación aplicada. Elige el servicio que necesitas.');
                    window.setTimeout(() => openCatalog(locationForm.dataset.homeCatalogUrl), 220);
                },
                () => {
                    locationButton.disabled = false;
                    setStatus('No se pudo usar tu ubicación. Prueba con una población.', true);
                },
                { enableHighAccuracy: false, timeout: 10000, maximumAge: 300000 }
            );
        });
    }

    addressForm?.addEventListener('submit', (event) => {
        event.preventDefault();
        const city = addressForm.querySelector('[name="city"]')?.value.trim() || '';
        const address = addressForm.querySelector('[name="address"]')?.value.trim() || '';
        saveLocation({ city, address, zoom: '13' });
        openCatalog(addressForm.dataset.homeCatalogUrl);
    });

    document.querySelector('[data-home-clear-location]')?.addEventListener('click', () => {
        sessionStorage.removeItem(storageKey);
    });
})();
