<?php

use Illuminate\Support\Facades\Route;
use Modules\Store\Http\Controllers\Admin\StoreController;

Route::middleware('auth')->prefix('/admin')->name('admin.')->group(function () {
	Route::put('/increment-balance', [StoreController::class, 'incrementBalance'])->name('stores.increment-balance');
	Route::put('/decrement-balance', [StoreController::class, 'decrementBalance'])->name('stores.decrement-balance');
	Route::resource('/stores', StoreController::class)->only('index', 'show');
});
