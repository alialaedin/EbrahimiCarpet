<?php

namespace Modules\Store\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Application;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Modules\Product\Models\Product;
use Modules\Store\Http\Requests\Admin\DecrementStoreBalanceRequest;
use Modules\Store\Http\Requests\Admin\IncrementStoreBalanceRequest;
use Modules\Store\Models\Store;
use Modules\Store\Models\StoreTransaction;
use Illuminate\Http\RedirectResponse;
use Modules\Store\Services\StoreService;

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

  public function index(): View|Application
  {
    $productId = request('product_id');
    $unitType = request('unit_type');
    $fromCreatedAt = request('from_created_at');
    $toCreatedAt = request('to_created_at') ?? now();

    $products = Product::query()
      ->select(['id', 'title', 'category_id', 'image'])
      ->with([
        'stores:id,balance,product_id',
        'category:id,title,unit_type',
      ])
      ->when($productId, fn(Builder $query) => $query->where('id', $productId))
      ->when($fromCreatedAt, fn(Builder $query) => $query->whereDate('created_at', '>=', $fromCreatedAt))
      ->when($unitType, function ($query) use ($unitType) {
        return $query->withWhereHas('category', fn($query) => $query->where('unit_type', $unitType));
      })
      ->whereDate('created_at', '<=', $toCreatedAt)
      ->latest('id')
      ->paginate();

    $productsToFilter = Product::all('id', 'title');

    return view('store::index', compact(['products', 'productsToFilter']));
  }

//  public function showTransactions(Product $product): View|Application
//  {
//    $product->load('product.category');
//
//    $transactions = StoreTransaction::query()
//      ->where('store_id', $store->id)
//      ->with('transactionable')
//      ->latest('id')
//      ->paginate();
//
//    return view('store::show', compact('transactions',  'store'));
//  }

  public function incrementBalance(IncrementStoreBalanceRequest $request): RedirectResponse
  {
    $product = Product::query()->find($request->input('product_id'));
    StoreService::add_product_to_store($product, $request->input('purchased_price'), $request->input('quantity'));

    toastr()->success("موجودی انبار برای محصول $product->title با موفقیت افزایش یافت.");

    return redirect()->back();
  }

  public function decrementBalance(DecrementStoreBalanceRequest $request): RedirectResponse
  {
    $product = Product::query()->find($request->input('product_id'));
    StoreService::decrement_store_balance($product, $request->input('quantity'));

    toastr()->success("موجودی انبار برای محصول $product->title با موفقیت کاهش یافت.");

    return redirect()->back();
  }
}
