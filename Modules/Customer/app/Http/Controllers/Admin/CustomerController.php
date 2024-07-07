<?php

namespace Modules\Customer\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Modules\Customer\Http\Requests\Admin\CustomerStoreRequest;
use Modules\Customer\Http\Requests\Admin\CustomerUpdateRequest;
use Modules\Customer\Models\Customer;

class CustomerController extends Controller implements HasMiddleware
{
	public static function middleware(): array
  {
		return [
			new Middleware('can:view customers', ['index', 'show']),
			new Middleware('can:create customers', ['create', 'store']),
			new Middleware('can:edit customers', ['edit', 'update']),
			new Middleware('can:delete customers', ['destroy']),
		];
	}
	public function index(): View
	{
		$fullName = request('full_name');
		$telephone = request('telephone');
		$mobile = request('mobile');
		$status = request('status');

		$customers = Customer::query()
			->select('id', 'name', 'mobile', 'telephone', 'status', 'gender', 'birthday')
			->when($fullName, fn (Builder $query) => $query->where('name', 'like', "%{$fullName}%"))
			->when($telephone, fn (Builder $query) => $query->where('telephone', $telephone))
			->when($mobile, fn (Builder $query) => $query->where('mobile', $mobile))
			->when(isset($status), fn (Builder $query) => $query->where('status', $status))
			->latest('id')
			->paginate(15)
			->withQueryString();

		$customersCount = $customers->total();

		return view('customer::index', compact('customers', 'customersCount'));
	}

	public function show(Customer $customer): View
  {
    $salesCount = $customer->countSales();
    $salePaymentsCount = $customer->countPayments();

    $salePayments = $customer->payments()->latest('id')->take(5)->get();
    $sales = $customer->sales()->latest('id')->take(5)->get();

    return view('customer::show', compact([
      'customer',
      'salesCount',
      'salePaymentsCount',
      'salePayments',
      'sales'
    ]));
	}

	public function create(): View
  {
		return view('customer::create');
	}

	public function store(CustomerStoreRequest $request): RedirectResponse
  {
		$customer = Customer::query()->create($request->validated());
		toastr()->success("مشتری جدید به نام {$customer->name} با موفقیت ساخته شد.");

		return to_route('admin.customers.index');
	}

	public function edit(Customer $customer): View
  {
		return view('customer::edit', compact('customer'));
	}

	public function update(CustomerUpdateRequest $request, Customer $customer): RedirectResponse
  {
		$customer->update($request->validated());
		toastr()->success("مشتری با نام {$customer->name} با موفقیت ویرایش شد.");

		return redirect()->back()->withInput();
	}

	public function destroy(Customer $customer): RedirectResponse
  {
		$customer->delete();
		toastr()->success("مشتری با نام {$customer->name} با موفقیت حذف شد.");

		return redirect()->back();
	}

  public function showInvoice(Customer $customer): View
  {
    $customer->load([
      'payments' ,
      'sales' => fn ($query) => $query->select(['id', 'customer_id', 'discount']),
      'sales.items' => fn ($query) => $query->select(['id', 'price', 'discount', 'quantity', 'product_id', 'sale_id']),
      'sales.items.product' => fn ($query) => $query->select(['id', 'print_title', 'category_id']),
      'sales.items.product.category' => fn ($query) => $query->select(['id', 'title', 'unit_type']),
    ]);

    return view('customer::invoice.show', compact('customer'));
  }
}
