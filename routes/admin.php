<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderAdminController;
use App\Http\Controllers\Admin\SubcategoryController;
use App\Http\Controllers\Admin\ProductController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['role:admin']], function () {
    Route::get('/', DashboardController::class)->name('dashboard');

    Route::resource('categories', CategoryController::class);
    Route::resource('subcategories', SubcategoryController::class);
    Route::resource('products', ProductController::class);
    Route::resource('orders', OrderAdminController::class);
    Route::patch('categories/{category}/status', [CategoryController::class, 'updateStatus'])->name('categories.updateStatus');
    Route::patch('subcategories/{subcategory}/status', [SubcategoryController::class, 'updateStatus'])->name('subcategories.updateStatus');
    Route::patch('products/{product}/status', [ProductController::class, 'updateStatus'])->name('products.updateStatus');
    Route::patch('orders/{id}/status', [OrderAdminController::class, 'updateStatus'])->name('orders.updateStatus');
});