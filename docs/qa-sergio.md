# QA — Sergio

**Estado:** línea base inicial, a la espera de las observaciones de Sergio.  
**Entorno analizado:** `kconecta_new` local, Laravel 12, MySQL local y
contenedores `kconecta_new-app` / `kconecta_new-mysql` activos.  
**Fecha de ejecución:** 9 de noviembre de 2026.

## Alcance revisado

- Inicio y ubicación: `/`.
- Catálogo y resultados de proveedores: `/result/services`.
- Registro de proveedor: `/quiero-ser-proveedor`.
- Ficha pública: `/proveedor/{id}` y `/result_provider/{id}`.
- Navegación, pie, legales y autenticación.
- Administración de usuarios, tipos de servicio, importación CSV, tickets,
  valoraciones y contratos API.

La web consulta directamente el esquema MySQL local `kconecta_new`. En las
validaciones de proveedor se debe preservar la relación real entre
`service_type.id` y `provider_services`; la API móvil no forma parte del flujo
web.

## Resultado de la línea base automatizada

Comando ejecutado:

```powershell
docker compose exec -T app php artisan test
```

Resultado: **196 pruebas correctas, 9 fallidas y 1.332 aserciones**
(`88,84 s`). No se realizaron cambios de aplicación durante esta revisión.

| ID | Área | Hallazgo reproducible | Severidad inicial | Estado |
| --- | --- | --- | --- | --- |
| QA-001 | Resultados públicos | Al seleccionar un tipo sin proveedores, la página no muestra `Sin resultados`. | Alta | Abierto |
| QA-002 | Resultados públicos | Un `sti[]` vacío no se ignora como espera el contrato de resultados y mapa. | Media | Abierto |
| QA-003 | Mapa de resultados | Falta el contrato esperado de `window.history.replaceState`, clústeres y algoritmo de mapa. | Media | Abierto |
| QA-004 | URL de resultados | La interfaz no muestra el estado de categoría/localización esperado y la limpieza de URL no cumple el contrato probado. | Media | Abierto |
| QA-005 | Alta de proveedor | El formulario no contiene los IDs esperados para los controles legales (`provider-terms-accepted` y `provider-privacy-acknowledged`). | Alta | Abierto |
| QA-006 | Marca | `resources/views/page/index.blade.php` conserva una referencia a la marca legacy `damelodamelo`. | Media | Abierto |
| QA-007 | Pie público | Alguna familia de vistas públicas no renderiza el pie compartido `site-footer`. | Media | Abierto |
| QA-008 | Navegación pública | Alguna familia de vistas públicas no renderiza el contrato de navegación de inicio. | Media | Abierto |
| QA-009 | Accesibilidad móvil | Falta el contenedor/contrato de disclosure esperado para el menú móvil. | Media | Abierto |
| QA-010 | Pie público | Sergio solicita alinear el pie con el nuevo diseño de Kconecta. La referencia coincide sustancialmente con el componente compartido actual; requiere validación visual y de enlaces antes de rediseñarlo. | Baja | Validado: mejora, no defecto visual confirmado |
| QA-011 | Mensajería de producto | Se retiran mensajes de prototipo, demostración, ejemplo y datos locales que contradicen los flujos web funcionales. También se elimina la vista heredada de resultados simulados. | Alta | Resuelto; pendiente de revisión visual |

### QA-010 — observación de Sergio

- **Reportado por:** Sergio (QA).
- **Evidencia:** referencia visual aportada el 9 de noviembre de 2026.
- **Componente:** `resources/views/layouts/partials/site-footer.blade.php` y
  `public/css/components/site-footer.css`.
- **Resultado del contraste:** el componente ya reproduce la composición de la
  referencia: fondo azul oscuro, marca y propuesta de valor, tres columnas de
  navegación, insignias de App Store/Google Play y barra legal inferior.
- **Decisión de QA:** no tratar la captura como una orden de rediseño completo.
  Aplicar solo un refinamiento visual aprobado y comprobar que el pie compartido
  se renderice en todas las familias públicas (véase QA-007).
- **Revisión de dirección de arte:** se descarta el azul eléctrico de la segunda
  propuesta como fondo integral. La paleta vigente usa `--kc-sky: #8bd6fa`,
  `--kc-soft: #e9f5ff`, `--kc-ink: #101927` y `--kc-blue: #1476e8` como acento.
  La recomendación es un pie claro en `#e9f5ff`, texto `#101927`, líneas
  `#bfd9ea` y acentos/enlaces `#1476e8`; no un fondo azul oscuro ni degradado.
- **Referencia posterior aprobada:** se sustituye por un pie de tinta `#101927`
  con logotipo WebP, tres columnas (Servicios, Profesionales e Información) y
  una barra legal compacta. La referencia posterior prevalece sobre la
  recomendación clara anterior.
- **Pendiente de auditoría funcional:** no convertir las insignias de las tiendas
  en enlaces hasta disponer de las URL oficiales verificadas de cada tienda.

### QA-011 — observación de Sergio

- **Reportado por:** Sergio (QA).
- **Solicitud:** eliminar cualquier mensaje que presente la web como prototipo,
  muestra o demostración, incluida la frase de inicio y el aviso de resultados
  sobre la base local.
- **Aplicado:** se actualizaron los beneficios de inicio, se eliminó el aviso de
  resultados y se retiró del catálogo la interfaz heredada con profesionales y
  acciones simuladas. El bloque de beneficios se reemplazó por registro
  gratuito, profesionales visibles, contacto directo, perfiles completos y un
  mensaje de producto. Las rutas de categoría continúan llevando a la consulta
  real de proveedores.
- **Verificación automática:** `HomePageTest`, `ServicesCatalogTest` y
  `ProviderLandingHeroTest`: 12 pruebas correctas y 103 aserciones.
- **Pendiente de auditoría visual:** comprobar en escritorio y móvil el inicio,
  catálogo, resultados y registro de proveedor.

### QA-012 — entrega de correo de verificación

- **Reportado por:** Sergio (QA).
- **Resultado del diagnóstico:** el flujo de alta sí emitía el evento de
  verificación, pero el entorno `kconecta_new` tenía `MAIL_MAILER=log`, sin
  transporte SMTP efectivo; por tanto el mensaje se registraba localmente y no
  se entregaba.
- **Corrección local aplicada:** se sincronizaron exclusivamente las variables
  `MAIL_*` desde el CRM de referencia y se recreó el contenedor para que Docker
  relea el archivo de entorno. No se versionan secretos ni se modificaron la
  base de datos, `APP_KEY` o `APP_URL` propios de `kconecta_new`.
- **Comprobación:** Laravel resuelve el mailer SMTP, host, puerto, remitente y
  credenciales configuradas. La prueba de alta valida además que se emite la
  notificación `VerifyEmail` para el proveedor recién registrado.

### QA-013 — ficha pública de proveedor

- **Reportado por:** Sergio (QA).
- **Observación:** la columna de perfil resultaba demasiado estrecha y algunos
  nombres y ubicaciones mostraban caracteres heredados como `Catalu?a`.
- **Aplicado:** la columna lateral pasa a un rango de 320–360 px en escritorio,
  con tarjetas, bordes y CTA alineados con el azul Kconecta. La normalización se
  extiende a los textos de perfil, dirección, especialidades y servicios, sin
  modificar los registros MySQL.
- **Pendiente de auditoría visual:** validar la ficha en escritorio y móvil con
  proveedores de nombre, dirección y correo extensos.

### QA-014 — identidad visual en acceso y navegación

- **Reportado por:** Sergio (QA).
- **Observación:** el acceso conservaba el panel azul oscuro, acciones turquesa y el pie mostraba el logotipo anterior. La tarjeta de acceso quedaba demasiado próxima al pie.
- **Aplicado:** el logotipo nuevo WebP se usa en cabecera, pie, catálogo, resultados e inscripción. Se normalizan acciones al azul Kconecta `#1476e8` y los acentos suaves al celeste `#8bd6fa`; el acceso deja 48 px antes del pie.
- **Pendiente de auditoría visual:** comprobar proporciones del logotipo nuevo y el espaciado en escritorio y móvil.

> Corrección posterior: se descartó el recurso vertical `KC`, que se identificó como el logotipo anterior. Toda la web utiliza ahora el logotipo horizontal aprobado `public/img/kconecta/kconecta_logo.webp`; los dos recursos heredados se retiraron del proyecto. El CTA de WhatsApp de la ficha también se fija a `#1476e8`, anulando el estilo en línea anterior.

## Riesgos y límites de la revisión

- La batería automática valida contratos y regresiones, pero no sustituye la
  comprobación visual manual en móvil y escritorio.
- Acciones presentadas solo como interfaz, sin comportamiento confirmado,
  deben quedar marcadas como **pendientes de auditoría funcional**.
- Hay tareas visuales pendientes ya registradas para inicio, catálogo,
  resultados y ficha pública. No deben cerrarse sin validación manual.
- Los datos heredados pueden contener caracteres dañados; la corrección visual
  no equivale a reparar los datos de origen.

## Registro de observaciones de Sergio

Cada observación que aporte Sergio se añadirá con el siguiente formato:

| Campo | Contenido |
| --- | --- |
| ID | `QA-XXX` |
| Reportado por | Sergio (QA) |
| Entorno y dispositivo | Pendiente |
| Ruta y precondiciones | Pendiente |
| Pasos para reproducir | Pendiente |
| Resultado actual | Pendiente |
| Resultado esperado | Pendiente |
| Evidencia | Captura, vídeo o referencia pendiente |
| Impacto / severidad | Pendiente de triaje |
| Tipo | Funcional, visual, accesibilidad, datos o regresión |
| Estado | Nuevo, validado, en curso, resuelto o no reproducible |

## Criterio de cierre

Una observación solo se cerrará cuando se documente la corrección, se ejecute la
prueba automática o manual aplicable y se confirme que no altera las consultas
reales de proveedores ni sus relaciones de servicio.
