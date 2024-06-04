<?php

namespace Modules\Supplier\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Modules\Core\Traits\BreadCrumb;
use Modules\Supplier\Http\Requests\Admin\SupplierStoreRequest;
use Modules\Supplier\Http\Requests\Admin\SupplierUpdateRequest;
use Modules\Supplier\Models\Supplier;

class SupplierController extends Controller implements HasMiddleware
{
	use BreadCrumb;
	public const MODEL = 'تامین کننده';
	public const TABLE = 'suppliers';

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
		$breadcrumbItems = $this->breadcrumbItems('index', static::TABLE, static::MODEL);

		return view('supplier::index', compact('suppliers', 'totalSuppliers', 'breadcrumbItems'));
	}

	public function show(Supplier $supplier)
	{
    return 'Employee Show';
		$breadcrumbItems = $this->breadcrumbItems('show', static::TABLE, static::MODEL);

		return view('supplier::show', compact('supplier', 'breadcrumbItems'));
	}

	public function create()
	{
		$breadcrumbItems = $this->breadcrumbItems('create', static::TABLE, static::MODEL);

		return view('supplier::create', compact('breadcrumbItems'));
	}

	public function store(SupplierStoreRequest $request)
	{
		$supplier = Supplier::create($request->validated());
		toastr()->success("تامین کننده جدید به نام {$supplier->name} با موفقیت ساخته شد.");

		return to_route('admin.suppliers.index');
	}

	public function edit(Supplier $supplier)
	{
		$breadcrumbItems = $this->breadcrumbItems('edit', static::TABLE, static::MODEL);

		return view('supplier::edit', compact('supplier', 'breadcrumbItems'));
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
