<?php

namespace Modules\Report\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Modules\Product\Models\Category;
use Modules\Product\Models\Product;
use Modules\Sale\Models\SaleItem;

class ProfitController extends Controller
{
  public function index(): View
  {
    $saleItems = SaleItem::query()
      ->select(['id', 'sale_id', 'product_id', 'price', 'quantity', 'discount', 'archived_price'])
      ->filters()
      ->with([
        'sale' => fn($q) => $q->select(['id', 'discount', 'cost_of_sewing', 'sold_at']),
        'product' => fn($q) => $q->select(['id', 'title', 'sub_title', 'category_id']),
        'product.category' => fn($q) => $q->select(['id', 'title', 'unit_type'])
      ])
      ->latest('id')
      ->get();

    $sumTotalBuyPrice = 0;
    $sumTotalSellPrice = 0;
    $sumTotalDiscountPrice = 0;
    $sumTotalCostOfSewingPrice = 0;

    $saleDiscounts = [];
    $saleCostsOfSewing = [];

    if ($saleItems->isNotEmpty()) {

      foreach ($saleItems as $item) {

        $sumTotalSellPrice += ($item->price * $item->quantity) - $item->discount;

        foreach ($item->archived_price as $price) {
          $sumTotalBuyPrice += (int) $price['buy_price'] * (int) $price['quantity'];
        }

        $saleId = $item->sale_id;

        if ($item->sale) {

          if (!isset($saleDiscounts[$saleId]) && (int) $item->sale->discount) {
            $saleDiscounts[$saleId] = (int) $item->sale->discount;
            $sumTotalDiscountPrice += $saleDiscounts[$saleId];
          }

          if (!isset($saleCostsOfSewing[$saleId]) && (int) $item->sale->cost_of_sewing) {
            $saleCostsOfSewing[$saleId] = (int) $item->sale->cost_of_sewing;
            $sumTotalCostOfSewingPrice += $saleCostsOfSewing[$saleId];
          }

        }
      }
    }

    $profit = $sumTotalSellPrice - $sumTotalBuyPrice - $sumTotalDiscountPrice + $sumTotalCostOfSewingPrice;

    $products = Product::query()->children()->select(['id', 'title', 'sub_title'])->get();
    $categories = Category::query()->children()->select(['id', 'title'])->get();

    $totalPrice = [
      'sum_total_sell_price' => $sumTotalSellPrice,
      'sum_total_buy_price' => $sumTotalBuyPrice,
      'sum_total_discount_price' => $sumTotalDiscountPrice,
      'sum_total_cost_of_sewing_price' => $sumTotalCostOfSewingPrice,
    ];

    return view('report::profit.index', compact([
      // 'sales',
      'saleItems',
      'profit',
      'products',
      'categories',
      'totalPrice'
    ]));
  }
}
