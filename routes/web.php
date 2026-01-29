<?php

use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ProductPageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ProductPageController::class, 'index'])->name('home');
Route::get('/search', [ProductPageController::class, 'search'])->name('products.search');
Route::get('/products/{product}', [ProductPageController::class, 'show'])->name('products.show');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::prefix('admin')->name('admin.')->middleware(['auth:sanctum'])->group(function () {
    Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [AdminProductController::class, 'create'])->name('products.create');
    Route::get('/products/{product}/edit', [AdminProductController::class, 'edit'])->name('products.edit');
});
