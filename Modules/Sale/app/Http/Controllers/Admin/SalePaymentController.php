<?php

namespace Modules\Sale\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\Customer\Models\Customer;
use Modules\Sale\Http\Requests\Admin\SalePayment\SalePaymentStoreRequest;
use Modules\Sale\Http\Requests\Admin\SalePayment\SalePaymentUpdateRequest;
use Modules\Sale\Models\SalePayment;

class SalePaymentController extends Controller implements HasMiddleware
{
  public static function middleware(): array
  {
    return [
      new Middleware('can:view sale_payments', ['index']),
      new Middleware('can:create sale_payments', ['create', 'store']),
      new Middleware('can:edit sale_payments', ['edit', 'update']),
      new Middleware('can:delete sale_payments', ['destroy']),
    ];
  }

  public function index(): View
  {
    $customerId = \request('customer_id');
    $type = \request('type');
    $status = \request('status');
    $fromPaymentDate = \request('from_payment_date');
    $toPaymentDate = \request('to_payment_date');
    $fromDueDate = \request('from_due_date');
    $toDueDate = \request('to_due_date');

    $payments = SalePayment::query()
      ->when($customerId, fn(Builder $query) => $query->where('supplier_id', $customerId))
      ->when($type, fn(Builder $query) => $query->where('type', $type))
      ->when(isset($status), fn(Builder $query) => $query->where('status', $status))
      ->when($fromPaymentDate, fn(Builder $query) => $query->whereDate('payment_date', '>=', $fromPaymentDate))
      ->when($toPaymentDate, fn(Builder $query) => $query->whereDate('payment_date', '<=', $toPaymentDate))
      ->when($fromDueDate, fn(Builder $query) => $query->whereDate('due_date', '>=', $fromDueDate))
      ->when($toDueDate, fn(Builder $query) => $query->whereDate('due_date', '<=', $toDueDate))
      ->with('customer:id,name')
      ->latest('id')
      ->paginate()
      ->withQueryString();

    $totalPayments = $payments->total();
    $customers = Customer::all('id', 'name', 'mobile');

    $cashPayments = $payments->where('type', '=', 'cash');
    $installmentPayments = $payments->where('type', '=', 'installment');
    $chequePayments = $payments->where('type', '=', 'cheque');

    return view('sale::sale-payment.index', compact(['payments', 'totalPayments', 'customers', 'installmentPayments', 'cashPayments', 'chequePayments']));
  }

  public function show(Customer $customer): View
  {
    $salePayments = SalePayment::query()
      ->where('customer_id', $customer->id)
      ->latest('id')
      ->get();

    $cashPayments = $salePayments->where('type', '=','cash');
    $installmentPayments = $salePayments->where('type', '=','installment');
    $chequePayments = $salePayments->where('type', '=','cheque');

    return view('sale::sale-payment.show', compact(['customer', 'salePayments', 'installmentPayments', 'cashPayments', 'chequePayments']));
  }

  public function create(Customer $customer): View
  {
    return view('sale::sale-payment.create', compact('customer'));
  }

  public function store(SalePaymentStoreRequest $request): RedirectResponse
  {
    $payType = $request->input('type');
    $customer = $request->input('customer');
    $inputs = [];

    if ($request->hasFile('image') && $request->file('image')->isValid()) {
      $inputs['image'] = $request->file('image')->store('images/sale-payments', 'public');
    }

    if ($payType === SalePayment::TYPE_CASH) {
      $inputs['type'] = SalePayment::TYPE_CASH;
      $inputs['customer_id'] = $customer->id;
      $inputs['amount'] = $request->input('cash_amount');
      $inputs['payment_date'] = $request->input('cash_payment_date');
      $inputs['created_at'] = now();
      $inputs['updated_at'] = now();
    }

    elseif ($payType === SalePayment::TYPE_CHEQUE) {
      $inputs['type'] = SalePayment::TYPE_CHEQUE;
      $inputs['customer_id'] = $customer->id;
      $inputs['amount'] = $request->input('cheque_amount');
      $inputs['cheque_serial'] = $request->input('cheque_serial');
      $inputs['cheque_holder'] = $request->input('cheque_holder');
      $inputs['bank_name'] = $request->input('bank_name');
      $inputs['pay_to'] = $request->input('pay_to');
      $inputs['due_date'] = $request->input('cheque_due_date');
      $inputs['is_mine'] = $request->input('is_mine');
      $inputs['status'] = 0;
      $inputs['created_at'] = now();
      $inputs['updated_at'] = now();
    }

    elseif ($payType === SalePayment::TYPE_INSTALLMENT) {
      $payDate = Carbon::parse($request->input('installment_start_date'));
      for ($i = 1; $i <= $request->input('number_of_installments'); $i++) {

        $inputs[] = [
          'type' => SalePayment::TYPE_INSTALLMENT,
          'customer_id' => $customer->id,
          'status' => 0,
          'amount' => $request->input('installment_amount'),
          'due_date' => $payDate->copy()->toDateString(),
          'created_at' => now(),
          'updated_at' => now(),
        ];

        $payDate->addMonth();
      }
    }
    DB::table('sale_payments')->insert($inputs);
    toastr()->success('پرداختی جدید با موفقیت ثبت شد.');

    return to_route('admin.sale-payments.show', $customer);
  }

  public function edit(SalePayment $salePayment): View
  {
    return view('sale::sale-payment.edit', compact('salePayment'));
  }

  public function update(SalePaymentUpdateRequest $request, SalePayment $salePayment): RedirectResponse
  {
    $salePayment->update($request->all());
    toastr()->success("پرداختی با موفقیت بروزرسانی شد.");

    return to_route('admin.sale-payments.show', $salePayment->customer);
  }

  public function destroy(SalePayment $salePayment): RedirectResponse
  {
    if ($salePayment->image) {
      Storage::disk('public')->delete($salePayment->image);
    }
    $salePayment->delete();
    toastr()->success("پرداختی با موفقیت حذف شد.");

    return redirect()->back();
  }

  public function destroyImage(SalePayment $salePayment): RedirectResponse
  {
    Storage::disk('public')->delete($salePayment->image);
    $salePayment->image = null;
    $salePayment->save();
    toastr()->success("عکس با موفقیت حذف شد.");

    return redirect()->back();
  }

}
