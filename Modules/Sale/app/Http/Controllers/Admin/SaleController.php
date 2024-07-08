<?php

namespace Modules\Sale\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Modules\Customer\Models\Customer;
use Modules\Employee\Models\Employee;
use Modules\Product\Models\Category;
use Modules\Product\Models\Product;
use Modules\Sale\Http\Requests\Admin\Sale\SaleStoreRequest;
use Modules\Sale\Http\Requests\Admin\Sale\SaleUpdateRequest;
use Modules\Sale\Models\Sale;
use Modules\Sale\Models\SaleItem;

class SaleController extends Controller implements HasMiddleware
{
  public static function middleware(): array
  {
    return [
      new Middleware('can:view sales', ['index']),
      new Middleware('can:view sale_items', ['show']),
      new Middleware('can:create sales', ['create', 'store']),
      new Middleware('can:edit sales', ['edit', 'update']),
      new Middleware('can:delete sales', ['destroy']),
    ];
  }

  public function index(): View
  {
    $customerId = request('customer_id');
    $fromSoldAt = request('from_sold_at');
    $toSoldAt = request('to_sold_at');

    $sales = Sale::query()
      ->select('id', 'customer_id', 'sold_at', 'discount', 'employee_id', 'discount_for')
      ->with([
        'customer:id,name,mobile',
        'employee:id,name,mobile'
      ])
      ->when($customerId, fn(Builder $query) => $query->where('customer_id', $customerId))
      ->when($fromSoldAt, fn(Builder $query) => $query->whereDate('sold_at', '>=', $fromSoldAt))
      ->when($toSoldAt, fn(Builder $query) => $query->whereDate('sold_at', '<=', $toSoldAt))
      ->latest('id')
      ->paginate()
      ->withQueryString();

    $customers = $this->getCustomers();
    $salesCount = $sales->total();

    return view('sale::sale.index', compact(['sales', 'customers', 'salesCount']));
  }

  public function create(): View
  {
    $customers = $this->getCustomers();
    $employees = $this->getEmployees();
    $categories = $this->getCategories();

    return view('sale::sale.create', compact(['customers', 'categories', 'employees']));
  }

  public function store(SaleStoreRequest $request): RedirectResponse
  {
    $sale = Sale::query()->create(
      $request->only(
        'customer_id', 'sold_at', 'discount', 'employee_id', 'discount_for'
      )
    );

    foreach ($request->input('products') as $product) {

      $thisProduct = Product::query()->find($product['id']);

      SaleItem::query()->create([
        'sale_id' => $sale->id,
        'product_id' => $product['id'],
        'quantity' => $product['quantity'],
        'discount' => $product['discount'],
        'price' => $product['price']
      ]);

      $store = $thisProduct->store;

      $store->decrement('balance', $product['quantity']);

      $sale->transactions()->create([
        'store_id' => $store->id,
        'type' => 'decrement',
        'quantity' => $product['quantity'],
      ]);
    }

    toastr()->success("فروش جدید برای {$sale->customer->name} ثبت شد.");

    return to_route('admin.sales.index');
  }

  public function show(Sale $sale): View
  {
    $sale->load([
      'customer' => fn($query) => $query->select('id', 'name', 'mobile', 'address', 'status', 'telephone'),
      'employee' => fn($query) => $query->select('id', 'name', 'mobile'),
      'items' => fn($query) => $query->latest('id'),
      'items.product' => fn($query) => $query->select('id', 'title', 'image', 'category_id'),
      'items.product.category' => fn($query) => $query->select('id', 'unit_type')
    ]);
    $categories = $this->getCategories();

    return view('sale::sale.show', compact('sale', 'categories'));
  }

  public function edit(Sale $sale): View|Application
  {
    $customers = $this->getCustomers();
    $employees = $this->getEmployees();

    return view('sale::sale.edit', compact(['customers', 'sale', 'employees']));
  }

  public function update(SaleUpdateRequest $request, Sale $sale): RedirectResponse
  {
    $sale->update($request->validated());
    toastr()->success("فروش با موفقیت بروزرسانی شد.");

    return to_route('admin.sales.index');
  }

  public function destroy(Sale $sale): RedirectResponse
  {
    $sale->delete();
    toastr()->success("فروش با موفقیت حذف شد.");

    return redirect()->back();
  }

  public function showInvoice(Sale $sale): View
  {
    $sale->load([
      'items' => fn($query) => $query->select(['id', 'price', 'discount', 'quantity', 'product_id', 'sale_id']),
      'items.product' => fn($query) => $query->select(['id', 'title', 'category_id']),
      'items.product.category' => fn($query) => $query->select(['id', 'title', 'unit_type']),
    ]);

    return view('sale::invoice.show', compact('sale'));
  }

  public function getProductStore(Request $request): JsonResponse
  {
    $product = Product::query()
      ->with('store:id,product_id,balance')
      ->find($request->input('product_id'));

    $data = [
      'balance' => $product->store->balance,
      'price' => number_format($product->price),
      'discount' => number_format($product->discoun),
    ];

    return response()->json($data);
  }

  private function getCustomers(): Collection|array
  {
    return Customer::query()
      ->where('status', 1)
      ->select('id', 'name', 'mobile')
      ->orderByDesc('name')
      ->get();
  }

  private function getEmployees(): Collection|array
  {
    return Employee::query()
      ->select('id', 'name', 'mobile')
      ->orderByDesc('name')
      ->get();
  }

  private function getCategories(): Collection|array
  {
    return Category::query()
      ->select('id', 'parent_id', 'title')
      ->where('status', 1)
      ->with('products')
      ->get();
  }
}
