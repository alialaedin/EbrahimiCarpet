<?php

namespace Modules\Purchase\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Modules\Product\Models\Product;
use Modules\Purchase\Http\Requests\Admin\PurchaseItem\PurchaseItemStoreRequest;
use Modules\Purchase\Http\Requests\Admin\PurchaseItem\PurchaseItemUpdateRequest;
use Modules\Purchase\Models\PurchaseItem;
use Modules\Store\Models\Store;
use Modules\Store\Models\StoreTransaction;
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
		$purchaseItem = PurchaseItem::create($request->validated());
		$product = Product::findOrFail($request->input('product_id'));
		
		StoreService::addProductToStore($product, $request->input('price'), $request->input('quantity'));

    $purchaseItem->purchase->transactions()->create([
      'store_id' => $product->store->id,
      'type' => 'increment',
      'quantity' => $request->input('quantity'),
    ]);

		toastr()->success('آیتم جدید با موفقیت ثبت شد');

		return redirect()->back();
	}

	public function update(PurchaseItemUpdateRequest $request, PurchaseItem $purchaseItem)
	{
		$diff = $request->input('quantity') - $purchaseItem->quantity;

		$purchaseItem->update($request->only(['quantity', 'price', 'discount']));

		$store = $purchaseItem->product->store;
		$store->balance += $diff;
		$store->save();

		$type = $diff < 0 ? 'decrement' : 'increment';
		$quantity = abs($diff);

    $purchaseItem->purchase->transactions()->create([
      'store_id' => $store->id,
      'type' => $type,
      'quantity' => $quantity
    ]);

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
