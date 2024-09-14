<?php

namespace Modules\Sale\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Modules\Sale\Http\Requests\Admin\SaleItem\SaleItemUpdateRequest;
use Modules\Sale\Http\Requests\Admin\SaleItem\SaleItemStoreRequest;
use Modules\Sale\Models\SaleItem;
use Modules\Store\Models\Store;
use Modules\Store\Services\StoreService;

class SaleItemController extends Controller implements HasMiddleware
{
  public static function middleware(): array
  {
    return [
      new Middleware('can:create sale_items', ['store']),
      new Middleware('can:edit sale_items', ['update']),
      new Middleware('can:delete sale_items', ['destroy']),
    ];
  }

  public function store(SaleItemStoreRequest $request): \Illuminate\Http\RedirectResponse
  {
    $productTotalBuyPrices = 0;
    $archivedPriceArr = [];
    $quantityToBeSold = $request->input('quantity');

    $stores = Store::query()
      ->where('product_id', $request->input('product_id'))
      ->where('balance', '>=', 1)
      ->orderBy('priority', 'ASC')
      ->get();

    foreach ($stores as $store) {
      $price = $store->price;
      $quantityToBuy = min($store->balance, $quantityToBeSold);
      $productTotalBuyPrices += $quantityToBuy * $price->buy_price;

      if ($store->balance < $quantityToBeSold) {

        $archivedPriceArr[] = [
          'price_id' => $price->id,
          'buy_price' => $price->buy_price,
          'priority' => $store->priority,
          'quantity' => $store->balance,
        ];

        $quantityToBeSold -= $store->balance;

        $store->balance = 0;
        $store->save();

      } else {

        $archivedPriceArr[] = [
          'price_id' => $price->id,
          'buy_price' => $price->buy_price,
          'priority' => $store->priority,
          'quantity' => $quantityToBeSold,
        ];

        $store->decrement('balance', $quantityToBeSold);

      }

      $quantityToBeSold -= $quantityToBuy;

      if ($quantityToBeSold <= 0) {
        break;
      }
    }

    $saleItem = SaleItem::query()->create([
      'sale_id' => $request->sale_id,
      'product_id' => $request->product_id,
      'quantity' => $request->quantity,
      'discount' => $request->discount,
      'archived_price' => $archivedPriceArr,
      'price' => $request->price
    ]);

    $sale = $saleItem->sale;
    $sale->total_buy_prices += $productTotalBuyPrices;
    $sale->total_sell_prices += (($request->price * $quantityToBeSold) - $request->discount);
    $sale->save();

//    $saleItem->sale->transactions()->create([
//      'store_id' => $product->store->id,
//      'type' => 'decrement',
//      'quantity' => $request->input('quantity'),
//    ]);

    toastr()->success('آیتم جدید با موفقیت ثبت شد');

    return redirect()->back();
  }

  public function update(SaleItemUpdateRequest $request, SaleItem $saleItem): \Illuminate\Http\RedirectResponse
  {
    $diff = (int)$request->input('quantity') - (int)$saleItem->quantity;

    $saleItem->update($request->only('quantity'));

    $type = $diff < 0 ? 'increment' : 'decrement';

    $diff = abs($diff);

    $store = $saleItem->product->store;

    $store->{$type}('balance', $diff);

    $saleItem->sale->transactions()->create([
      'store_id' => $store->id,
      'type' => $type,
      'quantity' => $diff
    ]);

    toastr()->success('آیتم مورد نظر با موفقیت بروزرسانی شد');

    return redirect()->back();
  }

  public function destroy(SaleItem $saleItem): \Illuminate\Http\RedirectResponse
  {
    StoreService::returningInventory($saleItem);
    $saleItem->delete();
    toastr()->success('آیتم مورد نظر با موفقیت حذف شد');

    return redirect()->back();
  }
}
