<?php

use Illuminate\Support\Facades\Route;
use Modules\Admin\Http\Controllers\AdminController;
use Modules\Admin\Http\Controllers\DashboardController;

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


// Route::prefix('admin/admins')->name('admin.admins.')->group(function () {
// 	Route::get('/', [AdminController::class, 'index'])->middleware('can:view admins')->name('index');
// 	Route::get('/create', [AdminController::class, 'create'])->middleware('can:create admins')->name('create');
// 	Route::get('/{admin}', [AdminController::class, 'show'])->middleware('can:view admins')->name('show');
// 	Route::post('/', [AdminController::class, 'store'])->middleware('can:create admins')->name('store');
// 	Route::post('/{admin}/edit', [AdminController::class, 'edit'])->middleware('can:edit admins')->name('edit');
// 	Route::patch('/{admin}', [AdminController::class, 'update'])->middleware('can:edit admins')->name('update');
// 	Route::delete('/{admin}', [AdminController::class, 'destory'])->middleware('can:delete admins')->name('destroy');
// });

Route::middleware('auth')->prefix('/admin')->name('admin.')->group(function() {
	Route::resource('/admins', AdminController::class);
	Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

