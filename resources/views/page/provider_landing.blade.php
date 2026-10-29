<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#82d1f3">
    <title>Brindo servicios | Kconecta</title>
    <meta name="description" content="Inscripción gratuita para profesionales en Kconecta.">
    <link rel="stylesheet" href="{{ asset('css/page/provider-onboarding.css') }}">
</head>
<body>
<main class="provider-page">
    <header class="provider-topbar" aria-label="Navegación de inscripción">
        <a class="provider-back" href="{{ url('/') }}" aria-label="Volver al inicio">‹</a>
        <img src="{{ asset('img/kconecta/kconecta_logo.webp') }}" alt="Kconecta" class="provider-logo">
        <p class="provider-location"><span aria-hidden="true">⌖</span><strong>Barcelona</strong><small>(08028) demo</small></p>
    </header>

    <section class="provider-content" aria-labelledby="provider-title">
        <header class="provider-intro">
            <img src="{{ asset('img/kconecta/shop.webp') }}" alt="" class="provider-intro__image">
            <div>
                <h1 id="provider-title">Brindo servicios</h1>
                <p>Inscríbete gratis y gana visibilidad ante personas que buscan profesionales para su hogar.</p>
            </div>
        </header>

        <div class="provider-columns">
            <div class="provider-primary">
                <section class="provider-panel provider-steps" aria-labelledby="steps-title">
                    <h2 id="steps-title">Cómo funciona la inscripción</h2>
                    <ol>
                        <li><span>1</span><div><strong>Déjanos tus datos básicos</strong><p>Nombre comercial, servicio y correo.</p></div></li>
                        <li><span>2</span><div><strong>Completa tu perfil</strong><p>Más adelante añadirás descripción, servicios y zona.</p></div></li>
                        <li><span>3</span><div><strong>Gana visibilidad</strong><p>Tu perfil aparecerá en las búsquedas de tu categoría.</p></div></li>
                    </ol>
                </section>

                <section class="provider-panel provider-form-panel" aria-labelledby="register-title">
                    <h2 id="register-title">Registro gratuito <span>(demostración)</span></h2>
                    <form data-provider-demo-form>
                        <label><span aria-hidden="true">♙</span><span class="sr-only">Nombre comercial</span><input type="text" placeholder="Nombre comercial" autocomplete="organization"></label>
                        <label><span aria-hidden="true">⚒</span><span class="sr-only">Servicio que ofreces</span><input type="text" placeholder="Servicio que ofreces" autocomplete="off"></label>
                        <label><span aria-hidden="true">✉</span><span class="sr-only">Correo electrónico</span><input type="email" placeholder="Correo electrónico" autocomplete="email"></label>
                        <button type="submit">Inscribirme gratis</button>
                        <p class="provider-form-note" data-provider-demo-status>Formulario demostrativo, no conectado a ningún servicio.</p>
                    </form>
                </section>
            </div>

            <aside class="provider-preview" aria-labelledby="preview-title">
                <p class="provider-preview__pill"><span aria-hidden="true">◉</span> Vista de ejemplo · no forma parte del formulario</p>
                <h2 id="preview-title">Así completarías tu perfil después</h2>
                <p>Estos pasos se definirán en una fase posterior y están pendientes de aprobación.</p>
                <ul>
                    <li><span aria-hidden="true">♙</span><div><strong>Perfil profesional</strong><small>Foto, descripción y especialidades</small></div></li>
                    <li><span aria-hidden="true">⚒</span><div><strong>Servicios</strong><small>Lista de trabajos que realizas</small></div></li>
                    <li><span aria-hidden="true">⌖</span><div><strong>Zonas de cobertura</strong><small>Poblaciones donde prestas servicio</small></div></li>
                </ul>
            </aside>
        </div>
    </section>
</main>
<script src="{{ asset('js/provider-onboarding.js') }}" defer></script>
</body>
</html>
