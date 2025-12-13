<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\CategoryController;


Route::get('/', function () {
    return redirect()->route('menu');
});

// route ke menu
Route::get('/menu', [MenuController::class, 'index'])->name('menu');
Route::get('/cart', [MenuController::class, 'cart'])->name('cart');
Route::post('/cart/add', [MenuController::class, 'addToCart'])->name('cart.add');
Route::post('/cart/update', [MenuController::class, 'updateCart'])->name('cart.update');
Route::post('/cart/remove', [MenuController::class, 'removeCart'])->name('cart.remove');
Route::get('/cart/clear', [MenuController::class, 'clearCart'])->name('cart.clear');
// route ke checkout
Route::get('/checkout', [MenuController::class, 'checkout'])->name('checkout');
Route::post('/checkout/store', [MenuController::class, 'storeOrder'])->name('checkout.store');
Route::get('/checkout/success/{orderId}', [MenuController::class, 'checkoutSuccess'])->name('checkout.success');

// route admin
Route::resource('categories', CategoryController::class);
Route::get('/dashboard', function () {
    return view('admin.dashboard');
})->name('dashboard');
Route::resource('categories', CategoryController::class);
Route::resource('items', CategoryController::class);
Route::resource('roles', CategoryController::class);
Route::resource('users', CategoryController::class);
Route::resource('orders', CategoryController::class);