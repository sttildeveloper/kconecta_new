# Despliegue de Kconecta New en Dokploy

Actualizado: 30 de octubre de 2026.

## Estado desplegado

- URL pública: `https://nuevo.kconecta.com`.
- DNS: registro `A` de `nuevo.kconecta.com` hacia `72.62.30.135`.
- HTTPS: certificado automático de Let's Encrypt gestionado por Dokploy.
- Repositorio de despliegue: `https://github.com/sttildeveloper/kconecta_new`.
- Rama de despliegue: `main`.
- Build: `Dockerfile` del repositorio; no usar Nixpacks para este servicio.

La aplicación funciona como un servicio Laravel separado de MySQL. La base de
datos no se expone a Internet y se comunica con la aplicación por la red interna
de Dokploy.

## Servicios Dokploy

Proyecto y entorno:

- Proyecto: `kconecta_new`.
- Entorno: `production`.

Servicios:

| Función | Servicio | Referencia interna |
| --- | --- | --- |
| Aplicación Laravel | `web` | `kconectanew-web` |
| MySQL 8 | `mysql` | `kconectanew-mysql-cw9vvk` |

El dominio de `web` se configuró con ruta externa e interna `/`, puerto interno
del contenedor `80` y HTTPS activado. No se debe publicar el puerto `3306` de
MySQL; si se hubiese añadido un puerto externo durante una prueba, debe
eliminarse.

## Variables de entorno de la aplicación

Configurar en el servicio `web` las variables estándar de Laravel, sin guardar
secretos en Git:

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://nuevo.kconecta.com
SESSION_DOMAIN=nuevo.kconecta.com
SESSION_SECURE_COOKIE=true

DB_CONNECTION=mysql
DB_HOST=kconectanew-mysql-cw9vvk
DB_PORT=3306
DB_DATABASE=kconecta_new
DB_USERNAME=mysql
DB_PASSWORD=<contraseña-interna-de-mysql>
```

`APP_KEY` debe contener una clave Laravel válida, normalmente con prefijo
`base64:`. Es normal que una clave generada por `openssl rand -base64 32` termine
en `=`. Si la interfaz de Dokploy recibe el contenido completo de `.env` y la
contraseña tiene caracteres como `#` o `@`, debe entrecomillarse:

```dotenv
DB_PASSWORD="<contraseña>"
```

No añadir la contraseña raíz de MySQL, el `APP_KEY` ni otros secretos a este
documento, a los commits ni a capturas públicas.

## Datos restaurados

Se restauró una copia de trabajo de la base local en el servicio MySQL. La copia
validada contiene 84 tablas. El archivo local utilizado está ignorado por Git:

```text
backups/kconecta_new_20261030.sql.gz
```

Antes de importar en otro entorno, hacer una copia de seguridad del destino y
validar el archivo. La importación se realiza desde el host o mediante el
contenedor de MySQL, nunca contra la base CRM de referencia.

Los directorios de medios restaurados son:

| Contenido | Volumen Dokploy | Destino en `web` |
| --- | --- | --- |
| Imágenes de proveedores | `kconecta-new-uploads` | `/var/www/html/public/img/uploads` |
| Vídeos de proveedores | `kconecta-new-videos` | `/var/www/html/public/video/uploads` |
| Fotos de perfil | `kconecta-new-profile-photos` | `/var/www/html/public/img/photo_profile` |

Las copias de origen se prepararon en `backups/` y están excluidas del
repositorio. Los volúmenes son imprescindibles: sin ellos, un redeploy puede
perder los archivos que no formen parte de la imagen Docker.

## Comprobaciones posteriores a un deploy

1. Confirmar que Dokploy ha terminado el deploy de `web` y que el servicio está
   iniciado.
2. Abrir `https://nuevo.kconecta.com` y una consulta real, por ejemplo
   `https://nuevo.kconecta.com/result/services?mode=1&sti[]=23`.
3. Verificar la conexión a MySQL desde el contenedor `web`:

   ```sh
   php artisan migrate:status
   ```

   No ejecutar migraciones sobre una copia restaurada sin revisar antes su
   estado y necesidad.
4. Comprobar imágenes, fotos de perfil y vídeos de un proveedor real.
5. Revisar la ruta `/result_provider/{id}` y la lista de resultados en escritorio
   y móvil.

## Incidencias resueltas durante la puesta en marcha

- El primer build se ejecutaba con Nixpacks y servía la aplicación desde `/app`.
  Se corrigió seleccionando el `Dockerfile`; la imagen final sirve Laravel desde
  `/var/www/html` por el puerto 80.
- Un error 500 inicial correspondía a `APP_KEY` ausente y, después, a un nombre
  de host MySQL de marcador. Se corrigieron las variables anteriores.
- La contraseña MySQL con caracteres especiales necesitó el formato de variable
  correcto para que no fuese interpretada como comentario.
- El log Laravel de la imagen Docker se encuentra en
  `/var/www/html/storage/logs`; no en `/app/storage/logs`.
- El perfil público tenía la cabecera sin estilos porque estos existían solo en
  la hoja de inicio. Se trasladaron a `public/css/components/site-navbar.css`.

## Calidad de datos pendiente

Los caracteres `?` de nombres como `Sig?enza` o `Russ?` están almacenados así
en los registros importados; no son un problema de collation de MySQL ni de la
interfaz. Un export vuelve a conservar esos mismos bytes. La reparación masiva
debe partir de una fuente canónica que contenga los nombres correctos, con
validación y respaldo previos. Ver también `CONTEXT.md`.
