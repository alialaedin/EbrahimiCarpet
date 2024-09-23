<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Hekmatinasser\Verta\Facades\Verta;
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
use Modules\Core\Helpers\Helpers;

class DashboardController extends Controller
{
  public function index(): View
  {
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
    $payableInstallments = $this->getPayableInstallments();

    return view('admin::dashboard.index', compact([

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
      'receivedInstallments',
      'payableInstallments'
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
    $startDate = Helpers::toGregorian(Verta::startMonth());
    $endDate = Helpers::toGregorian(Verta::endMonth());

    $purchases = Purchase::query()
      ->when($time === 'today', fn($query) => $query->whereDate('purchased_at', today()))
      ->when($time === 'month', fn($query) => $query->whereBetween('purchased_at', [$startDate, $endDate]))
      ->with('items')
      ->get();

    $amount = 0;

    foreach ($purchases as $purchase) {
      foreach ($purchase->items as $item) {
        $amount += ($item->price * $item->quantity) - $item->discount;
      }
      $amount -= $purchase->discount;
    }

    return $amount;
  }

  private function getTodaySaleItems(): int
  {
    return SaleItem::query()
      ->withWhereHas('sale', function ($query) {
        return $query->whereDate('sold_at', today());
      })->count();
  }

  private function getSaleAmount(string $time)
  {
    $startDate = Helpers::toGregorian(Verta::startMonth());
    $endDate = Helpers::toGregorian(Verta::endMonth());

    $sales = Sale::query()
      ->when($time === 'today', fn($query) => $query->whereDate('sold_at', today()))
      ->when($time === 'month', fn($query) => $query->whereBetween('sold_at', [$startDate, $endDate]))
      ->with('items')
      ->get();

    $amount = 0;

    foreach ($sales as $sale) {
      foreach ($sale->items as $item) {
        $amount += ($item->price * $item->quantity) - $item->discount;
      }
      $amount -= $sale->discount;
    }

    return $amount;
  }

  private function getReceivedCheques(): Collection|array
  {
    return SalePayment::query()
      ->select('id', 'customer_id', 'amount', 'type', 'due_date', 'payment_date')
      ->where('type', '=', 'cheque')
      ->whereNull('payment_date')
      ->with('customer:id,name')
      ->whereDate('due_date', '<=', now()->addWeeks(2))
      ->orderBy('due_date', 'ASC')
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
      ->orderBy('due_date', 'ASC')
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
      ->orderBy('due_date', 'ASC')
      ->get();
  }

  private function getPayableInstallments(): Collection|array
  {
    return Payment::query()
      ->select('id', 'supplier_id', 'amount', 'type', 'due_date', 'payment_date', 'image')
      ->where('type', '=', 'installment')
      ->whereNull('payment_date')
      ->with('supplier:id,name,mobile')
      ->whereDate('due_date', '<=', now()->addWeeks(2))
      ->orderBy('due_date', 'ASC')
      ->get();
  }
}
