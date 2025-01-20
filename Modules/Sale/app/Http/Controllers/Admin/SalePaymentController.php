<?php

namespace Modules\Sale\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Hekmatinasser\Verta\Facades\Verta;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
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

  public function index(): View
  {
    $payments = SalePayment::query()
      ->filters()
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
    $salePayments = $customer->payments()->latest('id');

    $cashPayments = $salePayments->cashes()->get();
    $installmentPayments = $salePayments->installments()->get();
    $chequePayments = $salePayments->cheques()->get();

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
    } elseif ($payType === SalePayment::TYPE_CHEQUE) {
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
    } elseif ($payType === SalePayment::TYPE_INSTALLMENT) {

      $shamsiFirstPayDate = verta($request->input('installment_start_date'));
      $jalaliDateArr = explode('-', $shamsiFirstPayDate->copy()->formatDate());

      $year = $jalaliDateArr[0];
      $month = $jalaliDateArr[1];
      $day = $jalaliDateArr[2];

      for ($i = 1; $i <= $request->input('number_of_installments'); $i++) {

        $payDate = implode('-', Verta::jalaliToGregorian($year, $month, $day));

        $inputs[] = [
          'type' => SalePayment::TYPE_INSTALLMENT,
          'customer_id' => $customer->id,
          'status' => 0,
          'amount' => $request->input('installment_amount'),
          'due_date' => $payDate,
          'created_at' => now(),
          'updated_at' => now(),
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

    SalePayment::insert($inputs);
    toastr()->success('پرداختی جدید با موفقیت ثبت شد.');

    return to_route('admin.customers.show', $customer);
  }

  public function edit(SalePayment $salePayment): View
  {
    return view('sale::sale-payment.edit', compact('salePayment'));
  }

  public function update(SalePaymentUpdateRequest $request, SalePayment $salePayment): RedirectResponse
  {
    $salePayment->update($request->all());
    toastr()->success("پرداختی با موفقیت بروزرسانی شد.");

    return redirect()->back();
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

  public function cheques()
  {
    $chequePayments = $this->getPaymentsWithType(SalePayment::TYPE_CHEQUE);
    $customers = Customer::getAllCustomers();

    return view('sale::sale-payment.cheques', compact('chequePayments', 'customers'));
  }

  public function installments()
  {
    $installmentPayments = $this->getPaymentsWithType(SalePayment::TYPE_INSTALLMENT);
    $customers = Customer::getAllCustomers();

    return view('sale::sale-payment.installments', compact('installmentPayments', 'customers'));
  }

  public function cashes()
  {
    $cashPayments = $this->getPaymentsWithType(SalePayment::TYPE_CASH);
    $customers = Customer::getAllCustomers();

    return view('sale::sale-payment.cashes', compact('cashPayments', 'customers'));
  }

  private function getPaymentsWithType($type)
  {
    $paymentsQuery = SalePayment::query();

    switch ($type) {
      case SalePayment::TYPE_CHEQUE:
        $paymentsQuery->cheques();
        break;
      case SalePayment::TYPE_INSTALLMENT:
        $paymentsQuery->installments();
        break;
      case SalePayment::TYPE_CASH:
        $paymentsQuery->cashes();
        break;
      default:
        toastr()->error('نوع پرداخت درخواستی اشتباه است');
        return redirect()->back();
    }

    $payments = $paymentsQuery
      ->filters()
      ->with('customer:id,name')
      ->latest('id')
      ->paginate()
      ->withQueryString();

    return $payments;
  }

}
