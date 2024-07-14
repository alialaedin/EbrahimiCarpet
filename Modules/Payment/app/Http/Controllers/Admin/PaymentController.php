<?php

namespace Modules\Payment\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\Foundation\Application as App;
use Illuminate\Http\RedirectResponse;
use Modules\Payment\Http\Requests\PaymentStoreRequest;
use Modules\Payment\Http\Requests\PaymentUpdateRequest;
use Modules\Payment\Models\Payment;
use Modules\Supplier\Models\Supplier;

class PaymentController extends Controller implements HasMiddleware
{

  public static function middleware(): array
  {
    return [
      new Middleware('can:view payments', ['index']),
      new Middleware('can:create payments', ['create', 'store']),
      new Middleware('can:edit payments', ['edit', 'update']),
      new Middleware('can:delete payments', ['destroy']),
    ];
  }

  public function index(): View|Application|Factory|App
  {
    $supplierId = \request('supplier_id');
    $type = \request('type');
    $status = \request('status');
    $fromPaymentDate = \request('from_payment_date');
    $toPaymentDate = \request('to_payment_date');
    $fromDueDate = \request('from_due_date');
    $toDueDate = \request('to_due_date');

    $payments = Payment::query()
      ->select('id', 'supplier_id', 'amount', 'type', 'image', 'payment_date', 'due_date', 'status')
      ->when($supplierId, fn(Builder $query) => $query->where('supplier_id', $supplierId))
      ->when($type, fn(Builder $query) => $query->where('type', $type))
      ->when(isset($status), fn(Builder $query) => $query->where('status', $status))
      ->when($fromPaymentDate, fn(Builder $query) => $query->whereDate('payment_date', '>=', $fromPaymentDate))
      ->when($toPaymentDate, fn(Builder $query) => $query->whereDate('payment_date', '<=', $toPaymentDate))
      ->when($fromDueDate, fn(Builder $query) => $query->whereDate('due_date', '>=', $fromDueDate))
      ->when($toDueDate, fn(Builder $query) => $query->whereDate('due_date', '<=', $toDueDate))
      ->with('supplier:id,name')
      ->latest('id')
      ->paginate()
      ->withQueryString();

    $totalPayments = $payments->total();
    $suppliers = Supplier::all('id', 'name', 'mobile');

    return view('payment::index', compact(['payments', 'totalPayments', 'suppliers']));
  }

  public function show(Supplier $supplier): View|Application|Factory|App
  {
    $payments = Payment::query()->where('supplier_id', $supplier->id)->latest('id')->get();

    $cashPayments = $payments->where('type', '=', 'cash');
    $installmentPayments = $payments->where('type', '=', 'installment');
    $chequePayments = $payments->where('type', '=', 'cheque');

    return view('payment::show', compact(['supplier', 'payments', 'installmentPayments', 'cashPayments', 'chequePayments']));
  }

  public function create(Supplier $supplier): View|Application|Factory|App
  {
    return view('payment::create', compact('supplier'));
  }

  public function store(PaymentStoreRequest $request): RedirectResponse
  {
    $payType = $request->input('type');
    $supplier = $request->input('supplier');
    $inputs = [];

    if ($payType === Payment::TYPE_CASH) {
      if ($request->hasFile('image') && $request->file('image')->isValid()) {
        $inputs['image'] = $request->file('image')->store('images/sale-payments', 'public');
      }
      $inputs['type'] = Payment::TYPE_CASH;
      $inputs['supplier_id'] = $supplier->id;
      $inputs['amount'] = $request->input('cash_amount');
      $inputs['payment_date'] = $request->input('cash_payment_date');
    } elseif ($payType === Payment::TYPE_CHEQUE) {
      $inputs['type'] = Payment::TYPE_CHEQUE;
      $inputs['supplier_id'] = $supplier->id;
      $inputs['amount'] = $request->input('cheque_amount');
      $inputs['cheque_serial'] = $request->input('cheque_serial');
      $inputs['cheque_holder'] = $request->input('cheque_holder');
      $inputs['bank_name'] = $request->input('bank_name');
      $inputs['pay_to'] = $request->input('pay_to');
      $inputs['due_date'] = $request->input('cheque_due_date');
      $inputs['is_mine'] = $request->input('is_mine');
      $inputs['status'] = 0;
    } elseif ($payType === Payment::TYPE_INSTALLMENT) {
      $payDate = Carbon::parse($request->input('installment_start_date'));
      for ($i = 1; $i <= $request->input('number_of_installments'); $i++) {

        $inputs[] = [
          'type' => Payment::TYPE_INSTALLMENT,
          'supplier_id' => $supplier->id,
          'status' => 0,
          'amount' => $request->input('installment_amount'),
          'due_date' => $payDate->copy()->toDateString()
        ];

        $payDate->addMonth();
      }
    }
    DB::table('payments')->insert($inputs);
    toastr()->success('پرداختی جدید با موفقیت ثبت شد.');

    return to_route('admin.payments.show', $supplier);
  }

  public function edit(Payment $payment): View|Application|Factory|App
  {
    return view('payment::edit', compact('payment'));
  }

  public function update(PaymentUpdateRequest $request, Payment $payment): RedirectResponse
  {
    $payment->update($request->validated());
    toastr()->success("پرداختی با موفقیت بروزرسانی شد.");

    return to_route('admin.payments.index', $payment->supplier);
  }

  public function destroy(Payment $payment): RedirectResponse
  {
    if ($payment->image) {
      Storage::disk('public')->delete($payment->image);
    }
    $payment->delete();
    toastr()->success("پرداختی با موفقیت حذف شد.");

    return redirect()->back();
  }

  public function destroyImage(Payment $payment): RedirectResponse
  {
    Storage::disk('public')->delete($payment->image);
    $payment->image = null;
    $payment->save();
    toastr()->success("عکس با موفقیت حذف شد.");

    return redirect()->back();
  }

}
