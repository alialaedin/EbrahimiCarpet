<?php

namespace Modules\Report\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Modules\Customer\Models\Customer;

class CustomerIndebtednessController extends Controller
{
  public function list(): View
  {
    $customers = Customer::query()
      ->select('id', 'name', 'mobile', 'telephone')
      ->with([
        'sales:id,discount,customer_id',
        'sales.items:id,sale_id,quantity,price,discount',
        'payments'
      ])
      ->latest('id')
      ->get();

    return view('report::customer-indebtedness.list', compact('customers'));
  }
}
