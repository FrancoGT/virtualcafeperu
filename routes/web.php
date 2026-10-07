<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\JsonProductos;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [WelcomeController::class, 'index'])->name('home');
Route::post('/cart/add/{productId}', [CartController::class, 'addToCart'])->name('cart.add');
Route::get('/cart', [CartController::class, 'showCart'])->name('cart.show');
Route::post('/checkout', [CartController::class, 'checkout'])->name('checkout');
Route::delete('/remove-from-cart/{productId}', [CartController::class, 'removeFromCart'])->name('remove_from_cart');

Route::get('/orders/pending', [OrderController::class, 'getPendingOrders']);
Route::get('/products/search', [JsonProductos::class, 'search']);
Route::get('/products/popular', [JsonProductos::class, 'getPopularProducts']);
Route::get('/products/products-by-subcategory', [JsonProductos::class, 'getProductsBySubcategory']);

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', [WelcomeController::class, 'pagina_cliente'])->name('dashboard');
    Route::get('/jsonproductos', [JsonProductos::class, 'index'])->name('jsonproductos');
});