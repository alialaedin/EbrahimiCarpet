<?php

use Illuminate\Support\Facades\Route;
use Modules\Purchase\Http\Controllers\Admin\PurchaseController;
use Modules\Purchase\Http\Controllers\Admin\PurchaseItemController;

Route::middleware('auth')->prefix('/admin')->name('admin.')->group(function () {
	Route::resource('/purchases', PurchaseController::class);
	Route::resource('/purchase-items', PurchaseItemController::class)->only('store', 'update', 'destroy');
});
