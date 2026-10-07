<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ClearCart extends Command
{
    protected $signature = 'cart:clear';
    protected $description = 'Clears the content of the cart in the session';

    public function handle()
    {
        // Borra el contenido del carrito en la sesión
        session(['cart' => []]);

        $this->info('The cart content has been cleared successfully.');
    }
}