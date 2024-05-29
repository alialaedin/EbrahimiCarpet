<?php

use Illuminate\Support\Facades\Route;
use Modules\Payment\Http\Controllers\Admin\PaymentController;

Route::middleware('auth')->prefix('/admin/purchases')->name('admin.purchases.')->group(function () {
	Route::get('/{purchase}/payments', [PaymentController::class, 'index'])->name('payments.index');
	Route::get('/{purchase}/payments/create', [PaymentController::class, 'create'])->name('payments.create');
	Route::delete('/payments/{payment}/image', [PaymentController::class, 'destroyImage'])->name('payments.image.destroy');
	Route::resource('/payments', PaymentController::class)->except('index', 'show', 'create');
});
