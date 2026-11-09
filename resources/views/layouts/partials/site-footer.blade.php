<footer class="site-footer" id="site-footer">
    <div class="site-footer__shell site-footer__grid">
        <div class="site-footer__about">
            <a class="site-footer__brand" href="{{ route('home') }}" aria-label="Kconecta, inicio">
                <img src="{{ asset('img/kconecta/kconecta_icon.webp') }}" alt="Kconecta" width="96" height="82">
            </a>
            <p>Encuentra servicios para tu hogar y conecta con profesionales de forma sencilla.</p>
        </div>

        <nav aria-label="Servicios">
            <strong>Servicios</strong>
            <a href="{{ url('/result/services') }}">Buscar servicios</a>
            <a href="{{ url('/result/services') }}">Profesionales</a>
            <a href="{{ route('home') }}#como-funciona">Cómo funciona</a>
        </nav>

        <nav aria-label="Profesionales">
            <strong>Profesionales</strong>
            <a href="{{ route('provider.landing') }}">Soy profesional</a>
            <a href="{{ route('provider.landing') }}">Registro gratuito</a>
        </nav>

        <nav aria-label="Información">
            <strong>Información</strong>
            <a href="mailto:contacto@kconecta.com">Ayuda</a>
            <a href="https://www.damelodamelo.com" target="_blank" rel="noopener noreferrer">Inmobiliaria <span aria-hidden="true">↗</span></a>
        </nav>
    </div>

    <div class="site-footer__shell site-footer__bottom">
        <small>© {{ date('Y') }} Kconecta</small>
        <div>
            <a href="{{ route('legal.terms') }}">Aviso legal</a>
            <a href="{{ route('legal.privacy') }}">Política de privacidad</a>
            <button type="button" data-cookie-manage>Configurar cookies</button>
        </div>
    </div>
</footer>
