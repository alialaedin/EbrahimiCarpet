<?php

use Illuminate\Support\Facades\Route;
use Modules\Payment\Http\Controllers\Admin\PaymentController;

Route::middleware('auth')->prefix('/admin/payments')->name('admin.payments.')->group(function () {
	
	Route::get('/cheques', [PaymentController::class, 'cheques'])->name('cheques');
	Route::get('/installments', [PaymentController::class, 'installments'])->name('installments');
	Route::get('/cashes', [PaymentController::class, 'cashes'])->name('cashes');
  Route::get('/', [PaymentController::class, 'index'])->name('index');
	Route::get('/{supplier}', [PaymentController::class, 'show'])->name('show');
	Route::get('/create/{supplier}', [PaymentController::class, 'create'])->name('create');
	Route::post('/', [PaymentController::class, 'store'])->name('store');
	Route::get('/{payment}/edit', [PaymentController::class, 'edit'])->name('edit');
	Route::patch('/{payment}', [PaymentController::class, 'update'])->name('update');
	Route::delete('/{payment}', [PaymentController::class, 'destroy'])->name('destroy');
	Route::delete('/{payment}/image', [PaymentController::class, 'destroyImage'])->name('image.destroy');

});
