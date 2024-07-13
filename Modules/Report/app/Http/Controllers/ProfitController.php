<?php

namespace Modules\Report\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Sale\Models\Sale;

class ProfitController extends Controller
{
  public function index(): View
  {
    $productId = \request('product_id');
    $categoryId = \request('category_id');
    $fromDate = \request('from_date');
    $toDate = \request('to_date') ?? now();

    $sales = Sale::query()
      ->when($fromDate, fn($query) => $query->whereDate('sold_at', '>=', $fromDate))
      ->when($toDate, fn($query) => $query->whereDate('sold_at', '<=', $toDate))
      ->when($productId, fn($query) => $query->whereHas('items', fn($itemQuery) => $itemQuery->where('product_id', $productId)))
      ->when($categoryId, fn($query) => $query->whereHas('items.product', fn($itemQuery) => $itemQuery->where('category_id', $categoryId)))
      ->with('items.product')
      ->latest('id')
      ->get();

    $sumTotalBuyPrice = 0;
    $sumTotalSellPrice = 0;

    if ($sales->count() >= 1) {
      foreach ($sales as $sale) {
        foreach ($sale->items as $saleItem) {
          $sumTotalSellPrice += ($saleItem->price * $saleItem->quantity) - $saleItem->discount;
          foreach ($saleItem->archived_price as $price) {
            $sumTotalBuyPrice += $price['buy_price'] ;
          }
        }
      }
    }

    $profit = $sumTotalBuyPrice - $sumTotalSellPrice;

    return view('report::profit.index', compact(['sales', 'profit']));
  }
}
