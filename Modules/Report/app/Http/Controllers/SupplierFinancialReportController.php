<?php

namespace Modules\Report\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Modules\Payment\Models\Payment;
use Modules\Supplier\Models\Supplier;

class SupplierFinancialReportController extends Controller
{
   public function allSuppliersFinance(): View
   {
     $suppliers = Supplier::query()
       ->select('id', 'name', 'mobile')
       ->with([
         'purchases:id,supplier_id,discount',
         'purchases.items:id,purchase_id,quantity,price,discount',
         'payments:id,supplier_id,payment_date,amount',
       ])
       ->latest('id')
       ->get();

     return view('report::suppliers.financial.index', compact('suppliers'));
   }

   public function suppliersFinanceFilter(): View
   {
     $suppliers = Supplier::query()->select('id', 'name', 'mobile')->latest('id')->get();
     $paymentTypes = Payment::getAllTypes();

     return view('report::suppliers.financial.filter', compact(['suppliers', 'paymentTypes']));
   }

   public function supplierFinance(Request $request): View
   {
     $supplierId = $request->input('supplier_id');
     $paymentType = $request->input('payment_type');
     $fromDate = $request->input('from_date');
     $toDate = $request->input('to_date') ?? today();

     $supplier = Supplier::query()
       ->with([
         'purchases' => function ($query) use ($fromDate, $toDate) {
           return $query->select('id', 'supplier_id', 'discount')
             ->whereBetween('created_at', [$fromDate, $toDate]);
         },
         'purchases.items:id,purchase_id,quantity,price,discount',
         'payments' => function ($query) use ($paymentType) {
           return $query->select('id', 'supplier_id', 'payment_date', 'amount', 'due_date', 'status', 'type')
             ->when($paymentType, fn ($query) => $query->where('type', $paymentType));
         },
       ])
       ->select('id', 'name', 'mobile')
       ->findOrFail($supplierId);

     $payments = $supplier->payments;

     $cashPaymentsQuery = clone $payments;
     $chequePaymentsQuery = clone $payments;
     $installmentPaymentsQuery = clone $payments;

     $cashPayments = $cashPaymentsQuery->where('type', Payment::TYPE_CASH)->isNotEmpty() ? $payments->where('type', Payment::TYPE_CASH)->latest('id')->get() : null;
     $chequePayments = $chequePaymentsQuery->where('type', Payment::TYPE_CHEQUE)->isNotEmpty() ? $payments->where('type', Payment::TYPE_CHEQUE)->latest('id')->get() : null;
     $installmentPayments = $installmentPaymentsQuery->where('type', Payment::TYPE_INSTALLMENT)->isNotEmpty() ? $payments->where('type', Payment::TYPE_INSTALLMENT)->latest('id')->get() : null;

     return view('report::suppliers.financial.show', compact([
       'supplier',
       'paymentType',
       'cashPayments',
       'chequePayments',
       'installmentPayments'
     ]));
   }
}
