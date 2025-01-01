<?php

namespace Modules\Payment\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Hekmatinasser\Verta\Facades\Verta;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
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
      new Middleware('can:view payments', ['index', 'cheques']),
      new Middleware('can:create payments', ['create', 'store']),
      new Middleware('can:edit payments', ['edit', 'update']),
      new Middleware('can:delete payments', ['destroy', 'destroyImage']),
    ];
  }

  public function index(): View|Application|Factory|App
  {
    $payments = Payment::query()
      ->filters()
      ->with('supplier:id,name')
      ->latest('id')
      ->paginate()
      ->withQueryString();

    $totalPayments = $payments->total();
    $suppliers = Supplier::getAllSuppliers();

    $cashPayments = $payments->where('type', '=', 'cash')->take(20);
    $installmentPayments = $payments->where('type', '=', 'installment')->take(20);
    $chequePayments = $payments->where('type', '=', 'cheque')->take(20);

    return view('payment::index', compact(['payments', 'totalPayments', 'suppliers', 'installmentPayments', 'cashPayments', 'chequePayments']));
  }

  public function show(Supplier $supplier): View|Application|Factory|App
  {
    $payments = Payment::query()->where('supplier_id', $supplier->id)->latest('id')->get();
    $supplier->loadCount(['purchases', 'payments']);
    $cashPayments = $payments->where('type', '=', 'cash');
    $installmentPayments = $payments->where('type', '=', 'installment');
    $chequePayments = $payments->where('type', '=', 'cheque');

    return view('payment::show', compact(['supplier', 'payments', 'installmentPayments', 'cashPayments', 'chequePayments']));
  }

  public function create(Supplier $supplier): View|Application|Factory|App
  {
    $supplier->loadCount(['purchases', 'payments']);
    return view('payment::create', compact('supplier'));
  }

  public function store(PaymentStoreRequest $request): RedirectResponse
  {
    $payType = $request->input('type');
    $supplier = $request->input('supplier');
    $inputs = [];

    if ($request->hasFile('image') && $request->file('image')->isValid()) {
      $inputs['image'] = $request->file('image')->store('images/payments', 'public');
    }

    if ($payType === Payment::TYPE_CASH) {
      $inputs['type'] = Payment::TYPE_CASH;
      $inputs['supplier_id'] = $supplier->id;
      $inputs['amount'] = $request->input('cash_amount');
      $inputs['payment_date'] = $request->input('cash_payment_date');
      $inputs['created_at'] = now();
      $inputs['updated_at'] = now();
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
      $inputs['created_at'] = now();
      $inputs['updated_at'] = now();
    } elseif ($payType === Payment::TYPE_INSTALLMENT) {

      $shamsiFirstPayDate = verta($request->input('installment_start_date'));
      $jalaliDateArr = explode('-', $shamsiFirstPayDate->copy()->formatDate());

      $year = $jalaliDateArr[0];
      $month = $jalaliDateArr[1];
      $day = $jalaliDateArr[2];

      for ($i = 1; $i <= $request->input('number_of_installments'); $i++) {

        $payDate = implode('-', Verta::jalaliToGregorian($year, $month, $day));

        $inputs[] = [
          'type' => Payment::TYPE_INSTALLMENT,
          'supplier_id' => $supplier->id,
          'status' => 0,
          'amount' => $request->input('installment_amount'),
          'due_date' => $payDate,
          'created_at' => now(),
          'updated_at' => now()
        ];

        $month = (int) $month + 1;

        if ($month > 12) {
          $month = 1;
          $year += 1;
        }

        if ($month < 10) {
          $month = '0' . $month;
        }

        if ($day == 31) {
          if (in_array($month, ['07', '08', '09', '10', '11'])) {
            $newDay = 30;
          } elseif ($month == '12') {
            $newDay = 29;
          } else {
            $newDay = 31;
          }
        }

        if ($day == 30) {
          $newDay = ($month == '12') ? 29 : 30; 
        }

        $day = $newDay ?? $day;
      }
    }

    Payment::insert($inputs);
    toastr()->success('پرداختی جدید با موفقیت ثبت شد.');

    return to_route('admin.payments.show', $supplier);
  }

  public function edit(Payment $payment): View|Application|Factory|App
  {
    return view('payment::edit', compact('payment'));
  }

  public function update(PaymentUpdateRequest $request, Payment $payment): RedirectResponse
  {
    $payment->update($request->all());
    toastr()->success("پرداختی با موفقیت بروزرسانی شد.");

    return redirect()->back();
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

  public function cheques()
  {
    $chequePayments = $this->getPaymentsWithType(Payment::TYPE_CHEQUE);
    $suppliers = Supplier::getAllSuppliers();

    return view('payment::cheques', compact('chequePayments', 'suppliers'));
  }

  public function installments()
  {
    $installmentPayments = $this->getPaymentsWithType(Payment::TYPE_INSTALLMENT);
    $suppliers = Supplier::getAllSuppliers();

    return view('payment::installments', compact('installmentPayments', 'suppliers'));
  }

  public function cashes()
  {
    $cashPayments = $this->getPaymentsWithType(Payment::TYPE_CASH);
    $suppliers = Supplier::getAllSuppliers();

    return view('payment::cashes', compact('cashPayments', 'suppliers'));
  }

  private function getPaymentsWithType($type)
  {
    $paymentsQuery = Payment::query();

    switch ($type) {
      case Payment::TYPE_CHEQUE:
        $paymentsQuery->cheques();
        break;
      case Payment::TYPE_INSTALLMENT:
        $paymentsQuery->installments();
        break;
      case Payment::TYPE_CASH:
        $paymentsQuery->cashes();
        break;
      default:
        toastr()->error('نوع پرداخت درخواستی اشتباه است');
        return redirect()->back();
    }

    $payments = $paymentsQuery
      ->filters()
      ->with('supplier:id,name')
      ->latest('id')
      ->paginate()
      ->withQueryString();

    return $payments;
  }

}
