<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#82d1f3">
    <title>Brindo servicios | Kconecta</title>
    <meta name="description" content="Inscripción gratuita para profesionales en Kconecta.">
    <link rel="stylesheet" href="{{ asset('css/page/provider-onboarding.css') }}">
    <link rel="stylesheet" href="{{ asset('css/page/provider-registration.css') }}?v={{ filemtime(public_path('css/page/provider-registration.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/components/legal-acceptance.css') }}?v={{ filemtime(public_path('css/components/legal-acceptance.css')) }}">
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
                    <h2 id="register-title">Registro gratuito</h2>
                    @if ($errors->any())
                        <div class="provider-registration-errors" role="alert"><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
                    @endif
                    <form method="POST" action="{{ route('provider.register') }}" class="provider-registration-form">
                        @csrf
                        <input type="hidden" name="registration_form_started_at" value="{{ $registrationFormStartedAt }}">
                        <input type="hidden" name="document_type" value="">
                        <input type="hidden" name="document_number" value="">
                        <div class="provider-registration-honeypot" aria-hidden="true"><label for="provider-website">Sitio web</label><input id="provider-website" name="website" type="text" tabindex="-1" autocomplete="off"></div>
                        <label class="provider-field"><span aria-hidden="true">♙</span><span class="sr-only">Nombre comercial</span><input name="company_name" type="text" value="{{ old('company_name') }}" placeholder="Nombre comercial" autocomplete="organization" required></label>
                        <label class="provider-field"><span aria-hidden="true">⚒</span><span class="sr-only">Servicio que ofreces</span><select name="service_type_id" required><option value="">Servicio que ofreces</option>@foreach ($serviceTypes as $serviceType)<option value="{{ $serviceType['id'] }}" @selected((string) old('service_type_id') === (string) $serviceType['id'])>{{ $serviceType['name'] }}</option>@endforeach</select></label>
                        <label class="provider-field"><span aria-hidden="true">✉</span><span class="sr-only">Correo electrónico</span><input name="email" type="email" value="{{ old('email') }}" placeholder="Correo electrónico" autocomplete="email" required></label>
                        <label class="provider-field"><span aria-hidden="true">●</span><span class="sr-only">Contraseña</span><input name="password" type="password" placeholder="Contraseña (mínimo 6 caracteres)" autocomplete="new-password" required></label>
                        <label class="provider-field"><span aria-hidden="true">●</span><span class="sr-only">Repite la contraseña</span><input name="password_confirmation" type="password" placeholder="Repite la contraseña" autocomplete="new-password" required></label>
                        @include('auth.partials.legal-acceptance', ['legalCheckboxPrefix' => 'provider-landing'])
                        <button type="submit">Inscribirme gratis</button>
                        <p class="provider-form-note">Te enviaremos un correo para verificar tu cuenta.</p>
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
</body>
</html>
