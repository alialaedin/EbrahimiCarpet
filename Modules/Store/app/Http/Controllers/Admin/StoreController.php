<?php

namespace Modules\Store\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Modules\Store\Models\Store;
use Modules\Store\Models\StoreTransaction;

class StoreController extends Controller implements HasMiddleware
{
  public static function middleware()
	{
		return [
			new Middleware('can:view stores', ['index', 'show']),
			new Middleware('can:create stores', ['create', 'store']),
			new Middleware('can:edit stores', ['edit', 'update']),
			new Middleware('can:delete stores', ['destroy']),
		];
	}

  public function index()
  {
    $stores = Store::query()
			->select('id', 'product_id', 'balance', 'created_at')
			->with('product:id,title,image')
			->latest('id')
			->paginate();

    return view('store::index', compact('stores'));
  }

  public function show(Store $store)
  {
    $store->load('product.category');

    $transactions = StoreTransaction::query()
      ->where('store_id', $store->id)
      ->with('purchase.supplier')
      ->latest('id')
      ->paginate(15);

    return view('store::show', compact('transactions',  'store'));
  }

}
