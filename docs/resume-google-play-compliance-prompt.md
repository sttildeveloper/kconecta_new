# Prompt de reanudación — Cumplimiento Google Play

```text
Continúa en C:\MeegDev\kconecta-crm\web con el cierre de cumplimiento para Google Play.

Antes de actuar:

1. Lee completamente agent.md.
2. Lee README.md, tasks.md, docs/google-play-compliance.md y
   docs/mobile-compliance-contract.md.
3. Revisa git status y git diff. Conserva todos los cambios existentes.
4. No elimines ni incluyas accidentalmente los JPG/ZIP de public/img/service-types
   ni el directorio scripts/, porque son cambios preexistentes del usuario.
5. No hagas commit, push, despliegue, migraciones ni cambios en producción sin
   autorización expresa. Recuerda que un push a main activa autodeploy.
6. No muestres secretos ni leas/imprimas el contenido completo de .env.

Estado conocido:

- Producción está en commit 661763a y las migraciones de cumplimiento se aplicaron
  correctamente como batch 20 después de un backup validado.
- El seguimiento de políticas de retención/eliminación y las casillas legales web
  está implementado localmente, no publicado.
- Los registros web general y de proveedor exigen dos controles separados:
  aceptación de Términos y confirmación de lectura de Privacidad, versión inicial
  1.0. Se registra fecha, IP y agente de usuario.
- La regresión completa local quedó en 195 tests / 1.803 aserciones, todo correcto.
- Edad mínima confirmada: 18 años.
- Correo confirmado: info@kconecta.com.
- Infraestructura confirmada: datos activos en Hostinger París; backups automáticos
  diarios separados en Países Bajos, máximo 14 días.
- Mantén LEGAL_ACCEPTANCE_REQUIRED_ON_REGISTRATION=false hasta que la app móvil
  compatible esté probada y los textos definitivos estén online.
- El Codex móvil está trabajando en aceptación legal, eliminación de cuenta,
  denuncias/bloqueos, auditoría Data Safety y preparación de APK/AAB.

Primero pregunta/identifica si ya llegó:

A) Respuesta de JM:
- nombre legal o razón social del responsable;
- NIF/NIE/CIF;
- domicilio fiscal/legal;
- representante y cargo si es sociedad;
- confirmación de legislación española respetando el fuero imperativo del consumidor;
- fecha efectiva de Términos y Privacidad versión 1.0;
- responsable/correo/SLA de moderación;
- SLA para eliminaciones solicitadas por correo;
- confirmación de público 18+, países y publicidad móvil.

B) Informe del Codex móvil:
- archivos y pantallas modificados;
- endpoints y payloads integrados;
- resultados de pruebas;
- inventario de permisos y SDK;
- evidencia para Data Safety;
- estado de APK y AAB;
- riesgos o bloqueos restantes.

Si llegó la información de JM, intégrala únicamente en variables/configuración y
textos legales, sin inventar datos. Revisa coherencia entre Privacidad, Términos,
eliminación y Data Safety. Si llegó el informe móvil, valida compatibilidad del
contrato con el CRM. Ejecuta pruebas focales y regresión completa.

No uses ni solicites ahora contraseñas personales o administrativas. Las cuentas de
revisión de Google deben ser dedicadas, no administrativas, y cargarse directamente
en Play Console App access cuando el artefacto esté preparado.

Al finalizar informa claramente:
- qué estaba desplegado y qué seguía solo local;
- archivos modificados;
- datos de JM todavía pendientes;
- compatibilidad móvil;
- pruebas ejecutadas;
- riesgos restantes;
- si estamos listos para pedir autorización de commit y push.
```
