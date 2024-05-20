<?php

namespace Modules\Customer\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Modules\Core\Traits\BreadCrumb;
use Modules\Customer\Http\Requests\Admin\CustomerStoreRequest;
use Modules\Customer\Http\Requests\Admin\CustomerUpdateRequest;
use Modules\Customer\Models\Customer;

class CustomerController extends Controller implements HasMiddleware
{
	use BreadCrumb;
	public const MODEL = 'مشتری';
	public const TABLE = 'customers';

	public static function middleware()
	{
		return [
			new Middleware('can:view customers', ['index', 'show']),
			new Middleware('can:create customers', ['create', 'store']),
			new Middleware('can:edit customers', ['edit', 'update']),
			new Middleware('can:delete customers', ['destroy']),
		];
	}
	public function index()
	{
		$fullName = request('full_name');
		$landlinePhone = request('lanline_phone');
		$mobile = request('mobile');
		$status = request('status') !== 'all' ? request('status') : null;

		$customers = Customer::query()
			->select('id', 'name', 'mobile', 'landline_phone', 'status', 'created_at')
			->when($fullName, fn (Builder $query) => $query->where('name', 'like', "%{$fullName}%"))
			->when($landlinePhone, fn (Builder $query) => $query->where('lanline_phone', $landlinePhone))
			->when($mobile, fn (Builder $query) => $query->where('mobile', $mobile))
			->when(isset($status), fn (Builder $query) => $query->where('status', $status))
			->latest('id')
			->paginate(15)
			->withQueryString();

		$customersCount =  $customers->total();
		$breadcrumbItems = $this->breadcrumbItems('index', static::TABLE, static::MODEL);

		return view('customer::index', compact('customers', 'customersCount', 'breadcrumbItems'));
	}

	public function create()
	{
		$breadcrumbItems = $this->breadcrumbItems('create', static::TABLE, static::MODEL);

		return view('customer::create', compact('breadcrumbItems'));
	}

	public function store(CustomerStoreRequest $request)
	{
		$customer = Customer::create($request->validated());
		toastr()->success("مشتری جدید به نام {$customer->name} با موفقیت ساخته شد.");

		return to_route('admin.customers.index');
	}

	public function edit(Customer $customer)
	{
		$breadcrumbItems = $this->breadcrumbItems('edit', static::TABLE, static::MODEL);

		return view('customer::edit', compact('customer', 'breadcrumbItems'));
	}

	public function update(CustomerUpdateRequest $request, Customer $customer)
	{
		$customer->update($request->validated());
		toastr()->success("مشتری با نام {$customer->name} با موفقیت ویرایش شد.");

		return redirect()->back()->withInput();
	}

	public function destroy(Customer $customer)
	{
		$customer->delete();
		toastr()->success("مشتری با نام {$customer->name} با موفقیت حذف شد.");

		return redirect()->back();
	}
}
