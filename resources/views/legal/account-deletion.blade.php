@extends('legal.layout', ['title' => 'Eliminación de Cuenta'])

@section('content')
    <p>Responsable: <strong>{{ $legalConfig['responsible_name'] }}</strong>, CIF {{ $legalConfig['responsible_tax_id'] }}.</p>

    <h2>1. Eliminación desde la app</h2>
    <p>Puede iniciar la eliminación de su cuenta desde la app móvil autenticada usando la opción «Eliminar cuenta» del perfil.</p>
    <p>La app utiliza el endpoint <code>DELETE /api/me</code>, que requiere autenticación y confirmación de contraseña.</p>

    <h2>2. Solicitud alternativa por web o correo</h2>
    <p>Si no puede acceder a la app, puede solicitar la baja mediante <strong><a href="mailto:{{ $legalConfig['support_contact_email'] }}">{{ $legalConfig['support_contact_email'] }}</a></strong>. La sociedad podrá solicitar información suficiente para verificar la identidad y evitar eliminaciones fraudulentas.</p>

    <h2>3. Qué datos se eliminan o anonimizan</h2>
    <ul>
        <li>Se eliminan o anonimizan los datos personales directos de la cuenta: nombre, correo, teléfonos, dirección personal y foto.</li>
        <li>Se revocan los tokens y sesiones activos para impedir nuevos accesos con la cuenta eliminada.</li>
        <li>En proveedores se eliminan la dirección pública, especialidades, servicios heredados, portada, galería, vídeo, visitas y clics identificables.</li>
        <li>Se retiran los archivos físicos atribuibles de forma exclusiva a esa cuenta.</li>
    </ul>

    <h2>4. Datos retenidos y motivo</h2>
    <p>{{ $legalConfig['retention_note'] }}</p>
    <ul>
        <li>Las valoraciones pueden conservarse vinculadas únicamente a una identidad interna despersonalizada para mantener la integridad estadística del servicio.</li>
        <li>Los códigos de trabajo se inutilizan y se conservan durante un máximo de cinco años como evidencia de la relación.</li>
        <li>Los tickets y mensajes se cierran y despersonalizan, y se eliminan a los dos años.</li>
        <li>Las auditorías de eliminación se conservan bloqueadas durante tres años.</li>
        <li>Las aceptaciones legales quedan bloqueadas durante cinco años y después se eliminan.</li>
    </ul>

    <h2>5. Plazos de eliminación</h2>
    @if ((int) $legalConfig['account_deletion_sla_days'] <= 0)
        <p>La retirada pública, la desactivación de la cuenta y la revocación de acceso se aplican de forma inmediata tras confirmar correctamente la solicitud en la app.</p>
    @else
        <p>La solicitud se procesa en un máximo de {{ (int) $legalConfig['account_deletion_sla_days'] }} días.</p>
    @endif
    <p>Los datos sujetos a los plazos indicados permanecen bloqueados o despersonalizados y fuera del uso ordinario hasta su supresión. Las copias de seguridad rotan conforme a los plazos descritos en la <a href="{{ route('legal.privacy') }}">Política de privacidad</a>.</p>
@endsection
