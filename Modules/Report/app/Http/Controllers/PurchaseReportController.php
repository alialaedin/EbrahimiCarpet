<?php

namespace Modules\Report\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Modules\Purchase\Models\Purchase;
use Modules\Report\Http\Requests\PurchaseFilterRequest;
use Modules\Supplier\Models\Supplier;
use Illuminate\Database\Eloquent\Builder;

class PurchaseReportController extends Controller
{
  public function filter(): View
  {
    $suppliers = Supplier::query()->select('id', 'name', 'mobile')->get();

    return view('report::purchase.filter', compact('suppliers'));
  }

  public function list(PurchaseFilterRequest $request): View
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

}
