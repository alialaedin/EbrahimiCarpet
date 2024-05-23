<?php

use Illuminate\Support\Facades\Route;
use Modules\Product\Http\Controllers\Admin\CategoryController;
use Modules\Product\Http\Controllers\Admin\ProductController;

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

Route::middleware('auth')->prefix('/admin')->name('admin.')->group(function () {
	Route::delete('/products/{product}/image', [ProductController::class, 'destroyImage'])->name('products.image.destroy');
	Route::resource('/products', ProductController::class);
});

Route::middleware('auth')->prefix('/admin')->name('admin.')->group(function () {
	Route::resource('/categories', CategoryController::class);
});
