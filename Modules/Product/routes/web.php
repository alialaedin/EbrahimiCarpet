<?php

use Illuminate\Support\Facades\Route;
use Modules\Product\Http\Controllers\Admin\CategoryController;
use Modules\Product\Http\Controllers\Admin\PricingController;
use Modules\Product\Http\Controllers\Admin\ProductController;

Route::middleware('auth')->prefix('/admin')->name('admin.')->group(function () {
	Route::delete('/products/{product}/image', [ProductController::class, 'destroyImage'])->name('products.image.destroy');
	Route::resource('/products', ProductController::class);
	Route::resource('/pricing', PricingController::class)->only(['create', 'store']);
	Route::apiResource('/categories', CategoryController::class)->except('show');
});
