# Contexto de trabajo — Kconecta New

Actualizado: 30 de octubre de 2026.

## Misión de esta fase

Mejorar el funcionamiento del CRM de Kconecta utilizando la nueva interfaz
gráfica/visual como capa de producto. El objetivo no es crear una maqueta aislada:
las vistas deben evolucionar sobre las consultas, perfiles y categorías reales
disponibles en la base local.

## Fuente de verdad

- Código de referencia: `C:\MeegDev\kconecta-crm\web`
- Proyecto de trabajo: `C:\MeegDev\kconecta_new\web`
- Datos locales: MySQL `kconecta_new`
- Datos de referencia clonados desde: `kconecta_schema`
- La web Laravel consulta MySQL directamente. La API es exclusiva de la app móvil.

## Despliegue de demostración

- URL: `https://nuevo.kconecta.com`.
- Plataforma: Dokploy en el VPS de Hostinger, con certificado Let's Encrypt.
- Aplicación: servicio `web` construido con `Dockerfile` y puerto interno 80.
- Datos: MySQL 8 en el servicio `mysql`, esquema `kconecta_new`, accesible sólo
  desde la red interna de Dokploy.
- Medios persistentes: volúmenes independientes para `img/uploads`,
  `video/uploads` e `img/photo_profile`.
- Operación y recuperación: consultar
  [`docs/deployment-dokploy.md`](./docs/deployment-dokploy.md). Nunca documentar
  contraseñas, claves ni contenido de copias de seguridad en este archivo.

## Estado funcional relevante

- `service_type` identifica la categoría.
- `provider_services` vincula los proveedores con las categorías.
- La búsqueda de resultados recibe `sti[]` y, opcionalmente, ciudad, provincia,
  dirección o coordenadas.
- Reformas integrales usa actualmente `service_type.id = 23` y cuenta con 179
  proveedores locales.
- La paginación existente carga 15 proveedores por página; una búsqueda hecha solo
  en JavaScript se limita a esos perfiles cargados. La búsqueda global debe pasar
  por una futura auditoría funcional.

## Decisiones visuales vigentes

- Fondo y lenguaje visual azul Kconecta, tarjetas redondeadas y controles de alto
  contraste.
- Todo recurso raster que se añada a la web debe estar en WebP.
- Las fichas de resultados usan la portada registrada de cada proveedor cuando
  existe en `cover_image` y está disponible como WebP.
- Los filtros de distancia/valoración están presentados como interfaz visual; su comportamiento real debe definirse y auditarse antes de activarlo. La acción Contactar abre el WhatsApp del proveedor cuando tiene un teléfono válido.

## Flujo de ubicación

1. Inicio: el usuario utiliza geolocalización o escribe población/dirección.
2. El navegador conserva la información temporalmente con `sessionStorage`.
3. Se abre el catálogo de servicios.
4. Al seleccionar una categoría se combina ubicación + `service_type.id` en la
   consulta real de resultados.

## Precauciones

- No reemplazar ni destruir la base de referencia `kconecta_schema`.
- No tratar la interfaz de demostración de “Brindo servicios” como registro real.
- Los caracteres especiales dañados ya están así en una parte de los datos
  clonados; la corrección visual no equivale a reparar el origen de datos.
- Evitar cambios de backend para ajustes puramente visuales. Cualquier cambio
  funcional de backend debe documentarse aquí antes de realizarse.

## Registro de próximas decisiones

Agregar aquí, para cada nueva fase:

- objetivo de producto;
- referencia visual o ruta afectada;
- datos reales que intervienen;
- comportamiento que se habilita o se deja pendiente;
- pruebas realizadas y resultado.

## Funcionalidad autorizada — acceso a otros servicios (31 de octubre de 2026)

- Ruta: `/result/services`.
- Datos reales: `service_type.id` y `provider_services`.
- Comportamiento: el catálogo muestra, bajo las doce categorías destacadas del hogar, el acceso “Otros servicios”. Este conserva la ubicación temporal elegida y muestra proveedores vinculados a al menos una categoría que no pertenece a esas doce; por ejemplo, Abogacía. No incluye perfiles sin servicios vinculados.
- Sin cambios de esquema ni datos. Pendiente: revisión visual manual en escritorio y móvil.

## Auditoría funcional — búsqueda de proveedores (26 de octubre de 2026)

- Objetivo: hacer funcional la búsqueda de la interfaz de resultados sin cambiar el esquema ni los datos.
- Ruta: `/result/services`.
- Datos reales: `user.user_name`, `user.first_name`, `user.last_name`, `provider_services`, `service_type` y `user_address`.
- Comportamiento habilitado: el parámetro `q` filtra en la consulta antes de paginar por razón social, nombre, apellidos y nombre completo; conserva especialidad y ubicación. Mientras se escribe, la interfaz actualiza los resultados desde la consulta real con una espera de 300 ms. La paginación conserva los parámetros de búsqueda y localización.
- Pendiente: ordenar por distancia/valoración, filtrado por valoración, contacto y favoritos requieren fases funcionales independientes.

## Auditoría visual pendiente — iconos del catálogo (29 de octubre de 2026)

- Ruta: `/result/services`.
- Cambio visual: se sustituyen los doce iconos de categorías por los recursos aprobados de `Kconecta_17_iconos_FINAL_web`, convertidos a WebP con transparencia preservada en `public/img/kconecta/service-types/`.
- Comportamiento: no modifica la selección de `service_type.id` ni las consultas de proveedores; queda pendiente de revisión visual manual en los formatos de escritorio y móvil.

## Funcionalidad autorizada — contacto por WhatsApp (29 de octubre de 2026)

- Ruta: `/result/services`.
- Datos reales: `user.provider_phone`, con `provider_landline_phone`, `phone` y `landline_phone` como respaldo de compatibilidad.
- Comportamiento: cada tarjeta con teléfono válido muestra “Contactar” como enlace directo a WhatsApp con un mensaje inicial; un perfil sin número no presenta un enlace funcional inexistente.
- Sin cambios de esquema ni datos.

## Funcionalidad autorizada — orden y filtro de valoración (29 de octubre de 2026)

- Ruta: `/result/services`.
- Datos reales: coordenadas de `user_address` y agregados de `service_provider_ratings`.
- Comportamiento: “Distancia” ordena por proximidad cuando la búsqueda contiene coordenadas; “Valoración” ordena por media de estrellas y, a igualdad, por número de valoraciones. Los controles 4,5+ y 4,8+ excluyen proveedores que no alcanzan ese promedio. Todos los controles conservan categoría, ubicación y búsqueda por nombre.
- Sin cambios de esquema ni datos.
- Pruebas realizadas: prueba de integración para distancia, orden por valoración y ambos umbrales; comprobación HTTP local de la consulta de REFORMAS BUELE con los parámetros activos.

## Corrección visual — nombres heredados de tipos de servicio (29 de octubre de 2026)

- Ruta: `/` y catálogo de servicios.
- Alcance: normalización en presentación de los signos `?` heredados en `service_type.name`.
- Sin cambios de esquema ni datos: los identificadores y relaciones con `provider_services` permanecen intactos.

## Auditoría visual pendiente — navegación en perfil público (30 de octubre de 2026)

- Ruta: `/result_provider/{id}`.
- Cambio visual: se incorporan en `site-navbar.css` los estilos compartidos de la cabecera, que estaban definidos solo en la hoja de inicio. Esto evita que el logotipo aparezca a tamaño natural y que los enlaces de navegación se muestren sin formato en las fichas de proveedor.
- Comportamiento: no modifica consultas, datos ni enlaces de navegación; queda pendiente de revisión visual manual en escritorio y móvil.

## Auditoría visual pendiente — lista y filtros de resultados (30 de octubre de 2026)

- Ruta: `/result/services`.
- Referencia: propuesta visual aprobada de resultados y hoja inferior de filtros.
- Cambio visual: se elimina la acción visible “Ver perfil”; toda la tarjeta del proveedor abre su perfil, excepto sus controles propios. “Contactar” continúa siendo la única acción visible. Los filtros dejan de ocupar espacio en la lista y se muestran en una hoja inferior al pulsar el icono de filtros.
- Comportamiento: se conservan la búsqueda, la consulta real de proveedores, los parámetros de orden y los umbrales de valoración ya autorizados. La hoja permite ajustar valores y aplicarlos explícitamente con “Ver resultados”; queda pendiente la comprobación visual manual en escritorio y móvil.
