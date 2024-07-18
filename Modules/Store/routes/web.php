<?php

use Illuminate\Support\Facades\Route;
use Modules\Store\Http\Controllers\Admin\StoreController;

Route::middleware('auth')->prefix('/admin')->name('admin.')->group(function () {
  Route::prefix('/stores')->name('stores.')->group(function () {
    Route::put('/increment-balance', [StoreController::class, 'incrementBalance'])->name('increment-balance');
    Route::put('/decrement-balance', [StoreController::class, 'decrementBalance'])->name('decrement-balance');
    Route::get('/', [StoreController::class, 'index'])->name('index');
    Route::get('/{product}', [StoreController::class, 'showTransactions'])->name('show-transactions');
  });
});
