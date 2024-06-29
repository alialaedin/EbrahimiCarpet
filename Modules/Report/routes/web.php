<?php

use Illuminate\Support\Facades\Route;
use Modules\Report\Http\Controllers\PurchaseReportController;
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

    // Purchases Report
    Route::get('/purchases', [PurchaseReportController::class, 'filter'])->name('purchases-filter');
    Route::post('/purchases', [PurchaseReportController::class, 'list'])->name('purchases-list');

  });
});
