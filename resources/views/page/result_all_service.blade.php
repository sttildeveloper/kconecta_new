@php
    $fixLegacyText = static function ($value): string {
        return strtr((string) $value, [
            'Alba?iler?a' => 'Albañilería',
            'Arquitect?nico' => 'Arquitectónico',
            'Asesor?a' => 'Asesoría',
            'Catalu?a' => 'Cataluña',
            'Cerrajer?a' => 'Cerrajería',
            'Climatizaci?n' => 'Climatización',
            'Construcci?n' => 'Construcción',
            'C?rdoba' => 'Córdoba',
            'Espa?a' => 'España',
            'Fontaner?a' => 'Fontanería',
            'Instalaci?n' => 'Instalación',
            'Jardiner?a' => 'Jardinería',
            'M?laga' => 'Málaga',
            'Reparaci?n' => 'Reparación',
            'Revisi?n' => 'Revisión',
            'Arag?n' => 'Aragón',
            'Le?n' => 'León',
        ]);
    };
    $selectedTypes = collect($service_type)->filter(fn ($type) => in_array((int) ($type['id'] ?? 0), $sti ?? [], true));
    $resultTitle = $fixLegacyText($selectedTypes->pluck('name')->filter()->implode(', ') ?: ($showOtherServices ? 'Otros servicios' : 'Servicios'));
    $resultLabel = mb_strtoupper($resultTitle);
    $paginationQuery = request()->query();
    $portraitAssets = [
        'img/kconecta/demo-lampista-1.webp',
        'img/kconecta/demo-lampista-2.webp',
        'img/kconecta/demo-lampista-3.webp',
        'img/kconecta/demo-lampista-4.webp',
    ];
@endphp
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#82d1f3">
    <title>Resultados: {{ $resultTitle }} | Kconecta</title>
    @include('layouts.partials.favicon')
    <link rel="stylesheet" href="{{ asset('css/page/results-ui.css') }}?v={{ filemtime(public_path('css/page/results-ui.css')) }}">
</head>
<body>
<main class="results-page">
    <header class="results-topbar" aria-label="Navegación de resultados">
        <a class="results-back" href="{{ url('/result/services') }}" aria-label="Volver a los servicios">‹</a>
        <img src="{{ asset('img/kconecta/kconecta_logo.webp') }}" alt="Kconecta" class="results-logo">
        <p class="results-location"><span aria-hidden="true">⌖</span><strong>{{ $city ?: 'Barcelona' }}</strong><small>{{ $city ? 'ubicación indicada' : '(08028) demo' }}</small></p>
    </header>

    <section class="results-content" aria-labelledby="results-title">
        <h1 id="results-title">Resultados: {{ $resultTitle }}</h1>
        <p class="results-subtitle">Profesionales cerca de ti</p>

        <div class="results-layout">
            <div class="results-list">
                <form class="results-search" data-results-search method="get" action="{{ url('/result/services') }}">
                    @foreach ($paginationQuery as $key => $value)
                        @if ($key !== 'q' && $key !== 'page' && $key !== 'sort' && $key !== 'min_rating')
                            @if (is_array($value))
                                @foreach ($value as $item)<input type="hidden" name="{{ $key }}[]" value="{{ $item }}">@endforeach
                            @else
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endif
                        @endif
                    @endforeach
                    <input type="hidden" name="sort" value="{{ $sort }}" data-results-sort-input>
                    <input type="hidden" name="min_rating" value="{{ $minRating }}" data-results-min-rating-input>
                    <label class="sr-only" for="professional-query">Buscar por nombre, apellidos o razón social</label>
                    <span aria-hidden="true">⌕</span><input id="professional-query" name="q" type="search" value="{{ $searchTerm }}" placeholder="Buscar por nombre o empresa" autocomplete="off"><button type="submit" aria-label="Buscar profesionales">⌕</button>
                </form>
                <button class="results-filter-trigger" type="button" aria-label="Abrir filtros" aria-expanded="false" aria-controls="results-filter-sheet" data-results-filter-open>
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7h10M18 7h2M4 17h3M11 17h9M14 4v6M7 14v6"/></svg>
                </button>
                <h2><span data-results-count>{{ count($properties) }}</span> perfiles mostrados <small>de {{ $quantity }} encontrados</small></h2>
                <p class="results-disclaimer"><span aria-hidden="true">ⓘ</span> Los perfiles proceden de la base local. La distancia y la valoración se calculan con sus datos registrados.</p>

                <div class="professional-cards">
                    @forelse ($properties as $index => $property)
                        @php
                            $user = data_get($property, 'user.0', []);
                            $rawProfileName = data_get($user, 'user_name')
                                ?: data_get($user, 'company_name')
                                ?: trim(data_get($user, 'first_name', '').' '.data_get($user, 'last_name', ''))
                                ?: 'Profesional local';
                            $profileName = $fixLegacyText(trim((string) $rawProfileName));
                            $addressData = data_get($property, 'user_address.0', []);
                            $location = $fixLegacyText(collect([data_get($addressData, 'city'), data_get($addressData, 'province')])->filter()->unique()->implode(', ') ?: 'Ubicación pendiente');
                            $specialties = collect(data_get($property, 'specialties', []))->pluck('name')->filter()->map($fixLegacyText)->take(2);
                            $registeredCover = \App\Models\CoverImage::query()
                                ->where('provider_user_id', (int) data_get($property, 'id'))
                                ->orderByDesc('is_provider_default')
                                ->orderByDesc('id')
                                ->value('url');
                            $registeredCoverPath = 'img/uploads/'.ltrim((string) $registeredCover, '/');
                            $portrait = $registeredCover && str_ends_with(strtolower((string) $registeredCover), '.webp') && is_file(public_path($registeredCoverPath))
                                ? asset($registeredCoverPath)
                                : asset($portraitAssets[$index % count($portraitAssets)]);
                            $providerPhone = data_get($user, 'provider_phone')
                                ?: data_get($user, 'provider_landline_phone')
                                ?: data_get($user, 'phone')
                                ?: data_get($user, 'landline_phone');
                            $whatsappPhone = preg_replace('/\D+/', '', (string) $providerPhone);
                            $whatsappLink = $whatsappPhone !== ''
                                ? 'https://wa.me/'.$whatsappPhone.'?text='.urlencode('Hola, me interesa tu servicio')
                                : null;
                        @endphp
                        <article class="professional-card" data-professional-card data-professional-name="{{ $profileName }}" data-profile-url="{{ url('/result_provider/'.data_get($property, 'id')) }}" tabindex="0" role="link" aria-label="Abrir perfil de {{ $profileName }}">
                            <img src="{{ $portrait }}" alt="Retrato ilustrativo para {{ $profileName }}">
                            <div class="professional-card__body">
                                <small>{{ $resultLabel }}</small>
                                <h3>{{ $profileName }}</h3>
                                <p class="professional-meta"><b>★ Perfil local</b><span>⌖ {{ $location }}</span></p>
                                @if ($specialties->isNotEmpty())
                                    <div class="professional-tags">@foreach ($specialties as $specialty)<span>{{ $specialty }}</span>@endforeach</div>
                                @endif
                                <div class="professional-actions">
                                    @if ($whatsappLink)
                                        <a class="professional-contact" href="{{ $whatsappLink }}" target="_blank" rel="noopener" style="border-color:#1474e8;color:#fff;background:#1474e8;">Contactar</a>
                                    @else
                                        <span class="professional-contact is-disabled" aria-disabled="true" style="display:grid;min-height:45px;padding:0 20px;place-items:center;border:2px solid #9aa8b5;border-radius:23px;color:#68788a;background:#eef2f5;font-size:14px;font-weight:800;">WhatsApp no disponible</span>
                                    @endif
                                </div>
                            </div>
                            <button class="professional-favorite" type="button" aria-label="Añadir {{ $profileName }} a favoritos" data-favorite>♡</button>
                        </article>
                    @empty
                        <p class="results-empty">No hay proveedores que coincidan con esta categoría y ubicación.</p>
                    @endforelse
                </div>
                @if ($quantity_block_nav > 1)
                    <nav class="results-pagination" aria-label="Páginas de resultados">
                        @if ($number_position > 1)<a href="{{ url('/result/services') }}?{{ http_build_query(array_merge($paginationQuery, ['page' => $number_position - 1])) }}">Anterior</a>@endif
                        <span>Página {{ $number_position }} de {{ $quantity_block_nav }}</span>
                        @if ($number_position < $quantity_block_nav)<a href="{{ url('/result/services') }}?{{ http_build_query(array_merge($paginationQuery, ['page' => $number_position + 1])) }}">Siguiente</a>@endif
                    </nav>
                @endif
                <p class="sr-only" aria-live="polite" data-results-status></p>
            </div>
        </div>
    </section>
</main>
<div class="results-filter-modal" id="results-filter-sheet" data-results-filter-modal aria-hidden="true">
    <button class="results-filter-backdrop" type="button" tabindex="-1" aria-label="Cerrar filtros" data-results-filter-close></button>
    <section class="results-filter-sheet" role="dialog" aria-modal="true" aria-labelledby="results-filter-title">
        <div class="results-filter-handle" aria-hidden="true"></div>
        <div class="results-filter-heading"><div><h2 id="results-filter-title">Filtros</h2><p>Ordena los profesionales que quieres ver.</p></div><button type="button" aria-label="Cerrar filtros" data-results-filter-close>×</button></div>
        <fieldset><legend>Ordenar por</legend><div class="filter-options"><button type="button" class="{{ $sort === 'distance' ? 'is-active' : '' }}" aria-pressed="{{ $sort === 'distance' ? 'true' : 'false' }}" data-results-sort="distance">Distancia</button><button type="button" class="{{ $sort === 'rating' ? 'is-active' : '' }}" aria-pressed="{{ $sort === 'rating' ? 'true' : 'false' }}" data-results-sort="rating">Valoración</button></div></fieldset>
        <fieldset><legend>Valoración mínima</legend><div class="filter-options"><button type="button" class="{{ $minRating === 0.0 ? 'is-active' : '' }}" aria-pressed="{{ $minRating === 0.0 ? 'true' : 'false' }}" data-results-min-rating="0">Todas</button><button type="button" class="{{ $minRating === 4.5 ? 'is-active' : '' }}" aria-pressed="{{ $minRating === 4.5 ? 'true' : 'false' }}" data-results-min-rating="4.5">4,5+ ★</button><button type="button" class="{{ $minRating === 4.8 ? 'is-active' : '' }}" aria-pressed="{{ $minRating === 4.8 ? 'true' : 'false' }}" data-results-min-rating="4.8">4,8+ ★</button></div></fieldset>
        <footer class="results-filter-actions"><button type="button" data-results-filter-reset>Restablecer</button><button type="button" data-results-filter-apply>Ver resultados</button></footer>
    </section>
</div>
<script src="{{ asset('js/results-ui.js') }}?v={{ filemtime(public_path('js/results-ui.js')) }}" defer></script>
</body>
</html>
