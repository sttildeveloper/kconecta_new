# Preparación de cumplimiento Google Play

## Estado de entrega

- Desplegado en producción: commit `661763a`, migraciones de denuncias/bloqueos y
  aceptaciones legales, eliminación de cuenta, contratos API y endurecimiento base.
- Solo local: políticas definitivas de retención/eliminación, ampliación final de
  plantillas legales y casillas obligatorias de Términos/Privacidad en los dos
  registros web.
- Validación local vigente: 195 tests / 1.803 aserciones, todas correctas.
- No publicar el seguimiento local hasta completar los datos de JM y hacer una
  revisión final coordinada con la app móvil.

## Decisiones seguras aplicadas

- La eliminación de cuenta retira inmediatamente el contenido público del proveedor, relaciones comerciales, dirección, especialidades, medios físicos exclusivos, tokens, sesiones y métricas.
- Los archivos se mueven a staging antes de la transacción: un rollback restaura los archivos. Las rutas absolutas, traversal y archivos compartidos se rechazan.
- Las valoraciones se conservan vinculadas a la identidad interna despersonalizada; los códigos de trabajo se bloquean; tickets y mensajes se cierran y despersonalizan; las auditorías quedan fuera del uso ordinario.
- Los dos registros web exigen por separado aceptar los Términos y confirmar la lectura de la Política de privacidad vigentes. El servidor valida la versión y registra usuario, documento, versión, fecha, IP y agente de usuario.
- La obligatoriedad global de la API móvil permanece desactivada hasta una publicación coordinada; esto no desactiva las casillas obligatorias de la web.
- Consentimiento web versionado por categorías: necesarias, analítica y publicidad. AdSense solo se carga tras consentimiento de publicidad.
- Logs: 90 días; IP: 30 días; agentes de usuario: 90 días; métricas identificables: 395 días; tickets/mensajes cerrados: 730 días; auditorías y denuncias resueltas: 1.095 días; denuncias rechazadas: 365 días; aceptaciones legales y códigos bloqueados: 1.825 días.
- Los comandos de retención son `compliance:retention-inventory` y `compliance:retention-prune`; el segundo es dry-run salvo `--apply`. Ninguno elimina backups.

## Retención acordada de backups

- Automáticos Hostinger: máximo 14 días.
- Manuales rutinarios: 30 días.
- Excepcionales de migración: 90 días.

## Datos confirmados por JM

- Correo de privacidad y soporte: `info@kconecta.com`.
- Datos activos: VPS de Hostinger en París, Francia.
- Copias automáticas separadas: Países Bajos, frecuencia diaria y conservación máxima de 14 días.
- Política operativa de retención indicada en este documento.
- Edad mínima de registro y uso: 18 años.
- Primera versión de Términos y Privacidad: `1.0`.
- Responsable del tratamiento: sociedad `Sttil Tecnology`, CIF `B19403427`.
- Nombre comercial: `KConecta`.
- Domicilio legal: Av. Josep Tarradellas 115-117, 08029 Barcelona, España.
- Teléfono de contacto: `+34 680 225 298`.
- Legislación aplicable: española, respetando los derechos y fueros imperativos de
  consumidores y usuarios.
- Responsable operativo interno de revisión de denuncias: Miguel Yesan. Su nombre
  no se publica en las páginas legales; externamente actúa el equipo de moderación
  de la sociedad a través del canal de soporte.
- José María Bardina Arancibia actúa como apoderado, pero no es el responsable del
  tratamiento a título personal. Su DNI no debe publicarse ni almacenarse en estos
  documentos o en la configuración de la aplicación.

## Datos que JM debe proporcionar antes de publicar textos definitivos

- Fecha efectiva final de Términos y Privacidad al publicar la versión `1.0`.
- Datos de inscripción en el Registro Mercantil aplicables a la sociedad.
- Plazo objetivo de respuesta a denuncias.
- Plazo operativo para atender solicitudes de eliminación recibidas por correo.
- Confirmación de que `info@kconecta.com` será el canal de privacidad, soporte,
  moderación y eliminación, o indicación de canales separados.
- Confirmación de público objetivo 18+, países iniciales y si la app móvil tendrá
  publicidad.

La identidad de la sociedad, CIF, domicilio y jurisdicción ya están definidos. Las
credenciales de revisión de Google deben cargarse posteriormente en Play Console y
no guardarse en el repositorio ni compartirse en los documentos de contexto.
