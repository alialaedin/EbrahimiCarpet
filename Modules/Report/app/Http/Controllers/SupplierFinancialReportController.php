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
    $suppliers = Supplier::getAllSuppliers();
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
            ->when($paymentType, fn($query) => $query->where('type', $paymentType));
        },
      ])
      ->select('id', 'name', 'mobile')
      ->findOrFail($supplierId);

    $payments = $supplier->payments;

    $cashPayments = $payments->where('type', Payment::TYPE_CASH)->isNotEmpty() ? $payments->where('type', Payment::TYPE_CASH)->sortByDesc('id') : null;
    $chequePayments = $payments->where('type', Payment::TYPE_CHEQUE)->isNotEmpty() ? $payments->where('type', Payment::TYPE_CHEQUE)->sortByDesc('id') : null;
    $installmentPayments = $payments->where('type', Payment::TYPE_INSTALLMENT)->isNotEmpty() ? $payments->where('type', Payment::TYPE_INSTALLMENT)->sortByDesc('id') : null;

    return view('report::suppliers.financial.show', compact([
      'supplier',
      'paymentType',
      'cashPayments',
      'chequePayments',
      'installmentPayments'
    ]));
  }

  public function supplierPaymentsFilter(): View
  {
    $suppliers = Supplier::getAllSuppliers();
    $paymentTypes = Payment::getAllTypes();

    return view('report::suppliers.payments.filter', compact(['suppliers', 'paymentTypes']));
  }

  public function supplierPayments(Request $request): View
  {
    $supplierId = $request->input('supplier_id');
    $paymentType = $request->input('payment_type');
    $fromDate = $request->input('from_date');
    $toDate = $request->input('to_date') ?? today();

    $payments = Payment::query()
      ->select('id', 'supplier_id', 'payment_date', 'amount', 'due_date', 'status', 'type', 'created_at')
      ->whereBetween('created_at', [$fromDate, $toDate])
      ->when($paymentType, fn($query) => $query->where('type', $paymentType))
      ->where('supplier_id', $supplierId)
      ->latest('id')
//      ->orderBy('type')
      ->get();

    $supplier = Supplier::query()->findOrFail($supplierId);

    return view('report::suppliers.payments.index', compact(['payments', 'supplier']));
  }
}
