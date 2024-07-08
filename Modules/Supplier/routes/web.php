<?php

use Illuminate\Support\Facades\Route;
use Modules\Supplier\Http\Controllers\Admin\SupplierController;
use Modules\Supplier\Http\Controllers\Admin\AccountController;

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
    Route::resource('/suppliers', SupplierController::class);
    Route::apiResource('/accounts', AccountController::class);
});
