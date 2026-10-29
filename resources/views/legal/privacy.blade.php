@extends('legal.layout', ['title' => 'Politica de Privacidad'])

@section('content')
    <h2>1. Responsable del tratamiento</h2>
    <p><strong>{{ $legalConfig['responsible_name'] }}</strong>, titular de la plataforma {{ $legalConfig['brand_name'] }}, es la sociedad responsable del tratamiento.</p>
    <ul>
        <li>CIF: {{ $legalConfig['responsible_tax_id'] }}</li>
        <li>Domicilio: {{ $legalConfig['responsible_address'] }}</li>
        <li>Correo de privacidad: <a href="mailto:{{ $legalConfig['privacy_contact_email'] }}">{{ $legalConfig['privacy_contact_email'] }}</a></li>
        <li>Teléfono de contacto: <a href="tel:{{ preg_replace('/\s+/', '', $legalConfig['contact_phone']) }}">{{ $legalConfig['contact_phone'] }}</a></li>
    </ul>

    <h2>2. Datos recopilados</h2>
    <p>La plataforma no está dirigida a menores de {{ (int) $legalConfig['minimum_age'] }} años y no admite su registro.</p>
    <ul>
        <li>Datos de cuenta: nombre, correo electronico, telefono, tipo de usuario.</li>
        <li>Datos de uso: eventos funcionales necesarios para operar la plataforma (por ejemplo, metricas de visitas y clicks).</li>
        <li>Datos tecnicos basicos para seguridad y operacion (logs, direccion IP, agente de usuario).</li>
        <li>Perfil profesional, ubicación pública, especialidades, imágenes y vídeo aportados por proveedores.</li>
        <li>Contenido generado por usuarios: valoraciones, mensajes, tickets, denuncias y bloqueos.</li>
        <li>Preferencias de cookies y consentimiento publicitario.</li>
    </ul>

    <h2>3. Finalidades del tratamiento</h2>
    <ul>
        <li>Autenticacion y gestion de cuenta.</li>
        <li>Prestacion de servicios del CRM y app movil.</li>
        <li>Seguridad, prevencion de fraude y soporte tecnico.</li>
        <li>Cumplimiento de obligaciones legales aplicables.</li>
        <li>Publicación de fichas profesionales, contacto entre usuarios y moderación de contenido.</li>
    </ul>

    <h2>4. Base legal</h2>
    <ul>
        <li>Ejecución de la relación contractual: alta, autenticación, gestión de cuenta, publicación de la ficha profesional y prestación de las funciones solicitadas.</li>
        <li>Interés legítimo: seguridad, prevención del fraude, métricas operativas, soporte y moderación de denuncias.</li>
        <li>Consentimiento: categorías opcionales de cookies, publicidad y cualquier tratamiento presentado expresamente como opcional.</li>
        <li>Cumplimiento de obligaciones legales y atención de requerimientos de autoridades competentes.</li>
    </ul>

    <h2>5. Destinatarios, servicios externos e infraestructura</h2>
    <p>Los datos pueden ser tratados por Hostinger como proveedor de alojamiento y por Arsys/IONOS como proveedor del correo transaccional, estrictamente para operar la plataforma.</p>
    @if($legalConfig['infrastructure_location'])<p>Ubicación de infraestructura: {{ $legalConfig['infrastructure_location'] }}.</p>@endif
    <p>Las funciones de mapa y búsqueda de lugares pueden comunicar consultas y datos técnicos a Google Maps/Places. Los enlaces de WhatsApp abren un servicio externo y el usuario decide qué información remite. Google AdSense solo puede cargarse cuando el usuario acepta expresamente la categoría de publicidad.</p>

    <h2>6. Conservacion de datos y almacenamiento</h2>
    <p>{{ $legalConfig['retention_note'] }}</p>
    <ul>
        <li>Logs técnicos y de seguridad: 90 días.</li>
        <li>Direcciones IP almacenadas en registros funcionales: 30 días.</li>
        <li>Agentes de usuario almacenados como evidencia técnica: 90 días.</li>
        <li>Métricas identificables de visitas y contactos: 395 días. Los datos agregados verdaderamente anonimizados pueden conservarse con fines estadísticos.</li>
        <li>Tickets y mensajes cerrados: 730 días desde su última actualización.</li>
        <li>Auditorías de eliminación de cuenta: 1.095 días.</li>
        <li>Aceptaciones legales asociadas a cuentas eliminadas: 1.825 días desde la eliminación.</li>
        <li>Códigos de trabajo bloqueados por eliminación de cuenta: 1.825 días.</li>
        <li>Denuncias rechazadas: 365 días; denuncias resueltas con medidas: 1.095 días.</li>
    </ul>
    <p>Backups automáticos de Hostinger: máximo 14 días; backups manuales rutinarios: 30 días; backups excepcionales de migración: 90 días.</p>

    <h2>7. Derechos del usuario</h2>
    <p>Puede solicitar gratuitamente el acceso, rectificación, supresión, oposición, limitación y portabilidad de sus datos mediante el correo de privacidad. Cuando el tratamiento dependa del consentimiento, puede retirarlo sin afectar a la licitud del tratamiento anterior.</p>
    <p>Las solicitudes se responderán en el plazo legal aplicable. Si considera que el tratamiento vulnera la normativa, puede presentar una reclamación ante la Agencia Española de Protección de Datos en <a href="https://www.aepd.es" target="_blank" rel="noopener noreferrer">www.aepd.es</a>.</p>
    <p>La eliminación de cuenta retira el contenido público, medios, especialidades, dirección, tokens y sesiones. Las denuncias pasan por estados pending, reviewing, resolved o rejected y los usuarios autenticados pueden bloquear o desbloquear a otros.</p>

    <h2>8. Contacto</h2>
    <p>Correo de privacidad: <strong><a href="mailto:{{ $legalConfig['privacy_contact_email'] }}">{{ $legalConfig['privacy_contact_email'] }}</a></strong></p>
    <p>Teléfono: <strong>{{ $legalConfig['contact_phone'] }}</strong></p>
@endsection
