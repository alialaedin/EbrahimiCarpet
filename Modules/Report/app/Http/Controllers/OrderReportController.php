<?php

namespace Modules\Report\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Hekmatinasser\Verta\Facades\Verta;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Modules\Core\Helpers\Helpers;
use Modules\Customer\Models\Customer;
use Modules\Purchase\Models\Purchase;
use Modules\Sale\Models\Sale;
use Modules\Supplier\Models\Supplier;

class OrderReportController extends Controller
{
  public function purchasesFilter(): View
  {
    $suppliers = Supplier::query()->select('id', 'name', 'mobile', 'created_at')->get();

    return view('report::purchase.filter', compact('suppliers'));
  }

  public function purchases(Request $request): View
  {
    $supplierId = $request->input('supplier_id');
    $hasDiscount = $request->input('has_discount');
    $fromPurchasedAt = $request->input('from_purchased_at');
    $toPurchasedAt = $request->input('to_purchased_at') ?? now();

    $supplier = Supplier::query()->findOrFail($supplierId, ['id', 'name']);

    $purchases = Purchase::query()
      ->select('id', 'supplier_id', 'purchased_at', 'discount', 'created_at')
      ->where('supplier_id', $supplierId)
      ->whereDate('purchased_at', '>=', $fromPurchasedAt)
      ->whereDate('purchased_at', '<=', $toPurchasedAt)
      ->when(isset($hasDiscount), function (Builder $query) use ($hasDiscount) {
        if ($hasDiscount == 0) {
          return $query->whereNull('discount')->orWhere('discount', 0);
        } else {
          return $query->where('discount', '>', 0);
        }
      })
      ->with('items:id,purchase_id,price,discount,quantity')
      ->latest('id')
      ->get();

    return view('report::purchase.list', compact(['purchases', 'supplier']));
  }

  public function salesFilter(): View
  {
    $customers = Customer::query()->select('id', 'name', 'mobile')->get();

    return view('report::sale.filter', compact('customers'));
  }

  public function sales(Request $request): View
  {
    $customerId = $request->input('customer_id');
    $hasDiscount = $request->input('has_discount');
    $fromSoldAt = $request->input('from_sold_at');
    $toSoldAt = $request->input('to_sold_at') ?? now();

    $customer = Customer::query()->findOrFail($customerId, ['id', 'name']);

    $sales = Sale::query()
      ->select('id', 'customer_id', 'sold_at', 'discount', 'created_at')
      ->where('customer_id', $customerId)
      ->whereDate('sold_at', '>=', $fromSoldAt)
      ->whereDate('sold_at', '<=', $toSoldAt)
      ->when(isset($hasDiscount), function (Builder $query) use ($hasDiscount) {
        if ($hasDiscount == 0) {
          return $query->whereNull('discount')->orWhere('discount', 0);
        } else {
          return $query->where('discount', '>', 0);
        }
      })
      ->with('items:id,sale_id,price,discount,quantity')
      ->latest('id')
      ->get();

    return view('report::sale.list', compact(['sales', 'customer']));
  }

  public function monthlySales()
  {
    $sales = Sale::query()
      ->select('id', 'customer_id', 'sold_at', 'discount', 'cost_of_sewing', 'created_at')
      ->with([
        'customer' => fn ($q) => $q->select(['id', 'name']),
        'items' => fn ($q) => $q->select(['id', 'sale_id', 'price', 'discount', 'quantity'])
      ])
      ->whereBetween('created_at', [
        Helpers::toGregorian(Verta::startYear()), 
        Helpers::toGregorian(Verta::endYear())
      ])
      ->withCount('items')
      ->get()
      ->groupBy('sold_at_month');

    return view('report::sale.monthly-sales', compact('sales'));
  }
}
