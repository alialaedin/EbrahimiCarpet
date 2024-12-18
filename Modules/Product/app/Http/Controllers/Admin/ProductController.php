<?php

namespace Modules\Product\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Storage;
use Modules\Product\Http\Requests\Admin\Product\ProductStoreRequest;
use Modules\Product\Http\Requests\Admin\Product\ProductUpdateRequest;
use Modules\Product\Models\Category;
use Modules\Product\Models\Product;
use Modules\Store\Services\StoreService;

class ProductController extends Controller implements HasMiddleware
{
  public static function middleware(): array
  {
    return [
      new Middleware('can:view products', ['index', 'show']),
      new Middleware('can:create products', ['create', 'store']),
      new Middleware('can:edit products', ['edit', 'update']),
      new Middleware('can:delete products', ['destroy']),
    ];
  }

  public function index(): View
  {
    $products = Product::query()
      ->select(Product::INDEX_PAGE_SELECTED_COLUMNS)
      ->with([
        'category' => fn($q) => $q->select('id', 'title', 'unit_type'),
        'stores' => fn($q) => $q->select('id', 'product_id', 'balance'),
      ])
      ->filters()
      ->parents()
      ->withCount('children')
      ->latest('id')
      ->paginate(request('perPage', 15))
      ->withQueryString();

    $categories = Category::query()->select('id', 'title')->get();
    $totalProducts = $products->total();

    return view('product::product.index', compact(['products', 'categories', 'totalProducts']));
  }

  public function show(Product $product): View
  {
    $product->load(['category.parent', 'children']);

    return view('product::product.show', compact('product'));
  }

  public function create(): View
  {
    $parentCategories = Category::getParentCategories();

    return view('product::product.create', compact('parentCategories'));
  }

  public function store(ProductStoreRequest $request): \Illuminate\Http\RedirectResponse
  {
    $image = null;

    if ($request->hasFile('image') && $request->file('image')->isValid()) {
      $image = $request->file('image')->store('images/products', 'public');
    }

    $mainProduct = Product::query()->create([
      'title' => $request->title,
      'print_title' => $request->print_title,
      'category_id' => $request->category_id,
      'image' => $image,
      'description' => $request->description,
      'price' => $request->price,
      'discount' => $request->discount,
      'status' => $request->status,
    ]);

    StoreService::storeProductDemenisions($mainProduct, $request->input('product_dimensions'), $request);

    toastr()->success("محصول جدید با نام {$request->title} با موفقیت ساخته شد.");

    return to_route('admin.products.index');
  }

  public function edit(Product $product): View
  {
    $parentCategories = Category::getParentCategories();

    return view('product::product.edit', compact('product', 'parentCategories'));
  }

  public function update(ProductUpdateRequest $request, Product $product): \Illuminate\Http\RedirectResponse
  {
    $inputs = $this->getFormInputs($request);

    if ($request->hasFile('image') && $request->file('image')->isValid()) {
      if (!is_null($product->image)) {
        Storage::disk('public')->delete($product->image);
      }
      $inputs['image'] = $request->file('image')->store('images/products', 'public');
    }

    $product->update($inputs);
    $product->updateChildren(['title', 'print_title', 'category_id']);

    StoreService::updateSellPrice($product);

    if (count($request->input('product_dimensions')) != 0) {
      StoreService::storeProductDemenisions($product, $request->input('product_dimensions'), $request);
    }

    toastr()->success("محصول با نام {$product->title} با موفقیت بروزرسانی شد.");

    return redirect()->back()->withInput();
  }

  public function destroy(Product $product): \Illuminate\Http\RedirectResponse
  {
    $product->delete();

    if ($product->image) {
      Storage::disk('public')->delete($product->image);
    }

    toastr()->success("محصول با نام {$product->title} با موفقیت حذف شد.");

    return $product->getRedirectRoute();
  }

  public function destroyImage(Product $product): \Illuminate\Http\RedirectResponse
  {
    Storage::disk('public')->delete($product->image);
    $product->image = null;
    $product->save();
    toastr()->success("عکس محصول با موفقیت حذف شد.");

    return redirect()->back();
  }

  private function getFormInputs(Request $request): array
  {
    return [
      'title' => $request->input('title'),
      'print_title' => $request->input('print_title'),
      'category_id' => $request->input('category_id'),
      'price' => $request->input('price'),
      'discount' => $request->input('discount'),
      'description' => $request->input('description'),
      'status' => $request->input('status'),
    ];
  }
}
