<?php

use Illuminate\Support\Facades\Route;
use Modules\Report\Http\Controllers\PurchaseReportController;
use Modules\Report\Http\Controllers\SaleReportController;
use Modules\Report\Http\Controllers\CustomerIndebtednessController;
use Modules\Report\Http\Controllers\AccountingReportController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware('auth')->prefix('/admin')->name('admin.')->group(function () {
  Route::prefix('/reports')->name('reports.')->group(function () {

    Route::get('/purchases', [PurchaseReportController::class, 'filter'])->name('purchases-filter');
    Route::post('/purchases', [PurchaseReportController::class, 'list'])->name('purchases-list');

    Route::get('/sales', [SaleReportController::class, 'filter'])->name('sales-filter');
    Route::post('/sales', [SaleReportController::class, 'list'])->name('sales-list');

    Route::get('/revenues', [AccountingReportController::class, 'revenues'])->name('revenues');
    Route::get('/expenses', [AccountingReportController::class, 'expenses'])->name('expenses');

    Route::get('/customer-indebtedness', [CustomerIndebtednessController::class, 'list'])->name('customer-indebtedness');

  });
});
