<a class="home-skip-link" href="#contenido-principal">Saltar al contenido</a>

<header class="kc-header" data-home-header>
    <div class="kc-header__inner">
        <a class="kc-brand" href="{{ route('home') }}" aria-label="Kconecta, inicio">
            <img src="{{ asset('img/kconecta/kconecta_logo.webp') }}" alt="Kconecta">
        </a>
        <button class="kc-menu-button" type="button" aria-expanded="false" aria-controls="kc-navigation" data-home-menu-button>
            <span class="sr-only">Abrir menú</span><span></span><span></span><span></span>
        </button>
        <nav class="kc-navigation" id="kc-navigation" aria-label="Navegación principal" data-home-navigation>
            <a href="{{ route('home') }}#como-funciona">Cómo funciona</a>
            <a href="{{ route('provider.landing') }}">Profesionales</a>
            <a href="{{ route('home') }}#resenas">Reseñas</a>
            <a href="{{ route('home') }}#ayuda">Ayuda</a>
            @auth
                <a class="kc-nav-button kc-nav-button--outline" href="{{ route('dashboard') }}">Mi panel</a>
            @else
                <a class="kc-nav-button kc-nav-button--outline" href="{{ route('login') }}">Entrar</a>
            @endauth
            <a class="kc-nav-button kc-nav-button--primary" href="{{ route('provider.landing') }}">Regístrate gratis</a>
        </nav>
    </div>
</header>
