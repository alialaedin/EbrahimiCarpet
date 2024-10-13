<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Hekmatinasser\Verta\Facades\Verta;
use Illuminate\Contracts\View\View;
use Modules\Payment\Models\Payment;
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

    $todayReceivedCheques = $this->getReceivedCheques('today');
    $todayPayableCheques = $this->getPayableCheques('today');
    $todayReceivedInstallments = $this->getReceivedInstallments('today');
    $todayPayableInstallments = $this->getPayableInstallments('today');

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

      'todayReceivedCheques',
      'todayPayableCheques',
      'todayReceivedInstallments',
      'todayPayableInstallments',

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
      ->when($time == 'today', fn($q) => $q->whereDate('sold_at', today()))
      ->when($time == 'month', fn($q) => $q->whereBetween('sold_at', [$startDate, $endDate]))
      ->with('items')
      ->get();

    $amount = 0;

    foreach ($sales as $sale) {
      foreach ($sale->items as $item) {
        $amount += ($item->price * $item->quantity) - $item->discount;
      }
      $amount -= $sale->discount;
      $amount += $sale->cost_of_sewing;
    }

    return $amount;
  }

  private function getReceivedCheques(string $time = null): Collection|array
  {
    $salePayments = SalePayment::query()
      ->select('id', 'customer_id', 'amount', 'type', 'due_date', 'payment_date')
      ->where('type', '=', 'cheque')
      ->whereNull('payment_date')
      ->with('customer:id,name');

    if ($time != null && $time == 'today') {
      $salePayments->whereDate('due_date', now());
    }else {
      $salePayments->whereDate('due_date', '<=', now()->addWeeks(2));
    }
    
    $salePayments->orderBy('due_date', 'ASC');
    $salePayments = $salePayments->get();

    return $salePayments;
  }

  private function getPayableCheques(string $time = null): Collection|array
  {
    $payments = Payment::query()
      ->select('id', 'supplier_id', 'amount', 'type', 'due_date', 'payment_date')
      ->where('type', '=', 'cheque')
      ->whereNull('payment_date')
      ->with('supplier:id,name');

    if ($time != null && $time == 'today') {
      $payments->whereDate('due_date', now());
    }else {
      $payments->whereDate('due_date', '<=', now()->addWeeks(2));
    }
    
    $payments->orderBy('due_date', 'ASC');
    $payments = $payments->get();

    return $payments;
  }

  private function getReceivedInstallments(string $time = null): Collection|array
  {
    $payments = SalePayment::query()
      ->select('id', 'customer_id', 'amount', 'type', 'due_date', 'payment_date', 'image')
      ->where('type', '=', 'installment')
      ->whereNull('payment_date')
      ->with('customer:id,name,mobile');

    if ($time != null && $time == 'today') {
      $payments->whereDate('due_date', now());
    }else {
      $payments->whereDate('due_date', '<=', now()->addWeeks(2));
    }
    
    $payments->orderBy('due_date', 'ASC');
    $payments = $payments->get();

    return $payments;
  }

  private function getPayableInstallments(string $time = null): Collection|array
  {
    $payments = Payment::query()
      ->select('id', 'supplier_id', 'amount', 'type', 'due_date', 'payment_date', 'image')
      ->where('type', '=', 'installment')
      ->whereNull('payment_date')
      ->with('supplier:id,name,mobile');

    if ($time != null && $time == 'today') {
      $payments->whereDate('due_date', now());
    }else {
      $payments->whereDate('due_date', '<=', now()->addWeeks(2));
    }
    
    $payments->orderBy('due_date', 'ASC');
    $payments = $payments->get();

    return $payments;
  }
}
