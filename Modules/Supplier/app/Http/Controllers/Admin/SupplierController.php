<?php

namespace Modules\Supplier\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Modules\Payment\Models\Payment;
use Modules\Supplier\Http\Requests\Admin\SupplierStoreRequest;
use Modules\Supplier\Http\Requests\Admin\SupplierUpdateRequest;
use Modules\Supplier\Models\Supplier;

class SupplierController extends Controller implements HasMiddleware
{
	public static function middleware()
	{
		return [
			new Middleware('can:view suppliers', ['index', 'show']),
			new Middleware('can:create suppliers', ['create', 'store']),
			new Middleware('can:edit suppliers', ['edit', 'update']),
			new Middleware('can:delete suppliers', ['destroy']),
		];
	}

	public function index()
	{
		$name = request('name');
		$mobile = request('mobile');
		$status = request('status');

		$suppliers = Supplier::query()
			->select('id', 'name', 'mobile', 'status', 'created_at')
			->when($name, fn (Builder $query) => $query->where('name', 'like', "%{$name}%"))
			->when($mobile, fn (Builder $query) => $query->where('mobile', $mobile))
			->when(isset($status), fn (Builder $query) => $query->where('status', $status))
			->latest('id')
			->paginate(15)
			->withQueryString();

		$totalSuppliers =  $suppliers->total();

		return view('supplier::index', compact('suppliers', 'totalSuppliers'));
	}

	public function show(Supplier $supplier)
	{
		$numberOfPurchases = $supplier->purchases->count();
		$numberOfPayments = $supplier->payments->count();

		$payments = Payment::query()->where('supplier_id', $supplier->id)->latest('id')->get();

		return view('supplier::show', compact(
			'supplier',
			'numberOfPurchases',
			'numberOfPayments',
			'payments',
		));
	}

	public function create()
	{
		return view('supplier::create');
	}

	public function store(SupplierStoreRequest $request)
	{
		$supplier = Supplier::create($request->validated());
		toastr()->success("تامین کننده جدید به نام {$supplier->name} با موفقیت ساخته شد.");

		return to_route('admin.suppliers.index');
	}

	public function edit(Supplier $supplier)
	{
		return view('supplier::edit', compact('supplier'));
	}

	public function update(SupplierUpdateRequest $request, Supplier $supplier)
	{
		$supplier->update($request->validated());
		toastr()->success("تامین کننده با نام {$supplier->name} با موفقیت ویرایش شد.");

		return redirect()->back()->withInput();
	}

	public function destroy(Supplier $supplier)
	{
		$supplier->delete();
		toastr()->success("تامین کننده با نام {$supplier->name} با موفقیت حذف شد.");

		return redirect()->back();
	}
}
