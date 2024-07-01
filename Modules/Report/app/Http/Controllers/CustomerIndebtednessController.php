<?php

namespace Modules\Report\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Modules\Customer\Models\Customer;

class CustomerIndebtednessController extends Controller
{
  public function list(): View
  {
    $name = request('name');

    $customers = Customer::query()
      ->select('id', 'name', 'mobile')
      ->when($name, fn(Builder $query) => $query->where('name', 'like', "%".$name."%"))
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
