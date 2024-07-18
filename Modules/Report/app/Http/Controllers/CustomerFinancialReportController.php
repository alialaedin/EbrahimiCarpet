<?php

namespace Modules\Report\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Modules\Customer\Models\Customer;
use Modules\Sale\Models\SalePayment;

class CustomerFinancialReportController extends Controller
{
  public function allCustomersFinance(): View
  {
    $customers = Customer::query()
      ->select('id', 'name', 'mobile')
      ->with([
        'sales:id,customer_id,discount',
        'sales.items:id,sale_id,quantity,price,discount',
        'payments:id,customer_id,payment_date,amount',
      ])
      ->latest('id')
      ->get();

    return view('report::customers.financial.index', compact('customers'));
  }

  public function customersFinanceFilter(): View
  {
    $customers = Customer::getAllCustomers();
    $paymentTypes = SalePayment::getAllTypes();

    return view('report::customers.financial.filter', compact(['customers', 'paymentTypes']));
  }

  public function customerFinance(Request $request): View
  {
    $customerId = $request->input('customer_id');
    $paymentType = $request->input('payment_type');
    $fromDate = $request->input('from_date');
    $toDate = $request->input('to_date') ?? today();

    $customer = Customer::query()
      ->with([
        'sales' => function ($query) use ($fromDate, $toDate) {
          return $query->select('id', 'customer_id', 'discount')
            ->whereBetween('created_at', [$fromDate, $toDate]);
        },
        'sales.items:id,sale_id,quantity,price,discount',
        'payments' => function ($query) use ($paymentType) {
          return $query->when($paymentType, fn($query) => $query->where('type', $paymentType));
        },
      ])
      ->select('id', 'name', 'mobile')
      ->findOrFail($customerId);

    $payments = $customer->payments;

    $cashPayments = $payments->where('type', SalePayment::TYPE_CASH)->isNotEmpty() ? $payments->where('type', SalePayment::TYPE_CASH)->sortByDesc('id') : null;
    $chequePayments = $payments->where('type', SalePayment::TYPE_CHEQUE)->isNotEmpty() ? $payments->where('type', SalePayment::TYPE_CHEQUE)->sortByDesc('id') : null;
    $installmentPayments = $payments->where('type', SalePayment::TYPE_INSTALLMENT)->isNotEmpty() ? $payments->where('type', SalePayment::TYPE_INSTALLMENT)->sortByDesc('id') : null;

    return view('report::customers.financial.show', compact([
      'customer',
      'cashPayments',
      'chequePayments',
      'installmentPayments'
    ]));
  }

  public function customerPaymentsFilter(): View
  {
    $customers = Customer::getAllCustomers();
    $paymentTypes = SalePayment::getAllTypes();

    return view('report::customers.payments.filter', compact(['customers', 'paymentTypes']));
  }

  public function customerPayments(Request $request): View
  {
    $customerId = $request->input('customer_id');
    $paymentType = $request->input('payment_type');
    $fromDate = $request->input('from_date');
    $toDate = $request->input('to_date') ?? now();

    $payments = SalePayment::query()
      ->when($paymentType, fn($query) => $query->where('type', $paymentType))
      ->where('customer_id', $customerId)
      ->whereBetween('created_at', [$fromDate, $toDate])
      ->latest('id')
      ->orderBy('type')
      ->get();

    $customer = Customer::query()->findOrFail($customerId);

    return view('report::customers.payments.index', compact(['payments', 'customer']));
  }
}
