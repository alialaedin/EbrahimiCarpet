<?php

namespace Modules\Product\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
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

  private function getParentCategories(): array|Collection
  {
    return Category::query()
      ->select('id', 'title', 'unit_type')
      ->whereNull('parent_id')
      ->with('children:id,title,parent_id')
      ->get();
  }

  public function index(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
  {
    $categoryId = request('category_id');
    $title = request('title');
    $status = request('status');
    $hasDiscount = request('has_discount');

    $products = Product::query()
      ->select(
        'id',   
        'title',   
        'print_title',   
        'category_id',   
        'status',   
        'discount',   
        'price',   
        'image',   
        'parent_id',
      )
      ->with([
        'category' => fn($query) => $query->select('id', 'title', 'unit_type'),
        'stores' => fn($query) => $query->select('id', 'product_id', 'balance'),
      ])
      ->when($title, fn(Builder $query) => $query->where('title', 'like', "%{$title}%"))
      ->when($categoryId, fn(Builder $query) => $query->where('category_id', $categoryId))
      ->when(isset($status), fn(Builder $query) => $query->where('status', $status))
      ->when(isset($hasDiscount), function (Builder $query) use ($hasDiscount) {
        return $query->where(function ($query) use ($hasDiscount) {
          if ($hasDiscount == 1) {
            $query->where('discount', '>', 0)->orWhereNotNull('discount');
          } else {
            $query->where('discount', '=', 0)->orWhereNull('discount');
          }
        });
      })
      ->whereNull('parent_id')
      ->withCount(relations: 'children')
      ->latest('id')
      ->paginate()
      ->withQueryString();

    $categories = Category::query()->select('id', 'title')->get();
    $productsCount = $products->total();
    $totalProducts = Product::count();

    return view('product::product.index', compact(['products', 'productsCount', 'categories', 'totalProducts']));
  }

  public function show(Product $product): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
  {
    $product->load(['category.parent', 'children']);

    return view('product::product.show', compact('product'));
  }

  public function create(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
  {
    $parentCategories = $this->getParentCategories();

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

  public function edit(Product $product): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
  {
    $parentCategories = $this->getParentCategories();

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

    // $product->updateChildrenTitles();
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
