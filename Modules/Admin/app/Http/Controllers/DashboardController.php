<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Modules\Payment\Models\Payment;
use Modules\Product\Models\Category;
use Modules\Product\Models\Product;
use Modules\Purchase\Models\Purchase;
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

    $todayPurchases = Purchase::query()->whereDate('purchased_at', today())->count();
    $todaySales = Sale::query()->whereDate('sold_at', today())->count();

    $totalItemsSalesThisMonth = $this->thisMonthSalesCount();
    $totalAmountSalesThisMonth = $this->thisMonthSalesAmount();

    $receivedCheques = $this->getReceivedCheques();
    $payableCheques = $this->getPayableCheques();
    $receivedInstallments = $this->getReceivedInstallments();

    return view('admin::dashboard.index', compact([
      'totalProducts',
      'totalSales',
      'totalPurchases',
      'totalCategories',
      'todayPurchases',
      'todaySales',
      'totalItemsSalesThisMonth',
      'totalAmountSalesThisMonth',
      'receivedCheques',
      'payableCheques',
      'receivedInstallments'
    ]));
  }

  private function thisMonthSalesCount(): int
  {
    $startDate = Carbon::now()->startOfMonth()->timezone(env('APP_TIMEZONE'));
    $endDate = Carbon::now()->endOfMonth()->timezone(env('APP_TIMEZONE'));

    return SaleItem::query()
      ->withWhereHas('sale', function ($query) use ($startDate, $endDate) {
        return $query->whereBetween('sold_at', [$startDate, $endDate]);
      })->count();
  }

  private function thisMonthSalesAmount(): int
  {
    $startDate = Carbon::now()->timezone(env('APP_TIMEZONE'))->startOfMonth();
    $endDate = Carbon::now()->timezone(env('APP_TIMEZONE'))->endOfMonth();

    return Sale::query()
      ->selectRaw('SUM(sale_items.price * sale_items.quantity - sale_items.discount) - sales.discount AS total_sales')
      ->join('sale_items', 'sales.id', '=', 'sale_items.sale_id')
      ->whereBetween('sales.sold_at', [$startDate, $endDate])
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
