<?php

use Illuminate\Support\Facades\Route;
use Modules\Sale\Http\Controllers\Admin\SaleController;
use Modules\Sale\Http\Controllers\Admin\SaleItemController;
use Modules\Sale\Http\Controllers\Admin\SalePaymentController;

Route::middleware('auth')->prefix('/admin')->name('admin.')->group(function () {

  Route::post('/sales/get-product-store', [SaleController::class, 'getProductStore'])->name('sales.get-product-store');

  Route::resource('/sale-items', SaleItemController::class)->only('store', 'update', 'destroy');
  Route::resource('/sales', SaleController::class);

  Route::prefix('/sales/{sale}/invoice')->name('sales.invoice.')->group(function () {
    Route::get('/', [SaleController::class, 'showInvoice'])->name('show');
    Route::post('/', [SaleController::class, 'printInvoice'])->name('print');
  });

  Route::name('sale-payments.')->prefix('/sale-payments')->group(function() {

    Route::get('/cheques', [SalePaymentController::class, 'cheques'])->name('cheques');
    Route::get('/installments', [SalePaymentController::class, 'installments'])->name('installments');
    Route::get('/cashes', [SalePaymentController::class, 'cashes'])->name('cashes');

    Route::get('/', [SalePaymentController::class, 'index'])->name('index');
    Route::get('/{customer}', [SalePaymentController::class, 'show'])->name('show');
    Route::get('/create/{customer}', [SalePaymentController::class, 'create'])->name('create');
    Route::post('/', [SalePaymentController::class, 'store'])->name('store');
    Route::get('/{sale_payment}/edit', [SalePaymentController::class, 'edit'])->name('edit');
    Route::put('/{sale_payment}', [SalePaymentController::class, 'update'])->name('update');
    Route::delete('/{sale_payment}', [SalePaymentController::class, 'destroy'])->name('destroy');
    Route::delete('/{sale_payment}/image', [SalePaymentController::class, 'destroyImage'])->name('image.destroy');
  });

});
