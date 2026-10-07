<?php

namespace App\Http\Controllers;

class WelcomeController extends Controller
{
    public function index()
    {
        return view('welcome');
    }

    public function pagina_cliente()
    {
        // Tras iniciar sesión el cliente vuelve a la tienda, a sus pedidos
        return redirect(route('home') . '#pedidos');
    }
}
