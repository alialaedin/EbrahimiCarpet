<?php

namespace Modules\Purchase\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Modules\Core\Traits\BreadCrumb;
use Modules\Product\Models\Category;
use Modules\Product\Models\Product;
use Modules\Purchase\Http\Requests\Admin\Purchase\PurchaseStoreRequest;
use Modules\Purchase\Http\Requests\Admin\Purchase\PurchaseUpdateRequest;
use Modules\Purchase\Models\Purchase;
use Modules\Purchase\Models\PurchaseItem;
use Modules\Store\Models\Store;
use Modules\Store\Models\StoreTransaction;
use Modules\Supplier\Models\Supplier;

class PurchaseController extends Controller implements HasMiddleware
{
	use BreadCrumb;
	public const MODEL = 'خرید';
	public const TABLE = 'purchases';

	public static function middleware()
	{
		return [
			new Middleware('can:view purchases', ['index']),
			new Middleware('can:view purchase_items', ['show']),
			new Middleware('can:create purchases', ['create', 'store']),
			new Middleware('can:edit purchases', ['edit', 'update']),
			new Middleware('can:delete purchases', ['destroy']),
		];
	}

	public function index()
	{
		$supplierId = request('supplier_id');
		$hasDiscount = request('has_discount');
		$fromPurchasedAt = request('from_purchased_at');
		$toPurchasedAt = request('to_purchased_at');

		$purchases = Purchase::query()
			->select('id', 'supplier_id', 'purchased_at', 'discount', 'created_at')
			->with('supplier:id,name,mobile')
			->when($supplierId, fn (Builder $query) => $query->where('supplier_id', $supplierId))
			->when($fromPurchasedAt, fn (Builder $query) => $query->whereDate('purchased_at', '>=', $fromPurchasedAt))
			->when($toPurchasedAt, fn (Builder $query) => $query->whereDate('purchased_at', '<=', $toPurchasedAt))
			->when(isset($hasDiscount), function (Builder $query) use ($hasDiscount) {
				return $query->where(function ($query) use ($hasDiscount) {
					if ($hasDiscount == 1) {
						$query->where('discount', '>', 0)->orWhereNotNull('discount');
					} else {
						$query->where('discount', '=', 0)->orWhereNull('discount');
					}
				});
			})
			->latest('id')
			->paginate(15)
			->withQueryString();

		$suppliers = $this->getSuppliers();
		$purchasesCount = $purchases->total();
		$breadcrumbItems = $this->breadcrumbItems('index', static::TABLE, static::MODEL);

		return view('purchase::index', compact('purchases', 'suppliers', 'purchasesCount', 'breadcrumbItems'));
	}

	public function create()
	{
		$suppliers = $this->getSuppliers();
		$categories = $this->getCategories();
		$breadcrumbItems = $this->breadcrumbItems('create', static::TABLE, static::MODEL);

		return view('purchase::create', compact('suppliers', 'categories', 'breadcrumbItems'));
	}

	public function store(PurchaseStoreRequest $request)
	{
		$purchase = Purchase::create($request->only('supplier_id', 'purchased_at', 'discount'));

		foreach ($request->input('products') as $product) {
			$thisProduct = Product::find($product['id']);

			PurchaseItem::create([
				'purchase_id' => $purchase->id,
				'product_id' => $product['id'],
				'quantity' => $product['quantity'],
				'discount' => $product['discount'],
				'price' => $product['price']
			]);

			if ($thisProduct->store()->exists()) {
				$thisProduct->store->balance += $product['quantity'];
				$thisProduct->store->save();
			} else {
				Store::create([
					'product_id' => $product['id'],
					'balance' => $product['quantity']
				]);
			}

			StoreTransaction::create([
				'store_id' => $thisProduct->store->id,
				'purchase_id' => $purchase->id,
				'type' => 'increment',
				'quantity' => $product['quantity'],
				'descrption' => null
			]);
		}
		toastr()->success("خرید جدید از {$purchase->supplier->name} ثبت شد.");

		return to_route('admin.purchases.index');
	}

	public function show(Purchase $purchase)
	{
		$purchase->load([
			'supplier' => fn ($query) => $query->select('id', 'name', 'mobile'),
			'items' => fn ($query) => $query->latest('id'),
			'items.product' => fn ($query) => $query->select('id', 'category_id', 'title', 'image'),
		]);
		$products = $this->getProducts();
		$categories = $this->getCategories();
		$breadcrumbItems = $this->breadcrumbItems('show', static::TABLE, static::MODEL);

		return view('purchase::show', compact('purchase', 'categories', 'breadcrumbItems'));
	}

	public function edit(Purchase $purchase)
	{
		$suppliers = $this->getSuppliers();
		$breadcrumbItems = $this->breadcrumbItems('edit', static::TABLE, static::MODEL);

		return view('purchase::edit', compact('purchase', 'suppliers', 'breadcrumbItems'));
	}

	public function update(PurchaseUpdateRequest $request, Purchase $purchase)
	{
		$purchase->update($request->validated());
		toastr()->success("خرید با موفقیت بروزرسانی شد.");

		return redirect()->back()->withInput();
	}

	public function destroy(Purchase $purchase)
	{
		$purchase->delete();
		toastr()->success("خرید با موفقیت حذف شد.");

		return redirect()->back();
	}

	private function getSuppliers()
	{
		return Supplier::query()
			->select('id', 'name', 'mobile')
			->orderByDesc('name')
			->get();
	}

	private function getCategories()
	{
		return Category::query()
			->select('id', 'parent_id', 'title')
			->where('status', 1)
			->with('products')
			->get();
	}

	private function getProducts()
	{
		return Product::query()
			->select('id', 'title', 'status')
			->where('status', 1)
			->get();
	}
}
