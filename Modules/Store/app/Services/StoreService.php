<?php

namespace Modules\Store\Services;

use Illuminate\Database\Eloquent\Builder;
use Modules\Product\Models\Price;
use Modules\Product\Models\Product;
use Modules\Sale\Models\SaleItem;
use Modules\Store\Models\Store;
use Illuminate\Database\Eloquent\Collection;
use Modules\Purchase\Models\PurchaseItem;

class StoreService
{
  public static function add_product_to_store(Product|Builder|Collection $product, int $purchasedPrice, int $initialBalance): void
  {
    $price = $product->prices()->create([
      'buy_price' => $purchasedPrice,
      'sell_price' => $product->price,
    ]);

    if ($product->stores()->exists()) {
      $store = Store::query()
        ->select('id', 'priority', 'product_id')
        ->where('product_id', $product->id)
        ->orderByDesc('priority')
        ->first();
      $priority = $store->priority + 1;
    } else {
      $priority = 1;
    }

    $product->stores()->create([
      'price_id' => $price->id,
      'balance' => $initialBalance,
      'priority' => $priority
    ]);
  }

  public static function store_product_demenisions($mainProduct, $demenisions, $request)
  {
    foreach ($demenisions as $demenisionProduct) {

      $storedProduct = Product::query()->create([
        'title' => $request->title,
        'sub_title' => $demenisionProduct['sub_title'],
        'print_title' => $request->print_title,
        'category_id' => $request->category_id,
        'parent_id' => $mainProduct->id,
        'price' => $demenisionProduct['price'],
        'discount' => $demenisionProduct['discount'],
        'status' => $request->status,
      ]);

      $productCollection = collect($storedProduct);
      $productCollection->put('initial_balance', $demenisionProduct['initial_balance']);
      $productCollection->put('purchased_price', $demenisionProduct['purchased_price']);

      $storedProducts[] = $productCollection;
    }

    static::check_if_product_has_initial_balance($storedProducts);
  }

  public static function decrement_store_balance(Product|Collection|Builder $product, int|float $quantity): void
  {
    $stores = Store::query()
      ->where('product_id', $product->id)
      ->orderByDesc('priority')
      ->get();

    foreach ($stores as $store) {

      $balance = $store->balance;
      $quantityToDecrement = (float) min($balance, $quantity);

      $store->decrement('balance', $quantityToDecrement);
      $quantity -= $balance;

      if ($quantity <= 0) {
        break;
      }
    }
  }

  public static function check_if_product_has_initial_balance(array|Collection $products): void
  {
    foreach ($products as $product) {
      $balance = $product['initial_balance'];
      $purchasedPrice = $product['purchased_price'];

      $hasInitialBalance = !is_null($balance);
      $hasPurchasedPrice = !is_null($purchasedPrice) && ($purchasedPrice > 0);

      if ($hasInitialBalance && $hasPurchasedPrice) {
        $product = Product::query()
          ->select(['id', 'price'])
          ->where('id', $product['id'])
          ->with(['prices', 'stores'])
          ->first();

        static::add_product_to_store($product, $purchasedPrice, $balance);
      }
    }
  }

  public static function update_sell_price(Product|Builder $product): void
  {
    $prices = Price::query()->whereIn('product_id', [$product->id])->get();
    foreach ($prices as $price) {
      $price->sell_price = $product->price;
      $price->save();
    }
  }

  public static function calc_total_buy_prices(array $products): int
  {
    $totalProductsBuyPrices = 0;

    foreach (collect($products) as $product) {
      $numProductsToBeSold = $product['quantity'];

      $stores = Store::query()
        ->where('product_id', $product['id'])
        ->orderBy('priority', 'ASC')
        ->get();

      $productTotalBuyPrices = 0;

      foreach ($stores as $store) {
        $price = $store->price;
        $quantityToBuy = min($store->balance, $numProductsToBeSold);

        $productTotalBuyPrices += $quantityToBuy * $price->buy_price;
        $numProductsToBeSold -= $quantityToBuy;

        if ($numProductsToBeSold <= 0) {
          break;
        }
      }

      $totalProductsBuyPrices += $productTotalBuyPrices;
    }

    return $totalProductsBuyPrices;
  }

  public static function calc_total_sell_prices(array $products): int
  {
    return collect($products)->map(function ($product) {
      return ($product['quantity'] * $product['price']) - $product['discount'];
    })->sum();
  }

  public static function insert_products_to_sale_items(array|Builder $products, int $saleId): void
  {
    $productIds = collect($products)->pluck('id')->all();
    $productsData = Product::query()->whereIn('id', $productIds)->get()->keyBy('id');

    foreach ($products as $product) {

      $numProductsToBeSold = $product['quantity'];
      $DBProduct = $productsData[$product['id']];

      $stores = Store::query()
        ->where('product_id', $DBProduct->id)
        ->where('balance', '>', 0)
        ->orderBy('priority', 'ASC')
        ->get();

      $archivedPriceArr = [];

      foreach ($stores as $store) {
        $price = $store->price;

        if ($store->balance < $numProductsToBeSold) {

          $archivedPriceArr[] = [
            'price_id' => $price->id,
            'buy_price' => $price->buy_price,
            'priority' => $store->priority,
            'quantity' => $store->balance,
          ];

          $numProductsToBeSold -= $store->balance;

          $store->balance = 0;
          $store->save();
        } else {

          $archivedPriceArr[] = [
            'price_id' => $price->id,
            'buy_price' => $price->buy_price,
            'priority' => $store->priority,
            'quantity' => $numProductsToBeSold,
          ];

          $store->decrement('balance', $numProductsToBeSold);
          break;
        }
      }

      SaleItem::query()->create([
        'sale_id' => $saleId,
        'product_id' => $product['id'],
        'quantity' => $product['quantity'],
        'discount' => $product['discount'],
        'archived_price' => $archivedPriceArr,
        'price' => $product['price']
      ]);

      // TODO: transactions for store should be added;

    }
  }

  public static function returning_inventory(SaleItem $saleItem): void
  {
    foreach ($saleItem->archived_price as $price) {
      $store = Store::findByPriceId($price['price_id']);
      $store->increment('balance', $price['quantity']);
    }
  }
}
