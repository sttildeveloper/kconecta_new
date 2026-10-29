# Kconecta New

Entorno local de evolución visual y funcional del CRM de Kconecta. Parte del CRM
de referencia, pero se ejecuta de forma aislada para construir la nueva interfaz
web y perfeccionar sus flujos sin afectar el proyecto de origen.

## Objetivo actual

Perfeccionar el funcionamiento del CRM con la nueva interfaz gráfica y visual.
Los cambios deben conservar las reglas de negocio y las consultas reales ya
existentes, mientras se sustituyen progresivamente las vistas heredadas por la
experiencia Kconecta New.

## Entorno local

- Aplicación: `http://localhost:8011`
- Contenedor web: `kconecta_new-app`
- Contenedor MySQL: `kconecta_new-mysql`
- Esquema de trabajo: `kconecta_new`
- Volumen de datos: `kconecta_new-db-data`

Arranque:

```powershell
docker compose up -d
```

Pruebas focalizadas:

```powershell
docker compose exec -T app php artisan test --filter='(HomePageTest|ServicesCatalogTest|ProviderLandingHeroTest)'
```

## Datos y arquitectura

La aplicación web Laravel consulta MySQL directamente mediante sus modelos y
servicios internos. No consume la API móvil. La API queda reservada para la app
nativa.

La base `kconecta_new` es una copia local e independiente de los datos del CRM de
referencia (`kconecta_schema`). No modificar, eliminar ni importar datos sobre la
base origen durante el trabajo de esta interfaz.

El modelo de búsqueda de profesionales utiliza:

1. `service_type.id` para identificar la categoría elegida.
2. `provider_services` para relacionar proveedor y categoría.
3. `user` y `user_address` para perfil y ubicación.

## Flujos visuales implementados

### Inicio

- Página: `/`
- Recursos gráficos servidos como WebP.
- La opción **Usar mi ubicación** guarda la ubicación temporalmente en la sesión
  del navegador y abre primero el catálogo de servicios.
- La opción de población/dirección sigue el mismo patrón.

### Catálogo de servicios

- Página: `/result/services`
- Muestra las categorías de hogar.
- Al escoger una categoría, se genera la consulta existente con `sti[]` y, si se
  indicó, con ubicación.

### Resultados de profesionales

- Ejemplo: `/result/services?mode=1&sti[]=23`
- Conserva la consulta real contra MySQL y presenta la nueva interfaz de
  resultados.
- Búsqueda de texto, favoritos y filtros visuales se limitan al front actual;
  las acciones que requieran una decisión funcional quedan pendientes de auditoría.
- Las imágenes mostradas son la portada registrada de cada proveedor cuando está
  disponible y es WebP.

### Brindo servicios

- Página: `/quiero-ser-proveedor`
- Interfaz de inscripción demostrativa. No registra ni transmite datos todavía.

## Reglas de trabajo

- Los recursos gráficos nuevos deben convertirse a WebP antes de usarse.
- Preservar la consulta existente y los datos reales cuando se cambien interfaces.
- No introducir una API para la web; el CRM consume su base MySQL directamente.
- No implementar acciones incompletas como si fueran funcionales. Señalarlas como
  pendientes de auditoría funcional.
- Antes de editar controladores, modelos, migraciones o consultas, confirmar que
  el cambio es necesario y está dentro de la auditoría funcional acordada.

## Datos heredados con caracteres especiales

Algunos textos importados ya contienen signos `?` en lugar de caracteres
acentuados. La interfaz corrige las equivalencias conocidas para mostrarlas bien;
la reparación masiva y definitiva de esos datos se tratará en una auditoría de
calidad de datos independiente.

## Próximos contenidos

Añadir nuevos requisitos, referencias visuales y decisiones funcionales en
[`CONTEXT.md`](./CONTEXT.md). Mantener este README como resumen operativo y de
arranque del proyecto.
