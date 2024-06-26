<?php

namespace Modules\Store\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Modules\Product\Models\Product;
use Modules\Store\Models\Store;
use Modules\Store\Models\StoreTransaction;
use Illuminate\Http\RedirectResponse;

class StoreController extends Controller implements HasMiddleware
{
  public static function middleware(): array
  {
		return [
			new Middleware('can:view stores', ['index', 'show']),
			new Middleware('can:create stores', ['create', 'store']),
			new Middleware('can:edit stores', ['edit', 'update']),
			new Middleware('can:delete stores', ['destroy']),
		];
	}

  public function index(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
  {
    $productId = request('product_id');
    $unitType = request('unit_type');
    $fromCreatedAt = request('from_created_at');
    $toCreatedAt = request('to_created_at') ?? now();

    $stores = Store::query()
			->select('id', 'product_id', 'balance', 'created_at')
      ->with([
        'product:id,title,image,category_id',
        'product.category:id,unit_type',
      ])
      ->when($productId, fn(Builder $query) => $query->where('product_id', $productId))
      ->when($fromCreatedAt, fn(Builder $query) => $query->whereDate('created_at', '>=', $fromCreatedAt))
      ->when($unitType, function ($query) use ($unitType) {
        return $query->withWhereHas('product.category', fn($query) => $query->where('unit_type', $unitType));
      })
      ->whereDate('created_at', '<=', $toCreatedAt)
			->latest('id')
			->paginate();

    $products = Product::all('id', 'title');

    return view('store::index', compact(['stores', 'products']));
  }

  public function show(Store $store): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
  {
    $store->load('product.category');

    $transactions = StoreTransaction::query()
      ->where('store_id', $store->id)
      ->with('transactionable')
      ->latest('id')
      ->paginate(15);

    return view('store::show', compact('transactions',  'store'));
  }

  public function increase_decrease(Request $request): RedirectResponse
  {
    $product = Product::query()
      ->select(['id', 'title'])
      ->with('store')
      ->findOrFail($request->input('product_id'));

    if ($request->input('type') === 'increase'){
      $product->store->increment($request->input('quantity'));
    }else {
      $product->store->decrement($request->input('quantity'));
    }

    return redirect()->back();
  }

}
