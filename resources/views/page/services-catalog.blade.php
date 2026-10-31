<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#82d1f3">
    <title>Servicios para el hogar | Kconecta</title>
    <link rel="stylesheet" href="{{ asset('css/page/services-catalog.css') }}">
</head>
<body>
<main class="services-page">
    <header class="services-topbar" aria-label="Navegación de servicios">
        <a class="services-back" href="{{ url('/') }}" aria-label="Volver al inicio">‹</a>
        <img src="{{ asset('img/kconecta/kconecta_logo.webp') }}" alt="Kconecta" class="services-logo">
        <p class="services-location"><span aria-hidden="true">⌖</span><strong>Barcelona</strong><small>(08028) demo</small></p>
    </header>

    <section class="services-content" aria-labelledby="services-title">
        <h1 id="services-title">Servicios para el hogar</h1>
        <p class="services-subtitle">Busca el servicio que necesitas</p>

        <form class="services-search" role="search" data-services-search>
            <label class="sr-only" for="service-query">¿Qué servicio necesitas?</label>
            <span class="services-search__icon" aria-hidden="true">⌕</span>
            <input id="service-query" type="search" placeholder="¿Qué servicio necesitas?" autocomplete="off" data-services-query>
            <button type="submit" aria-label="Buscar servicio">⌕</button>
        </form>

        <section class="services-explorer" aria-labelledby="explorer-title">
            <h2 id="explorer-title">Explora los servicios</h2>
            <p class="sr-only" aria-live="polite" data-services-status></p>
            <div class="services-grid">
                @foreach ($categories as $category)
                    <a class="service-card service-card--{{ $category['tone'] }}"
                       href="{{ $category['id'] ? url('/result/services').'?mode=1&amp;sti%5B%5D='.$category['id'] : '#' }}"
                       data-service-card
                       data-service-name="{{ $category['name'] }}"
                       data-service-key="{{ $category['key'] }}"
                       data-service-id="{{ $category['id'] ?? '' }}"
                       aria-label="Buscar {{ $category['name'] }}">
                        <img src="{{ asset('img/kconecta/'.$category['asset']) }}" alt="" loading="lazy">
                        <span>{{ $category['name'] }}</span>
                    </a>
                @endforeach
            </div>
            <a class="other-services-link" href="{{ url('/result/services') }}?mode=1&amp;other_services=1" data-other-services-link>
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3v18M3 12h18"/><circle cx="12" cy="12" r="9"/></svg>
                <span>Otros servicios</span>
            </a>
        </section>
    </section>

    <section class="service-results" data-service-results hidden aria-labelledby="results-title">
        <h1 id="results-title">Resultados: Lampista</h1>
        <p class="services-subtitle">Profesionales de demostración · sin búsqueda geográfica real</p>
        <div class="results-layout">
            <aside class="results-filters" aria-label="Filtros de demostración">
                <h2>Filtros</h2>
                <fieldset><legend>Ordenar por</legend><div class="filter-options"><button type="button" class="is-active" data-demo-filter>Distancia</button><button type="button" data-demo-filter>Valoración</button></div></fieldset>
                <fieldset><legend>Valoración mínima</legend><div class="filter-options"><button type="button" class="is-active" data-demo-filter>Todas</button><button type="button" data-demo-filter>4,5+</button><button type="button" data-demo-filter>4,8+</button></div></fieldset>
                <p>Filtros demostrativos sobre los resultados de ejemplo.</p>
            </aside>
            <div class="results-list">
                <form class="results-search" data-results-search><label class="sr-only" for="professional-query">Buscar profesionales</label><span aria-hidden="true">⌕</span><input id="professional-query" type="search" placeholder="Buscar profesionales"><button type="submit" aria-label="Buscar profesionales">⌕</button></form>
                <h2><span data-results-count>4</span> profesionales de demostración</h2>
                <p class="results-disclaimer"><span aria-hidden="true">ⓘ</span> Contenido ilustrativo: los profesionales y datos mostrados son de demostración.</p>
                <div class="professional-cards">
                    <article class="professional-card" data-professional-card data-professional-name="Instalaciones Pérez">
                        <img src="{{ asset('img/kconecta/demo-lampista-1.webp') }}" alt="Retrato de demostración de Instalaciones Pérez">
                        <div class="professional-card__body"><small>LAMPISTA</small><h3>Instalaciones Pérez</h3><p><b>★ 4.9</b> (124) <span>⌖ A 1,2 km · Barcelona (ejemplo)</span></p><div class="professional-tags"><span>Calefacción</span><span>Boilers</span></div><div class="professional-actions"><button type="button" data-demo-contact>Contactar</button><button type="button" data-demo-profile>Ver perfil</button></div></div><button class="professional-favorite" type="button" aria-label="Añadir Instalaciones Pérez a favoritos" data-demo-favorite>♡</button>
                    </article>
                    <article class="professional-card" data-professional-card data-professional-name="Fontanería Rápida">
                        <img src="{{ asset('img/kconecta/demo-lampista-2.webp') }}" alt="Retrato de demostración de Fontanería Rápida">
                        <div class="professional-card__body"><small>LAMPISTA</small><h3>Fontanería Rápida</h3><p><b>★ 4.8</b> (96) <span>⌖ A 1,5 km · Barcelona (ejemplo)</span></p><div class="professional-tags"><span>Urgencias 24h</span><span>Reformas</span></div><div class="professional-actions"><button type="button" data-demo-contact>Contactar</button><button type="button" data-demo-profile>Ver perfil</button></div></div><button class="professional-favorite" type="button" aria-label="Añadir Fontanería Rápida a favoritos" data-demo-favorite>♡</button>
                    </article>
                    <article class="professional-card" data-professional-card data-professional-name="AquaServeis BCN">
                        <img src="{{ asset('img/kconecta/demo-lampista-3.webp') }}" alt="Retrato de demostración de AquaServeis BCN">
                        <div class="professional-card__body"><small>LAMPISTA</small><h3>AquaServeis BCN</h3><p><b>★ 4.7</b> (68) <span>⌖ A 2,1 km · Barcelona (ejemplo)</span></p><div class="professional-tags"><span>Desatascos</span><span>Reformas</span></div><div class="professional-actions"><button type="button" data-demo-contact>Contactar</button><button type="button" data-demo-profile>Ver perfil</button></div></div><button class="professional-favorite" type="button" aria-label="Añadir AquaServeis BCN a favoritos" data-demo-favorite>♡</button>
                    </article>
                    <article class="professional-card" data-professional-card data-professional-name="Servicios del Hogar">
                        <img src="{{ asset('img/kconecta/demo-lampista-4.webp') }}" alt="Retrato de demostración de Servicios del Hogar">
                        <div class="professional-card__body"><small>LAMPISTA</small><h3>Servicios del Hogar</h3><p><b>★ 4.9</b> (210) <span>⌖ A 2,3 km · Barcelona (ejemplo)</span></p><div class="professional-tags"><span>Calefacción</span><span>Aire acondicionado</span></div><div class="professional-actions"><button type="button" data-demo-contact>Contactar</button><button type="button" data-demo-profile>Ver perfil</button></div></div><button class="professional-favorite" type="button" aria-label="Añadir Servicios del Hogar a favoritos" data-demo-favorite>♡</button>
                    </article>
                </div>
                <p class="sr-only" aria-live="polite" data-results-status></p>
            </div>
        </div>
    </section>
</main>
<script src="{{ asset('js/services-catalog.js') }}?v={{ filemtime(public_path('js/services-catalog.js')) }}" defer></script>
</body>
</html>
