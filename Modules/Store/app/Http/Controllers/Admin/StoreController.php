<?php

namespace Modules\Store\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Application;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Modules\Product\Models\Product;
use Modules\Store\Http\Requests\Admin\DecrementStoreBalanceRequest;
use Modules\Store\Http\Requests\Admin\IncrementStoreBalanceRequest;
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
    $products = Product::query()
      ->select(['id', 'title', 'category_id', 'sub_title', 'image', 'parent_id', 'created_at'])
      ->with([
        'stores' => fn($q) => $q->select(['id', 'balance', 'product_id']),
        'category' => fn($q) => $q->select(['id', 'title', 'unit_type']),
        'parent' => fn($q) => $q->select(['id', 'image']),
      ])
      ->filters()
      ->children()
      ->latest('id')
      ->paginate()
      ->withQueryString();

    $productsToFilter = Product::query()->select(['id', 'title', 'sub_title'])->children()->get();

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
    StoreService::addProductToStore($product, $request->input('purchased_price'), $request->input('quantity'));

    toastr()->success("موجودی انبار برای محصول $product->title با موفقیت افزایش یافت.");

    return redirect()->back();
  }

  public function decrementBalance(DecrementStoreBalanceRequest $request): RedirectResponse
  {
    $product = Product::query()->find($request->input('product_id'));
    StoreService::decrementStoreBalance($product, $request->input('quantity'));

    toastr()->success("موجودی انبار برای محصول $product->title با موفقیت کاهش یافت.");

    return redirect()->back();
  }
}
