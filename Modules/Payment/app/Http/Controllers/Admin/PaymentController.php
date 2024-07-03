<?php

namespace Modules\Payment\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
use Modules\Purchase\Models\Purchase;
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
    $payments = Payment::query()
      ->select('id', 'supplier_id', 'amount', 'type', 'image', 'payment_date', 'due_date', 'status')
      ->with('supplier:id,name')
      ->latest('id')
      ->paginate()
      ->withQueryString();

    $totalPayments = $payments->total();

    return view('payment::index', compact(['payments', 'totalPayments']));
  }

	public function show(Supplier $supplier): View|Application|Factory|App
  {
		$payments = Payment::query()->where('supplier_id', $supplier->id)->latest('id')->get();

    $cashPayments = $payments->where('type', '=','cash');
    $installmentPayments = $payments->where('type', '=','installment');
    $chequePayments = $payments->where('type', '=','cheque');

		return view('payment::show', compact(['supplier', 'installmentPayments', 'cashPayments', 'chequePayments']));
	}

	public function create(Supplier $supplier): View|Application|Factory|App
  {
		return view('payment::create', compact('supplier'));
	}

	public function store(PaymentStoreRequest $request): RedirectResponse
  {
		$inputs = $this->getFormInputs($request);

		if ($request->hasFile('image') && $request->file('image')->isValid()) {
			$inputs['image'] = $request->file('image')->store('images/payments', 'public');
		}

		$supplier = $request->input('supplier');
		$supplier->payments()->create($inputs);
		toastr()->success('پرداختی جدید با موفقیت ثبت شد.');

		return to_route('admin.payments.index', $supplier);
	}

	public function edit(Payment $payment): View|Application|Factory|App
  {
		return view('payment::edit', compact('payment'));
	}

	public function update(PaymentUpdateRequest $request, Payment $payment): RedirectResponse
  {
		$inputs = $this->getFormInputs($request);

		if ($request->hasFile('image') && $request->file('image')->isValid()) {
			if (!is_null($payment->image)) {
				Storage::delete($payment->image);
			}
			$inputs['image'] = $request->file('image')->store('images/payments', 'public');
		}

		$payment->update($inputs);
		toastr()->success("پرداختی با موفقیت بروزرسانی شد.");

		return to_route('admin.payments.index', $payment->supplier);
	}

	public function destroy(Payment $payment): RedirectResponse
  {
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
