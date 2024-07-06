<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Modules\Payment\Models\Payment;
use Modules\Product\Models\Category;
use Modules\Product\Models\Product;
use Modules\Purchase\Models\Purchase;
use Modules\Purchase\Models\PurchaseItem;
use Modules\Sale\Models\Sale;
use Modules\Sale\Models\SaleItem;
use Modules\Sale\Models\SalePayment;
use Illuminate\Database\Eloquent\Collection;

class DashboardController extends Controller
{
  public function index(): View
  {

    $totalProducts = Product::query()->count();
    $totalSales = Sale::query()->count();
    $totalPurchases = Purchase::query()->count();
    $totalCategories = Category::query()->count();

    $todayPurchaseCount = Purchase::query()->whereDate('purchased_at', today())->count();
    $todayPurchaseItems = $this->getTodayPurchaseItems();
    $todayPurchaseAmount = $this->getPurchaseAmount('today');
    $thisMonthPurchaseAmount = $this->getPurchaseAmount('month');

    $todaySaleCount = Sale::query()->whereDate('sold_at', today())->count();
    $todaySaleItems = $this->getTodaySaleItems();
    $todaySaleAmount = $this->getSaleAmount('today');
    $thisMonthSaleAmount = $this->getSaleAmount('month');

    $receivedCheques = $this->getReceivedCheques();
    $payableCheques = $this->getPayableCheques();
    $receivedInstallments = $this->getReceivedInstallments();

    return view('admin::dashboard.index', compact([

      'totalProducts',
      'totalSales',
      'totalPurchases',
      'totalCategories',

      'todayPurchaseCount',
      'todayPurchaseItems',
      'todayPurchaseAmount',
      'thisMonthPurchaseAmount',

      'todaySaleCount',
      'todaySaleItems',
      'todaySaleAmount',
      'thisMonthSaleAmount',

      'receivedCheques',
      'payableCheques',
      'receivedInstallments'
    ]));
  }

  private function getTodayPurchaseItems(): int
  {
    return PurchaseItem::query()
      ->withWhereHas('purchase', function ($query) {
        return $query->whereDate('purchased_at', today());
      })->count();
  }

  private function getPurchaseAmount(string $time): int
  {
    $startDate = Carbon::now()->timezone(env('APP_TIMEZONE'))->startOfMonth();
    $endDate = Carbon::now()->timezone(env('APP_TIMEZONE'))->endOfMonth();

    return Purchase::query()
      ->join('purchase_items', 'purchases.id', '=', 'purchase_items.purchase_id')
      ->selectRaw(
        'SUM(purchase_items.price * purchase_items.quantity - purchase_items.discount)
        - purchases.discount AS total_purchases'
      )
      ->when($time === 'today', fn($query) => $query->whereDate('purchases.purchased_at', today()))
      ->when($time === 'month', fn($query) => $query->whereBetween('purchases.purchased_at', [$startDate, $endDate]))
      ->groupBy('purchases.id', 'purchases.discount')
      ->get()
      ->sum('total_purchases');
  }

  private function getTodaySaleItems(): int
  {
    return SaleItem::query()
      ->withWhereHas('sale', function ($query) {
        return $query->whereDate('sold_at', today());
      })->count();
  }

  private function getSaleAmount(string $time): int
  {
    $startDate = Carbon::now()->timezone(env('APP_TIMEZONE'))->startOfMonth();
    $endDate = Carbon::now()->timezone(env('APP_TIMEZONE'))->endOfMonth();

    return Sale::query()
      ->join('sale_items', 'sales.id', '=', 'sale_items.sale_id')
      ->selectRaw(
        'SUM(sale_items.price * sale_items.quantity - sale_items.discount)
        - sales.discount AS total_sales'
      )
      ->when($time === 'today', fn($query) => $query->whereDate('sales.sold_at', today()))
      ->when($time === 'month', fn($query) => $query->whereBetween('sales.sold_at', [$startDate, $endDate]))
      ->groupBy('sales.id', 'sales.discount')
      ->get()
      ->sum('total_sales');
  }

  private function getReceivedCheques(): Collection|array
  {
    return SalePayment::query()
      ->select('id', 'customer_id', 'amount', 'type', 'due_date', 'payment_date')
      ->where('type', '=', 'cheque')
      ->whereNull('payment_date')
      ->with('customer:id,name')
      ->whereDate('due_date', '<=', now()->addWeeks(2))
      ->latest('id')
      ->get();
  }

  private function getPayableCheques(): Collection|array
  {
    return Payment::query()
      ->select('id', 'supplier_id', 'amount', 'type', 'due_date', 'payment_date')
      ->where('type', '=', 'cheque')
      ->whereNull('payment_date')
      ->with('supplier:id,name')
      ->whereDate('due_date', '<=', now()->addWeeks(2))
      ->latest('id')
      ->get();
  }

  private function getReceivedInstallments(): Collection|array
  {
    return SalePayment::query()
      ->select('id', 'customer_id', 'amount', 'type', 'due_date', 'payment_date', 'image')
      ->where('type', '=', 'installment')
      ->whereNull('payment_date')
      ->with('customer:id,name,mobile')
      ->whereDate('due_date', '<=', now()->addWeeks(2))
      ->latest('id')
      ->get();
  }
}
