<?php

namespace Modules\Report\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Modules\Product\Models\Category;
use Modules\Product\Models\Product;
use Modules\Sale\Models\Sale;

class ProfitController extends Controller
{
  public function index(): View
  {
    $productId = request('product_id');
    $categoryId = request('category_id');
    $fromDate = request('from_date');
    $toDate = request('to_date') ?? now();

    $sales = Sale::query()
      ->when($fromDate, fn($q) => $q->whereDate('sold_at', '>=', $fromDate))
      ->when($toDate, fn($q) => $q->whereDate('sold_at', '<=', $toDate))
      ->when($productId, fn($q) => $q->whereHas('items', fn($itemQ) => $itemQ->where('product_id', $productId)))
      ->when($categoryId, fn($q) => $q->whereHas('items.product', fn($itemQ) => $itemQ->where('category_id', $categoryId)))
      ->with('items.product.category')
      ->latest('id')
      ->get();

    $sumTotalBuyPrice = 0;
    $sumTotalSellPrice = 0;
    $sumTotalDiscountPrice = 0;
    $sumTotalCostOfSewingPrice = 0;

    if ($sales->count() >= 1) {
      foreach ($sales as $sale) {
        foreach ($sale->items as $saleItem) {
          $sumTotalSellPrice += ($saleItem->price * $saleItem->quantity) - $saleItem->discount;
          foreach ($saleItem->archived_price as $price) {
            $sumTotalBuyPrice += (integer) $price['buy_price'] * (integer) $price['quantity'] ;
          }
        }
        if ((int) $sale->discount) {
          $sumTotalDiscountPrice += (int) $sale->discount;
        }
        if ((int) $sale->cost_of_sewing) {
          $sumTotalCostOfSewingPrice += (int) $sale->cost_of_sewing;
        }
      }
    }

    $profit = $sumTotalSellPrice - $sumTotalBuyPrice - $sumTotalDiscountPrice + $sumTotalCostOfSewingPrice;

    $products = Product::childrens()->select('id', 'title', 'sub_title')->get();
    $categories = Category::query()->children()->select('id', 'title')->get();

    $totalPrice = [
      'sum_total_sell_price' => $sumTotalSellPrice,
      'sum_total_buy_price' => $sumTotalBuyPrice,
      'sum_total_discount_price' => $sumTotalDiscountPrice,
      'sum_total_cost_of_sewing_price' => $sumTotalCostOfSewingPrice,
    ];

    return view('report::profit.index', compact([
      'sales', 
      'profit', 
      'products', 
      'categories',
      'totalPrice'
    ]));
  }
}
