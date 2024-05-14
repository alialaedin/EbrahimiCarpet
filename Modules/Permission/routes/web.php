<?php

use Illuminate\Support\Facades\Route;
use Modules\Permission\App\Http\Controllers\Admin\RoleController;

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

Route::prefix('admin/roles')->name('admin.roles.')->middleware('auth:admin-api')->group(function () {
	Route::get('/', [RoleController::class, 'index'])->middleware('can:view roles')->name('index');
	Route::get('/create', [RoleController::class, 'create'])->middleware('can:create roles')->name('create');
	Route::post('/', [RoleController::class, 'store'])->middleware('can:create roles')->name('store');
	Route::post('/{role}/edit', [RoleController::class, 'edit'])->middleware('can:edit roles')->name('edit');
	Route::patch('/{role}', [RoleController::class, 'update'])->middleware('can:edit roles')->name('update');
	Route::delete('/{role}', [RoleController::class, 'destory'])->middleware('can:delete roles')->name('destroy');
});

// Route::name('admin.roles')->resource('admin/roles', RoleController::class)->except('show');