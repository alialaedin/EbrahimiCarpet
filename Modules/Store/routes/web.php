<?php

use Illuminate\Support\Facades\Route;
use Modules\Store\Http\Controllers\Admin\StoreController;

Route::middleware('auth')->prefix('/admin')->name('admin.')->group(function () {
	Route::post('/stores/increase-decrease', [StoreController::class, 'increase_decrease'])->name('stores.increase-decrease');
	Route::resource('/stores', StoreController::class)->only('index', 'show');
});
