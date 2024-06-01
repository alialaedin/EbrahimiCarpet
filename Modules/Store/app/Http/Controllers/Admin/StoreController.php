<?php

namespace Modules\Store\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Modules\Core\Traits\BreadCrumb;
use Modules\Store\Models\Store;

class StoreController extends Controller implements HasMiddleware
{
  use BreadCrumb;

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
    $breadcrumbItems = $this->breadcrumbItems('index', 'stores', 'انبار');
    $stores = Store::query()
			->select('id', 'product_id', 'balance', 'created_at')
			->with('product:id,title,image')
			->latest('id')
			->paginate();

    return view('store::index', compact('stores', 'breadcrumbItems'));
  }
}
