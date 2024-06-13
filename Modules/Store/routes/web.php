<?php

use Illuminate\Support\Facades\Route;
use Modules\Store\Http\Controllers\Admin\StoreController;

Route::middleware('auth')->prefix('/admin')->name('admin.')->group(function () {
	Route::resource('/stores', StoreController::class)->only('index', 'show');
});
