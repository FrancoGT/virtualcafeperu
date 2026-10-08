#!/bin/sh
# Arranque del contenedor en Render. Se ejecuta en tiempo de ejecución para que
# las cachés de Laravel se generen con las variables reales del servicio
# (APP_URL, APP_ENV, SESSION_*, DB_*...) y no con las del entorno de build.
set -e

PORT="${PORT:-10000}"
sed -i "s/^Listen .*/Listen ${PORT}/" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:[0-9]*>/<VirtualHost *:${PORT}>/" /etc/apache2/sites-available/000-default.conf

# Descarta cualquier caché previa de configuración, rutas y vistas.
php artisan config:clear
php artisan route:clear
php artisan view:clear

php artisan package:discover --ansi
php artisan storage:link 2>/dev/null || true

php artisan config:cache
php artisan view:cache

exec apache2-foreground
