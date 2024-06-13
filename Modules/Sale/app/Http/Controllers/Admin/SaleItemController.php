<?php

namespace Modules\Sale\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Modules\Product\Models\Product;
use Modules\Sale\Http\Requests\Admin\SaleItem\SaleItemUpdateRequest;
use Modules\Sale\Http\Requests\Admin\SaleItem\SaleItemStoreRequest;
use Modules\Sale\Models\SaleItem;
use Modules\Store\Models\Store;

class SaleItemController extends Controller implements HasMiddleware
{
  public static function middleware()
  {
    return [
      new Middleware('can:create sale_items', ['store']),
      new Middleware('can:edit sale_items', ['update']),
      new Middleware('can:delete sale_items', ['destroy']),
    ];
  }

  public function store(SaleItemStoreRequest $request)
  {
    $saleItem = SaleItem::create($request->validated());

    $product = Product::findOrFail($request->input('product_id'));
    $product->store->decrement('balance', $request->input('quantity'));

    $saleItem->sale->transactions()->create([
      'store_id' => $product->store->id,
      'type' => 'decrement',
      'quantity' => $request->input('quantity'),
    ]);

    toastr()->success('آیتم جدید با موفقیت ثبت شد');

    return redirect()->back();
  }

  public function update(SaleItemUpdateRequest $request, SaleItem $saleItem)
  {
    $saleItem->update($request->only('quantity'));

    $balance = $request->input('quantity') - $saleItem->quantity;

    $type = $balance < 0 ? 'increment' : 'decrement';

    $store = Store::query()->where('product_id', $saleItem->product_id)->first();
    $store->update([
      'balance' => $store->balance - $balance
    ]);

    $quantity = abs($balance);

//    $store = $saleItem->product->store;
//    $store->{$type}('balance', $quantity);

    $saleItem->sale->transactions()->create([
      'store_id' => $store->id,
      'type' => $type,
      'quantity' => $quantity
    ]);

    toastr()->success('آیتم مورد نظر با موفقیت بروزرسانی شد');

    return redirect()->back();
  }

  public function destroy(SaleItem $saleItem)
  {
    $saleItem->delete();
    toastr()->success('آیتم مورد نظر با موفقیت حذف شد');

    return redirect()->back();
  }
}
