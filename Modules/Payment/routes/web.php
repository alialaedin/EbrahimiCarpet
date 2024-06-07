<?php

use Illuminate\Support\Facades\Route;
use Modules\Payment\Http\Controllers\Admin\PaymentController;

Route::middleware('auth')->prefix('/admin/payments')->name('admin.payments.')->group(function () {

	Route::get('/{supplier}', [PaymentController::class, 'index'])->name('index');

	Route::get('/create/{supplier}', [PaymentController::class, 'create'])->name('create');

	Route::post('/', [PaymentController::class, 'store'])->name('store');

	Route::get('/{payment}/edit', [PaymentController::class, 'edit'])->name('edit');

	Route::patch('/{payment}', [PaymentController::class, 'update'])->name('update');

	Route::delete('/{payment}', [PaymentController::class, 'destroy'])->name('destroy');

	Route::delete('/{payment}/image', [PaymentController::class, 'destroyImage'])->name('image.destroy');
	
});
