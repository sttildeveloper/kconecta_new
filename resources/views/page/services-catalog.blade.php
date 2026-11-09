<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#82d1f3">
    <title>Servicios para el hogar | Kconecta</title>
    @include('layouts.partials.favicon')
    <link rel="stylesheet" href="{{ asset('css/page/services-catalog.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/site-footer.css') }}?v={{ filemtime(public_path('css/components/site-footer.css')) }}">
</head>
<body>
<main class="services-page">
    <header class="services-topbar" aria-label="Navegación de servicios">
        <a class="services-back" href="{{ url('/') }}" aria-label="Volver al inicio">‹</a>
        <img src="{{ asset('img/kconecta_icon.webp') }}" alt="Kconecta" class="services-logo">
        <p class="services-location"><span aria-hidden="true">⌖</span><strong>Ubicación</strong><small>Selecciona un servicio</small></p>
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
                <img src="{{ asset('img/kconecta/otros-servicios.svg') }}" alt="Otros servicios">
            </a>
        </section>
    </section>

</main>
@include('layouts.partials.site-footer')
<script src="{{ asset('js/cookie_config.js') }}"></script>
<script src="{{ asset('js/services-catalog.js') }}?v={{ filemtime(public_path('js/services-catalog.js')) }}" defer></script>
</body>
</html>
