@extends('legal.layout', ['title' => 'Términos y Condiciones'])

@section('content')
    <h2>1. Titular de la plataforma</h2>
    <p>{{ $legalConfig['brand_name'] }} es una plataforma operada por <strong>{{ $legalConfig['responsible_name'] }}</strong>, CIF {{ $legalConfig['responsible_tax_id'] }}, con domicilio en {{ $legalConfig['responsible_address'] }}.</p>
    <p>Contacto: <a href="mailto:{{ $legalConfig['support_contact_email'] }}">{{ $legalConfig['support_contact_email'] }}</a> · {{ $legalConfig['contact_phone'] }}.</p>

    <h2>2. Uso permitido</h2>
    <p>El usuario se compromete a usar {{ $legalConfig['brand_name'] }} conforme a la ley, la buena fe y estos términos, evitando usos fraudulentos o lesivos.</p>

    <h2>3. Cuenta y seguridad</h2>
    <p>El registro y uso de la plataforma está reservado a personas de al menos {{ (int) $legalConfig['minimum_age'] }} años.</p>
    <p>El usuario debe proporcionar información veraz y actualizada, custodiar sus credenciales y responder de la actividad realizada desde su cuenta.</p>

    <h2>4. Publicaciones, denuncias y moderación</h2>
    <p>El usuario garantiza que puede publicar la información, imágenes, vídeos y demás contenido que aporte y que dicho contenido no vulnera derechos de terceros.</p>
    <p>No se admite suplantación, fraude, acoso, discriminación, contenido sexual ilícito, servicios ilegales, spam, exposición indebida de datos personales ni infracciones de propiedad intelectual.</p>
    <p>La sociedad revisa las denuncias mediante su equipo de moderación y puede retirar contenido o limitar cuentas cuando resulte necesario. Denunciar y bloquear son acciones distintas. Las consultas sobre una decisión de moderación pueden remitirse a <a href="mailto:{{ $legalConfig['support_contact_email'] }}">{{ $legalConfig['support_contact_email'] }}</a>.</p>

    <h2>5. Función y responsabilidades de la plataforma</h2>
    <p>{{ $legalConfig['brand_name'] }} facilita la publicación y localización de fichas profesionales y el contacto entre usuarios. No presta los servicios anunciados ni garantiza resultados comerciales concretos entre usuarios.</p>
    <p>Nada de lo dispuesto en estos términos limita los derechos imperativos de consumidores y usuarios ni las responsabilidades que legalmente no puedan excluirse.</p>

    <h2>6. Propiedad intelectual</h2>
    <p>El software, la marca y los contenidos propios de la plataforma están protegidos por la normativa aplicable. Queda prohibida su explotación no autorizada.</p>
    <p>El usuario conserva los derechos sobre el contenido que aporta y autoriza su reproducción y comunicación pública únicamente en la medida necesaria para mostrarlo y operar {{ $legalConfig['brand_name'] }} mientras permanezca publicado.</p>

    <h2>7. Suspensión, cancelación y eliminación</h2>
    <p>La sociedad puede suspender o cancelar cuentas ante incumplimientos, riesgos de seguridad o requerimientos legales, aplicando una medida proporcionada a las circunstancias.</p>
    <p>El usuario puede eliminar su cuenta desde su perfil o solicitar ayuda mediante el canal indicado en la página de <a href="{{ route('legal.account-deletion') }}">eliminación de cuenta</a>.</p>

    <h2>8. Modificaciones</h2>
    <p>Estos términos pueden actualizarse para reflejar cambios legales o funcionales. La versión y fecha de actualización vigentes se publican en los canales legales de la plataforma y, cuando proceda, se solicitará una nueva aceptación.</p>

    <h2>9. Jurisdicción y ley aplicable</h2>
    <p>La relación se regirá por la legislación de {{ $legalConfig['jurisdiction'] }}, sin perjuicio de los derechos imperativos y del fuero que correspondan a consumidores y usuarios.</p>
@endsection
