@extends('layouts.home')

@section('head')
    <meta property="og:type" content="website">
    <meta property="og:title" content="Kconecta | Encuentra el servicio que necesitas">
    <meta property="og:description" content="Encuentra y contacta profesionales de confianza para tu hogar.">
@endsection

@section('content')
<main id="contenido-principal" class="kc-page">
    <section class="kc-home" aria-labelledby="home-title">
        <div class="kc-home__shell">
            <h1 id="home-title">Encuentra el servicio que<br>necesitas</h1>
            <div class="kc-action-grid">
                <form class="kc-tile kc-tile--location" action="{{ url('/result/services') }}" method="get" data-home-search data-home-catalog-url="{{ url('/result/services') }}">
                    <input type="hidden" name="mode" value="2"><input type="hidden" name="latitude" value="" data-home-latitude><input type="hidden" name="longitude" value="" data-home-longitude><input type="hidden" name="city" value="" data-home-city><input type="hidden" name="province" value="" data-home-province><input type="hidden" name="zoom" value="13" data-home-zoom>
                    <img src="{{ asset('img/kconecta/map.webp') }}" alt="" class="kc-tile__illustration">
                    <span class="kc-tile__content"><strong>Usar mi ubicación</strong><small data-home-location-status>Busca profesionales cerca de ti</small></span>
                    <button class="kc-round-arrow" type="button" aria-label="Usar mi ubicación" data-home-use-location>›</button>
                </form>
                <form class="kc-tile kc-tile--address" action="{{ url('/result/services') }}" method="get" data-home-address data-home-catalog-url="{{ url('/result/services') }}">
                    <input type="hidden" name="mode" value="2"><img src="{{ asset('img/kconecta/house.webp') }}" alt="" class="kc-tile__illustration">
                    <div class="kc-address-form"><strong>Escribir dirección en este lugar</strong><label><span aria-hidden="true">⌖</span><span class="sr-only">Población</span><input name="city" required placeholder="Población" autocomplete="address-level2"></label><label><span aria-hidden="true">⌂</span><span class="sr-only">Dirección</span><input name="address" placeholder="Dirección" autocomplete="street-address"></label></div>
                    <button class="kc-round-arrow" type="submit" aria-label="Buscar con esta dirección">›</button>
                </form>
                <a class="kc-tile kc-tile--services" href="{{ url('/result/services') }}" data-home-clear-location><img src="{{ asset('img/kconecta/house-tools.webp') }}" alt="" class="kc-tile__illustration"><span class="kc-tile__content"><strong>Servicios para el hogar</strong><small>Lampista, reformas y más servicios</small></span><span class="kc-round-arrow" aria-hidden="true">›</span></a>
                <a class="kc-tile kc-tile--provider" href="{{ route('provider.landing') }}"><img src="{{ asset('img/kconecta/shop.webp') }}" alt="" class="kc-tile__illustration"><span class="kc-tile__content"><strong>Ofrezco servicios</strong><small>Eres un profesional o manitas<br>Inscríbete gratuitamente y gana visibilidad</small></span><span class="kc-round-arrow" aria-hidden="true">›</span></a>
                <a class="kc-tile kc-tile--property" href="https://www.damelodamelo.com" target="_blank" rel="noopener noreferrer"><img src="{{ asset('img/kconecta/house-sale.webp') }}" alt="" class="kc-tile__illustration"><span class="kc-tile__content"><strong>Inmobiliaria</strong><small>Si eres profesional o particular anuncia tus inmuebles o busca lo que necesitas</small><em>Gratuitamente</em></span><span class="kc-round-arrow" aria-hidden="true">›</span></a>
            </div>
            <section class="kc-benefits" id="como-funciona" aria-label="Características de Kconecta">
                <article><span aria-hidden="true">♙</span><div><strong>Registro gratuito</strong><small>Formulario de demostración</small></div></article><article><span aria-hidden="true">✿</span><div><strong>Perfiles de ejemplo</strong><small>Datos ilustrativos, no verificados</small></div></article><article><span aria-hidden="true">▣</span><div><strong>Tickets de servicio</strong><small>Funcionalidad pendiente</small></div></article><article id="resenas"><span aria-hidden="true">☆</span><div><strong>Valoraciones de ejemplo</strong><small>No son reseñas reales</small></div></article><p>Prototipo ilustrativo: no se crean cuentas, no se envían mensajes y no se usa geolocalización real.</p>
            </section>
        </div>
    </section>
    <section class="kc-catalog" id="ayuda" aria-labelledby="catalog-title"><div class="kc-home__shell"><p class="kc-eyebrow">Servicios para tu hogar</p><h2 id="catalog-title">Elige una categoría</h2><div class="kc-catalog__grid">@forelse ($serviceTypes as $serviceType)<a href="{{ url('/result/services') }}?mode=2&amp;sti%5B%5D={{ $serviceType['id'] }}">{{ $serviceType['name'] }} <span aria-hidden="true">→</span></a>@empty<p>El catálogo estará disponible cuando se conecte la base de datos local.</p>@endforelse</div></div></section>
</main>
@endsection
