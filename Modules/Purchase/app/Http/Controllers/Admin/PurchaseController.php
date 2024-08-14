<?php

namespace Modules\Purchase\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Modules\Product\Models\Category;
use Modules\Product\Models\Product;
use Modules\Purchase\Http\Requests\Admin\Purchase\PurchaseStoreRequest;
use Modules\Purchase\Http\Requests\Admin\Purchase\PurchaseUpdateRequest;
use Modules\Purchase\Models\Purchase;
use Modules\Purchase\Models\PurchaseItem;
use Modules\Store\Services\StoreService;
use Modules\Supplier\Models\Supplier;

class PurchaseController extends Controller implements HasMiddleware
{
	public static function middleware(): array
	{
		return [
			new Middleware('can:view purchases', ['index']),
			new Middleware('can:view purchase_items', ['show']),
			new Middleware('can:create purchases', ['create', 'store']),
			new Middleware('can:edit purchases', ['edit', 'update']),
			new Middleware('can:delete purchases', ['destroy']),
		];
	}

	public function index(): View
	{
		$supplierId = request('supplier_id');
		$fromPurchasedAt = request('from_purchased_at');
		$toPurchasedAt = request('to_purchased_at');

		$purchases = Purchase::query()
			->select('id', 'supplier_id', 'purchased_at', 'discount', 'created_at')
			->with('supplier:id,name,mobile')
			->when($supplierId, fn (Builder $query) => $query->where('supplier_id', $supplierId))
			->when($fromPurchasedAt, fn (Builder $query) => $query->whereDate('purchased_at', '>=', $fromPurchasedAt))
			->when($toPurchasedAt, fn (Builder $query) => $query->whereDate('purchased_at', '<=', $toPurchasedAt))
			->latest('id')
			->paginate(15)
			->withQueryString();

		$suppliers = $this->getSuppliers();
		$purchasesCount = $purchases->total();

		return view('purchase::purchase.index', compact('purchases', 'suppliers', 'purchasesCount'));
	}

	public function create(): View
	{
		$suppliers = $this->getSuppliers();
		$categories = $this->getCategories();

		return view('purchase::purchase.create', compact('suppliers', 'categories'));
	}

	public function store(PurchaseStoreRequest $request): RedirectResponse
	{
		$purchase = Purchase::query()->create($request->only('supplier_id', 'purchased_at', 'discount'));

		foreach ($request->input('products') as $product) {
			$thisProduct = Product::query()->find($product['id']);

			PurchaseItem::query()->create([
				'purchase_id' => $purchase->id,
				'product_id' => $product['id'],
				'quantity' => $product['quantity'],
				'discount' => $product['discount'],
				'price' => $product['price']
			]);

      StoreService::add_product_to_store($thisProduct, $product['price'], $product['quantity']);

//      $purchase->transactions()->create([
//        'store_id' => $thisProduct->store->id,
//        'type' => 'increment',
//        'quantity' => $product['quantity'],
//      ]);

		}
		toastr()->success("خرید جدید از {$purchase->supplier->name} ثبت شد.");

		return to_route('admin.purchases.index');
	}

	public function show(Purchase $purchase): View|Application
  {
		$purchase->load([
			'supplier' => fn ($query) => $query->select('id', 'name', 'mobile'),
			'items' => fn ($query) => $query->latest('id'),
			'items.product' => fn ($query) => $query->select('id', 'title', 'image', 'category_id', 'sub_title'),
			'items.product.category' => fn ($query) => $query->select('id', 'unit_type')
		]);
		$categories = $this->getCategories();

		return view('purchase::purchase.show', compact('purchase', 'categories'));
	}

	public function edit(Purchase $purchase): View|Application
  {
		$suppliers = $this->getSuppliers();

		return view('purchase::purchase.edit', compact('purchase', 'suppliers'));
	}

	public function update(PurchaseUpdateRequest $request, Purchase $purchase): RedirectResponse
  {
		$purchase->update($request->validated());
		toastr()->success("خرید با موفقیت بروزرسانی شد.");

		return redirect()->back()->withInput();
	}

	public function destroy(Purchase $purchase): RedirectResponse
  {
		$purchase->delete();
		toastr()->success("خرید با موفقیت حذف شد.");

		return redirect()->back();
	}

	private function getSuppliers(): Collection|array
	{
		return Supplier::active()
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
