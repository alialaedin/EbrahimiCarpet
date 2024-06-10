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
use Modules\Store\Models\Store;

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
      ->select('id', 'title')
      ->whereNull('parent_id')
      ->with('children:id,title,parent_id')
      ->get();
	}

	public function index()
	{
		$categoryId = request('category_id');
		$title = request('title');
		$status = request('status');
		$hasDiscount = request('has_discount');

		$products = Product::query()
			->select('id', 'title', 'category_id', 'status', 'discount', 'price', 'image', 'created_at')
			->with(['category' => fn ($query) => $query->select('id', 'title')])
			->when($title, fn (Builder $query) => $query->where('title', 'like', "%{$title}%"))
			->when($categoryId, fn (Builder $query) => $query->where('category_id', $categoryId))
			->when(isset($status), fn (Builder $query) => $query->where('status', $status))
			->when(isset($hasDiscount), function (Builder $query) use ($hasDiscount) {
				return $query->where(function ($query) use ($hasDiscount) {
					if ($hasDiscount == 1) {
						$query->where('discount', '>', 0)->orWhereNotNull('discount');
					} else {
						$query->where('discount', '=', 0)->orWhereNull('discount');
					}
				});
			})
			->latest('id')
			->paginate(15)
			->withQueryString();

		$categories = Category::select('id', 'title')->get();
		$productsCount = $products->total();

		return view('product::product.index', compact('products', 'productsCount', 'categories'));
	}

	public function show(Product $product)
	{
		$product->load('category.parent');

		return view('product::product.show', compact('product'));
	}

	public function create()
	{
		$parentCategories = $this->getParentCategories();

		return view('product::product.create', compact('parentCategories'));
	}

	public function store(ProductStoreRequest $request)
	{
		$inputs = $this->getFormInputs($request);

		if ($request->hasFile('image') && $request->file('image')->isValid()) {
			$inputs['image'] = $request->file('image')->store('images/products', 'public');
		}

		$product = Product::query()->create($inputs);
    Store::query()->create([
      'product_id' => $product->id,
      'balance' => 0
    ]);

		toastr()->success("محصول جدید با نام {$product->title} با موفقیت ساخته شد.");

		return to_route('admin.products.index');
	}

	public function edit(Product $product)
	{
		$parentCategories = $this->getParentCategories();

		return view('product::product.edit', compact('product', 'parentCategories'));
	}

	public function update(ProductUpdateRequest $request, Product $product)
	{
		$inputs = $this->getFormInputs($request);

		if ($request->hasFile('image') && $request->file('image')->isValid()) {
			if (!is_null($product->image)) {
				Storage::delete($product->image);
			}
			$inputs['image'] = $request->file('image')->store('images/products', 'public');
		}

		$product->update($inputs);
		toastr()->success("محصول با نام {$product->title} با موفقیت بروزرسانی شد.");

		return redirect()->back()->withInput();
	}

	public function destroy(Product $product)
	{
		$product->delete();

		if ($product->image) {
			Storage::disk('public')->delete($product->image);
		}
		
		toastr()->success("محصول با نام {$product->title} با موفقیت حذف شد.");

		return redirect()->back();
	}

	public function destroyImage(Product $product)
	{
		Storage::disk('public')->delete($product->image);
		$product->image = null;
		$product->save();
		toastr()->success("عکس محصول با موفقیت حذف شد.");

		return redirect()->back();
	}

	private function getFormInputs(Request $request)
	{
		return [
			'title' => $request->input('title'),
			'category_id' => $request->input('category_id'),
			'price' => $request->input('price'),
			'discount' => $request->input('discount'),
			'description' => $request->input('description'),
			'status' => $request->input('status'),
		];
	}
}
