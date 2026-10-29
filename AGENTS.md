# Instrucciones de contexto para Kconecta New

Lee primero [`README.md`](./README.md) y [`CONTEXT.md`](./CONTEXT.md).

- Este repositorio es el entorno aislado `kconecta_new`, no el CRM de referencia.
- La web consulta directamente la base MySQL local `kconecta_new`; no uses la API
  móvil para las vistas web.
- Prioriza cambios visuales en Blade, CSS y JavaScript. Los cambios de backend o
  de datos requieren una necesidad funcional explícita y documentación previa.
- Usa imágenes WebP en toda interfaz nueva o modificada.
- Conserva las consultas reales de proveedores por `service_type.id` y
  `provider_services`.
- Marca como pendiente de auditoría cualquier acción visual sin comportamiento
  funcional validado.
