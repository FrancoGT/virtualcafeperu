# CafeVirtualPeru

Tienda en línea de repostería, bebidas y café: los clientes arman su pedido, lo confirman por WhatsApp y el administrador gestiona categorías, productos y pedidos desde `/admin`.

**Stack:** PHP 8.4 · Laravel 13 · Jetstream 5 (Livewire 4) · Sanctum 4 · Spatie Permission 8 · MySQL 8.4

## Ejecutar con Docker

Se usan **dos contenedores** conectados por una red de Docker:

| Contenedor          | Imagen            | Qué hace                                  | Se crea en |
|---------------------|-------------------|-------------------------------------------|------------|
| `mysql-virtualcafe` | `mysql:8.4`       | Base de datos                             | Paso 1     |
| `virtualcafeperu`   | `virtualcafeperu` | La aplicación (Apache + PHP 8.4, Laravel) | Paso 5     |

> **Importante:** construir la imagen (paso 3) **no** crea el contenedor. El contenedor `virtualcafeperu` solo existe después de que el `docker run` del paso 5 termine sin errores. Hasta entonces, cualquier `docker exec virtualcafeperu ...` falla con `No such container: virtualcafeperu`.

Todos los comandos están escritos en **una sola línea** para que funcionen igual en PowerShell, CMD y bash. Ejecútalos desde la carpeta del proyecto.

### 1. Base de datos (MySQL 8.4)

```bash
docker run -d --name mysql-virtualcafe -e MYSQL_ALLOW_EMPTY_PASSWORD=yes -e MYSQL_DATABASE=virtualcafeperu -p 127.0.0.1:3306:3306 -v mysql-virtualcafe-data:/var/lib/mysql mysql:8.4
```

- Usuario `root` sin contraseña. Úsalo solo en desarrollo.
- El puerto se publica solo en `127.0.0.1`, así que también puedes conectarte desde tu máquina (Herd, TablePlus, `mysql -h 127.0.0.1 -u root`).
- Los datos se guardan en el volumen `mysql-virtualcafe-data` y no se pierden al borrar el contenedor.

**Comprobar:** `docker ps --filter name=mysql-virtualcafe` debe mostrarlo con estado `Up`.

### 2. Red compartida

```bash
docker network create virtualcafe
docker network connect virtualcafe mysql-virtualcafe
```

Dentro de la red, la aplicación llega a MySQL con el host `mysql-virtualcafe` (por eso `DB_HOST=mysql-virtualcafe` en el paso 4).

**Comprobar:** `docker network inspect virtualcafe` debe listar `mysql-virtualcafe` en `Containers`.

### 3. Imagen de la aplicación

```bash
docker build -t virtualcafeperu .
```

Esto solo crea la **imagen** (la plantilla). Hay que repetirlo cada vez que cambies el código.

**Comprobar:** `docker images virtualcafeperu` debe mostrar la imagen.

### 4. Archivo de variables de entorno `.env.docker`

Este archivo **es obligatorio**: el paso 5 lo lee con `--env-file`. No está en el repositorio ni se copia a la imagen (`.dockerignore` excluye `.env*`), así que tienes que crearlo tú en la raíz del proyecto, junto al `Dockerfile`.

Primero genera una clave para la aplicación:

```bash
docker run --rm virtualcafeperu php artisan key:generate --show
```

Copia el valor que imprime (empieza con `base64:`) y crea `.env.docker` con este contenido, pegándolo en `APP_KEY`:

```dotenv
APP_NAME=CafeVirtualPeru
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000
APP_KEY=base64:PEGA_AQUI_LA_CLAVE

DB_CONNECTION=mysql
DB_HOST=mysql-virtualcafe
DB_PORT=3306
DB_DATABASE=virtualcafeperu
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=database
CACHE_STORE=file
QUEUE_CONNECTION=sync
FILESYSTEM_DISK=public
LOG_CHANNEL=stderr
```

> - Con `APP_ENV=production` todas las URLs se generan con `https://` (pensado para Render). En local deja `APP_ENV=local`.
> - No pongas comillas ni espacios alrededor del `=`: `--env-file` toma los valores literalmente.

**Comprobar:** `ls .env.docker` debe encontrar el archivo.

### 5. Crear y arrancar el contenedor de la aplicación

```bash
docker run -d --name virtualcafeperu --network virtualcafe --env-file .env.docker -p 8000:10000 virtualcafeperu
```

| Opción                     | Significado                                                        |
|----------------------------|--------------------------------------------------------------------|
| `--name virtualcafeperu`   | Nombre del contenedor (el que usan los `docker exec` siguientes)   |
| `--network virtualcafe`    | Lo conecta a la misma red que MySQL                                |
| `--env-file .env.docker`   | Carga las variables del paso 4                                     |
| `-p 8000:10000`            | `localhost:8000` en tu máquina → puerto `10000` del contenedor     |
| `virtualcafeperu` (último) | La imagen del paso 3                                               |

Al arrancar, `docker/start.sh` limpia y regenera las cachés de Laravel, crea el enlace `public/storage` e inicia Apache.

**Comprobar (no sigas si esto falla):**

```bash
docker ps --filter name=virtualcafeperu
```

Debe aparecer con estado `Up`. Si no aparece, revisa [Solución de problemas](#solución-de-problemas).

### 6. Migraciones y datos iniciales

Solo cuando el paso 5 muestre el contenedor en estado `Up`:

```bash
docker exec virtualcafeperu php artisan migrate --force
docker exec virtualcafeperu php artisan db:seed --force
```

`db:seed` es opcional: crea roles, usuarios, categorías y productos de ejemplo.

Abre **http://localhost:8000**. El panel de administración está en `/admin` (requiere un usuario con rol `admin`).

## Solución de problemas

### `Error response from daemon: No such container: virtualcafeperu`

El contenedor de la aplicación no existe porque el paso 5 no se ejecutó o falló. Compruébalo:

```bash
docker ps -a --filter name=virtualcafeperu
```

- **No aparece nada:** el `docker run` del paso 5 falló antes de crear el contenedor. Vuelve a ejecutarlo y lee el mensaje de error. Las causas habituales son:
  - `open .env.docker: The system cannot find the file specified`: falta el archivo del paso 4, o no estás en la carpeta del proyecto.
  - `invalid reference format` o `"\" is not a valid ...`: copiaste un comando partido en varias líneas con `\` en PowerShell. Usa los comandos en una sola línea, tal como están en esta guía.
  - `Unable to find image 'virtualcafeperu:latest'`: falta el paso 3 (`docker build`).
  - `port is already allocated`: otro programa usa el puerto 8000. Cambia `-p 8000:10000` por `-p 8080:10000` (y `APP_URL` a `http://localhost:8080`).
- **Aparece con estado `Exited`:** el contenedor se creó pero se cayó al arrancar. Mira la causa con `docker logs virtualcafeperu`, corrígela y luego recréalo con `docker rm virtualcafeperu` y otra vez el paso 5.

### `Conflict. The container name "/virtualcafeperu" is already in use`

Ya existe un contenedor con ese nombre (aunque esté detenido). Si solo quieres iniciarlo: `docker start virtualcafeperu`. Si quieres recrearlo: `docker rm -f virtualcafeperu` y repite el paso 5.

### `SQLSTATE[HY000] [2002] php_network_getaddresses: getaddrinfo for mysql-virtualcafe failed`

La aplicación no encuentra MySQL. Comprueba que `mysql-virtualcafe` esté en estado `Up` y conectado a la red (paso 2), y que `.env.docker` tenga `DB_HOST=mysql-virtualcafe`.

### Cambié `.env.docker` y no se nota

Las variables se leen al **crear** el contenedor; `docker restart` no las recarga. Recréalo:

```bash
docker rm -f virtualcafeperu
docker run -d --name virtualcafeperu --network virtualcafe --env-file .env.docker -p 8000:10000 virtualcafeperu
```

## Comandos útiles

```bash
# estado de los contenedores
docker ps -a --filter name=virtualcafe

# logs de la aplicación (Ctrl+C para salir)
docker logs -f virtualcafeperu

# consola de Laravel y de MySQL
docker exec -it virtualcafeperu php artisan tinker
docker exec -it mysql-virtualcafe mysql -u root virtualcafeperu

# reconstruir tras cambiar el código
docker rm -f virtualcafeperu
docker build -t virtualcafeperu .
docker run -d --name virtualcafeperu --network virtualcafe --env-file .env.docker -p 8000:10000 virtualcafeperu

# detener / volver a iniciar (sin perder datos)
docker stop virtualcafeperu mysql-virtualcafe
docker start mysql-virtualcafe virtualcafeperu
```

## Desarrollo local sin Docker (Herd)

Con el contenedor de MySQL del paso 1 en marcha:

```bash
composer install
cp .env.example .env        # DB_HOST=127.0.0.1, DB_DATABASE=virtualcafeperu, DB_USERNAME=root
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
```

## Tests

```bash
php artisan test
```

Los tests usan SQLite en memoria (`phpunit.xml`), así que no tocan la base de datos MySQL.
