<?php

use Illuminate\Support\Facades\Route;
use Modules\Accounting\Http\Controllers\Admin\HeadlineController;
use Modules\Accounting\Http\Controllers\Admin\ExpenseController;
use Modules\Accounting\Http\Controllers\Admin\RevenueController;
use Modules\Accounting\Http\Controllers\Admin\SalaryController;
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

Route::middleware('auth')->prefix('/admin')->name('admin.')->group(function() {

  Route::apiResource('/headlines', HeadlineController::class)->except('show');
  Route::resource('/expenses', ExpenseController::class)->except('show');
  Route::resource('/revenues', RevenueController::class)->except('show');
  Route::resource('/salaries', SalaryController::class);

  Route::post('/salaries/get-employee-salary', [SalaryController::class, 'getEmployeeSalary'])
    ->name('salaries.get-employee-salary');

  Route::delete('/salaries/image', [SalaryController::class, 'destroyImage'])->name('salaries.image.destroy');

});
