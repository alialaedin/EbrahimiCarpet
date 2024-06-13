<?php

namespace Modules\Sale\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
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

  public function index(Customer $customer): View
  {
    $salePayments = SalePayment::query()
      ->where('customer_id', $customer->id)
      ->latest('id')
      ->get();

    $cashPayments = $salePayments->where('type', '=','cash');
    $installmentPayments = $salePayments->where('type', '=','installment');
    $chequePayments = $salePayments->where('type', '=','cheque');

    return view('sale::sale-payment.index', compact(['customer', 'installmentPayments', 'cashPayments', 'chequePayments']));
  }

  public function create(Customer $customer): View
  {
    return view('sale::sale-payment.create', compact('customer'));
  }

  public function store(SalePaymentStoreRequest $request): RedirectResponse
  {
    $inputs = $this->getFormInputs($request);

    if ($request->hasFile('image') && $request->file('image')->isValid()) {
      $inputs['image'] = $request->file('image')->store('images/sale-payments', 'public');
    }

    $customer = $request->input('customer');
    $customer->payments()->create($inputs);
    toastr()->success('پرداختی جدید با موفقیت ثبت شد.');

    return to_route('admin.sale-payments.index', $customer);
  }

  public function edit(SalePayment $salePayment): View
  {
    return view('sale::sale-payment.edit', compact('salePayment'));
  }

  public function update(SalePaymentUpdateRequest $request, SalePayment $salePayment): RedirectResponse
  {
    $inputs = $this->getFormInputs($request);

    if ($request->hasFile('image') && $request->file('image')->isValid()) {
      if (!is_null($salePayment->image)) {
        Storage::delete($salePayment->image);
      }
      $inputs['image'] = $request->file('image')->store('images/sale-payments', 'public');
    }

    $salePayment->update($inputs);
    toastr()->success("پرداختی با موفقیت بروزرسانی شد.");

    return to_route('admin.sale-payments.index', $salePayment->customer);
  }

  public function destroy(SalePayment $salePayment): RedirectResponse
  {
    $salePayment->delete();
    toastr()->success("پرداختی با موفقیت حذف شد.");

    return redirect()->back();
  }

  public function destroyImage(SalePayment $salePayment): RedirectResponse
  {
    Storage::disk('public')->delete($payment->image);
    $salePayment->image = null;
    $salePayment->save();
    toastr()->success("عکس با موفقیت حذف شد.");

    return redirect()->back();
  }

  private function getFormInputs(Request $request): array
  {
    return [
      'amount' => $request->input('amount'),
      'status' => $request->input('status'),
      'type' => $request->input('type'),
      'payment_date' => $request->input('payment_date'),
      'due_date' => $request->input('due_date'),
      'description' => $request->input('description'),
    ];
  }
}
