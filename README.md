<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Despliegue en Render (base de datos remota)

La app se despliega en Render usando el `Dockerfile` del repositorio. El archivo `.env` **no** se sube a la imagen, así que todas las variables deben configurarse en **Render → tu servicio → Environment**.

### Aplicación

| Variable | Valor de ejemplo | Notas |
|---|---|---|
| `APP_NAME` | `VirtualCafePeru` | |
| `APP_ENV` | `production` | |
| `APP_KEY` | `base64:...` | Generar en local con `php artisan key:generate --show` |
| `APP_DEBUG` | `false` | No usar `true` en producción |
| `APP_URL` | `https://tu-servicio.onrender.com` | URL pública que asigna Render |
| `LOG_CHANNEL` | `stderr` | Para ver los logs en el panel de Render |
| `LOG_LEVEL` | `error` | |

### Base de datos remota (MySQL)

| Variable | Valor de ejemplo | Notas |
|---|---|---|
| `DB_CONNECTION` | `mysql` | |
| `DB_HOST` | `mysql-xxxx.proveedor.com` | Host del proveedor (Aiven, Railway, PlanetScale, Clever Cloud, etc.) |
| `DB_PORT` | `3306` | Algunos proveedores usan un puerto distinto |
| `DB_DATABASE` | `virtualcafeperu` | |
| `DB_USERNAME` | `usuario` | |
| `DB_PASSWORD` | `********` | |
| `DATABASE_URL` | `mysql://usuario:clave@host:3306/basedatos` | *Opcional.* Alternativa a las variables `DB_*` anteriores; si se define, tiene prioridad |
| `MYSQL_ATTR_SSL_CA` | `/etc/ssl/certs/ca-certificates.crt` | *Opcional.* Solo si el proveedor exige conexión SSL |

> El servidor de base de datos debe aceptar conexiones externas (desde cualquier IP o desde las IPs de salida de Render).

### Sesiones, caché y colas

| Variable | Valor | Notas |
|---|---|---|
| `SESSION_DRIVER` | `database` | Requiere la tabla `sessions` en la base remota |
| `CACHE_DRIVER` | `file` | |
| `QUEUE_CONNECTION` | `sync` | |
| `FILESYSTEM_DRIVER` | `local` | |

### Migraciones

El `Dockerfile` **no** ejecuta migraciones al arrancar. Antes del primer despliegue, crea las tablas en la base remota, por ejemplo desde tu máquina local apuntando el `.env` a la base remota:

```bash
php artisan migrate --force
php artisan db:seed --force   # si necesitas datos iniciales
```

O desde la pestaña **Shell** del servicio en Render: `php artisan migrate --force`.

Después de cambiar variables de entorno en Render, haz un **Manual Deploy** para que `php artisan config:cache` vuelva a leerlas.

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 1500 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Cubet Techno Labs](https://cubettech.com)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[Many](https://www.many.co.uk)**
- **[Webdock, Fast VPS Hosting](https://www.webdock.io/en)**
- **[DevSquad](https://devsquad.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[OP.GG](https://op.gg)**
- **[WebReinvent](https://webreinvent.com/?utm_source=laravel&utm_medium=github&utm_campaign=patreon-sponsors)**
- **[Lendio](https://lendio.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
