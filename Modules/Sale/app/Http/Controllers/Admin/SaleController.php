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
use Modules\Store\Services\StoreService;

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
    //  ---------- Creating Sale  ---------- \\

    $totalSellPrices = StoreService::calcTotalSellPrices($request->input('products'));
    $totalBuyPrices = StoreService::calcTotalBuyPrices($request->input('products'));

    $sale = Sale::query()->create([
      'customer_id' => $request->customer_id,
      'sold_at' => $request->sold_at,
      'discount' => $request->discount,
      'employee_id' => $request->employee_id,
      'discount_for' => $request->discount_for,
      'cost_of_sewing' => $request->cost_of_sewing,
      'total_sell_prices' => $totalSellPrices,
      'total_buy_prices' => $totalBuyPrices
    ]);

    // ---------- End Of Creating Sale ---------- \\

    // ---------- Creating Sale Items ---------- \\
    StoreService::insertProductsToSaleItems($request->input('products'), $sale->id);
    // ---------- End Of Creating Sale Items ---------- \\

    toastr()->success("فروش جدید برای {$sale->customer->name} ثبت شد.");

    return to_route('admin.sales.index');
  }

  public function show(Sale $sale): View
  {
    $sale->load([
      'customer' => fn($q) => $q->select('id', 'name', 'mobile', 'address'),
      'employee' => fn($q) => $q->select('id', 'name', 'mobile', 'address'),
      'items' => fn($q) => $q->latest('id'),
      'items.product' => fn($q) => $q->select('id', 'title', 'category_id', 'sub_title', 'parent_id'),
      'items.product.parent' => fn($q) => $q->select('id', 'image'),
      'items.product.category' => fn($q) => $q->select('id', 'unit_type'),
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
    foreach ($sale->items as $saleItem) {
      StoreService::returningInventory($saleItem);
    }

    $sale->delete();
    toastr()->success("فروش با موفقیت حذف شد.");

    return to_route('admin.sales.index');
  }

  public function showInvoice(Sale $sale): View
  {
    $sale->load([
      'items' => fn($query) => $query->select(['id', 'price', 'discount', 'quantity', 'product_id', 'sale_id']),
      'items.product' => fn($query) => $query->select(['id', 'title', 'category_id', 'print_title', 'sub_title']),
      'items.product.category' => fn($query) => $query->select(['id', 'title', 'unit_type']),
    ]);

    return view('sale::invoice.show', compact('sale'));
  }

  public function getProductStore(Request $request): JsonResponse
  {
    $product = Product::query()
      ->with('stores:id,product_id,balance')
      ->find($request->input('product_id'));

    $data = [
      'balance' => $product->stores->sum('balance'),
      'price' => number_format($product->price),
      'discount' => number_format($product->discount),
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
