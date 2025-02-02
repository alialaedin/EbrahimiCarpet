<?php

namespace Modules\Purchase\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Modules\Product\Models\Product;
use Modules\Purchase\Http\Requests\Admin\PurchaseItem\PurchaseItemStoreRequest;
use Modules\Purchase\Http\Requests\Admin\PurchaseItem\PurchaseItemUpdateRequest;
use Modules\Purchase\Models\PurchaseItem;
use Modules\Store\Services\StoreService;

class PurchaseItemController extends Controller implements HasMiddleware
{
	public static function middleware()
	{
		return [
			new Middleware('can:create purchase_items', ['store']),
			new Middleware('can:edit purchase_items', ['update']),
			new Middleware('can:delete purchase_items', ['destroy']),
		];
	}

	public function store(PurchaseItemStoreRequest $request)
	{
		PurchaseItem::create($request->validated());
		$product = Product::findOrFail($request->input('product_id'));
		
		StoreService::addProductToStore($product, $request->input('price'), $request->input('quantity'));

		toastr()->success('آیتم جدید با موفقیت ثبت شد');

		return redirect()->back();
	}

	public function update(PurchaseItemUpdateRequest $request, PurchaseItem $purchaseItem)
	{
		$purchaseItem->load(['product.stores.price']);
		PurchaseItem::updateQuantityAndPrice($purchaseItem, $request);
		toastr()->success('آیتم مورد نظر با موفقیت بروزرسانی شد');

		return redirect()->back();	
	}

	public function destroy(PurchaseItem $purchaseItem)
	{
		$purchaseItem->delete();
		toastr()->success('آیتم مورد نظر با موفقیت حذف شد');

		return redirect()->back();
	}
}
