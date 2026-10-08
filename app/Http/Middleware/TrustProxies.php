<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     *
     * En Render el TLS termina en su balanceador, cuyas IPs no son fijas, y el
     * contenedor solo es accesible a través de él. '*' confía únicamente en la
     * IP que conecta directamente (REMOTE_ADDR), no en toda la cadena de
     * X-Forwarded-For. En local, sin proxy, no tiene efecto.
     *
     * @var array<int, string>|string|null
     */
    protected $proxies = '*';

    /**
     * The headers that should be used to detect proxies.
     *
     * Solo se aceptan el protocolo y la IP del cliente. X-Forwarded-Host no se
     * acepta para evitar envenenar el host de las URLs generadas (p. ej. los
     * enlaces de restablecimiento de contraseña); Render ya envía el Host
     * correcto y el puerto se deduce del esquema (443 para https).
     *
     * @var int
     */
    protected $headers =
        Request::HEADER_X_FORWARDED_FOR |
        Request::HEADER_X_FORWARDED_PROTO;
}
