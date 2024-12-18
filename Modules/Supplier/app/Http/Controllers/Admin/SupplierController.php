<?php

namespace Modules\Supplier\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Modules\Supplier\Http\Requests\Admin\Supplier\SupplierStoreRequest;
use Modules\Supplier\Http\Requests\Admin\Supplier\SupplierUpdateRequest;
use Modules\Supplier\Models\Supplier;

class   SupplierController extends Controller implements HasMiddleware
{
	public static function middleware(): array
  {
		return [
			new Middleware('can:view suppliers', ['index', 'show']),
			new Middleware('can:create suppliers', ['create', 'store']),
			new Middleware('can:edit suppliers', ['edit', 'update']),
			new Middleware('can:delete suppliers', ['destroy']),
		];
	}

	public function index(): View
  {
		$name = request('full_name');
		$mobile = request('mobile');
		$type = request('type');
		$status = request('status');

		$suppliers = Supplier::query()
			->select('id', 'name', 'mobile', 'status', 'national_code', 'type', 'postal_code', 'created_at')
			->when($name, fn (Builder $query) => $query->where('name', 'like', "%$name%"))
			->when($mobile, fn (Builder $query) => $query->where('mobile', $mobile))
			->when($type, fn (Builder $query) => $query->where('type', $type))
			->when(isset($status), fn (Builder $query) => $query->where('status', $status))
			->latest('id')
			->paginate(15)
			->withQueryString();

		$totalSuppliers =  $suppliers->total();

		return view('supplier::supplier.index', compact('suppliers', 'totalSuppliers'));
	}

	public function show(Supplier $supplier): View
  {
		$numberOfPurchases = $supplier->purchases->count();
		$numberOfPayments = $supplier->payments->count();
		$numberOfAccounts = $supplier->accounts->count();

		$payments = $supplier->payments()->latest('id')->take(5)->get();
		$purchases = $supplier->purchases()->latest('id')->get();
    $accounts = $supplier->accounts()->latest('id')->get();

		return view('supplier::supplier.show', compact(
			'supplier',
			'numberOfPurchases',
			'numberOfPayments',
      'numberOfAccounts',
			'payments',
      'purchases',
      'accounts'
		));
	}

	public function create(): View
  {
		return view('supplier::supplier.create');
	}

	public function store(SupplierStoreRequest $request): RedirectResponse
  {
		$supplier = Supplier::create($request->validated());
		toastr()->success("تامین کننده جدید به نام $supplier->name با موفقیت ساخته شد.");

		return to_route('admin.suppliers.index');
	}

	public function edit(Supplier $supplier): View
	{
		return view('supplier::supplier.edit', compact('supplier'));
	}

	public function update(SupplierUpdateRequest $request, Supplier $supplier): RedirectResponse
  {
		$supplier->update($request->validated());
		toastr()->success("تامین کننده با نام $supplier->name با موفقیت ویرایش شد.");

		return redirect()->back()->withInput();
	}

	public function destroy(Supplier $supplier): RedirectResponse
  {
		$supplier->delete();
		toastr()->success("تامین کننده با نام $supplier->name با موفقیت حذف شد.");

		return to_route('admin.suppliers.index');
	}
}
