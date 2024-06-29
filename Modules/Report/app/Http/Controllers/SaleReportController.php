<?php

namespace Modules\Report\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Customer\Models\Customer;
use Modules\Report\Http\Requests\SaleFilterRequest;
use Modules\Sale\Models\Sale;

class SaleReportController extends Controller
{
  public function filter(): View
  {
    $customers = Customer::query()->select('id', 'name', 'mobile')->get();

    return view('report::sale.filter', compact('customers'));
  }

  public function list(SaleFilterRequest $request): View
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
}
