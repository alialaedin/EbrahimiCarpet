<?php

use Illuminate\Support\Facades\Route;
use Modules\Report\Http\Controllers\ReportController;
use Modules\Report\Http\Controllers\AccountingReportController;
use Modules\Report\Http\Controllers\OrderReportController;
use Modules\Report\Http\Controllers\SupplierFinancialReportController;
use Modules\Report\Http\Controllers\CustomerFinancialReportController;
use Modules\Report\Http\Controllers\ProfitController;

Route::middleware('auth')->prefix('/admin')->name('admin.')->group(function () {
  Route::prefix('/reports')->name('reports.')->group(function () {

    Route::get('/', [ReportController::class, 'index'])->name('index');

    Route::get('/purchases', [OrderReportController::class, 'purchasesFilter'])->name('purchases-filter');
    Route::post('/purchases', [OrderReportController::class, 'purchases'])->name('purchases-list');

    Route::get('/sales', [OrderReportController::class, 'salesFilter'])->name('sales-filter');
    Route::post('/sales', [OrderReportController::class, 'sales'])->name('sales-list');

    Route::get('/suppliers-finance', [SupplierFinancialReportController::class, 'allSuppliersFinance'])->name('all-suppliers-finance');
    Route::get('/supplier-finance-filter', [SupplierFinancialReportController::class, 'suppliersFinanceFilter'])->name('suppliers-finance-filter');
    Route::post('/supplier-finance', [SupplierFinancialReportController::class, 'supplierFinance'])->name('supplier-finance');
    Route::get('/supplier-payments-filter', [SupplierFinancialReportController::class, 'supplierPaymentsFilter'])->name('supplier-payments-filter');
    Route::post('/supplier-payments', [SupplierFinancialReportController::class, 'supplierPayments'])->name('supplier-payments');

    Route::get('/customers-finance', [CustomerFinancialReportController::class, 'allCustomersFinance'])->name('all-customers-finance');
    Route::get('/customer-finance-filter', [CustomerFinancialReportController::class, 'customersFinanceFilter'])->name('customers-finance-filter');
    Route::post('/customer-finance', [CustomerFinancialReportController::class, 'customerFinance'])->name('customer-finance');
    Route::get('/customer-payments-filter', [CustomerFinancialReportController::class, 'customerPaymentsFilter'])->name('customer-payments-filter');
    Route::post('/customer-payments', [CustomerFinancialReportController::class, 'customerPayments'])->name('customer-payments');

    Route::get('/revenues', [AccountingReportController::class, 'revenues'])->name('revenues');
    Route::get('/expenses', [AccountingReportController::class, 'expenses'])->name('expenses');
    Route::get('/salaries', [AccountingReportController::class, 'salaries'])->name('salaries');

    Route::get('/profit', [ProfitController::class, 'index'])->name('profit');
  });
});
