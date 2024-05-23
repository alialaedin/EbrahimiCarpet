<?php

namespace Modules\Product\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Storage;
use Modules\Core\Traits\BreadCrumb;
use Modules\Product\Http\Requests\Admin\Product\ProductStoreRequest;
use Modules\Product\Http\Requests\Admin\Product\ProductUpdateRequest;
use Modules\Product\Models\Category;
use Modules\Product\Models\Product;

class ProductController extends Controller implements HasMiddleware
{
	use BreadCrumb;
	public const MODEL = 'محصول';
	public const TABLE = 'products';

	public static function middleware()
	{
		return [
			new Middleware('can:view products', ['index', 'show']),
			new Middleware('can:create products', ['create', 'store']),
			new Middleware('can:edit products', ['edit', 'update']),
			new Middleware('can:delete products', ['destroy']),
		];
	}

	private function getParentCategories()
	{
		return Category::query()->select('id', 'title')->whereNull('parent_id')->with('children:id,title,parent_id')->get();
	}

	public function index()
	{
		$categoryId = request('category_id');
		$title = request('title');
		$status = request('status');
		$hasDiscount = request('has_discount');

		$products = Product::query()
			->select('id', 'title', 'category_id', 'status', 'discount', 'price', 'created_at')
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
		$breadcrumbItems = $this->breadcrumbItems('index', static::TABLE, static::MODEL);

		return view('product::product.index', compact('products', 'productsCount', 'categories', 'breadcrumbItems'));
	}

	public function show(Product $product)
	{
		$product->load([
			'category',
			'category.parent'
		]);
		$breadcrumbItems = $this->breadcrumbItems('show', static::TABLE, static::MODEL);

		return view('product::product.show', compact('product', 'breadcrumbItems'));
	}

	public function create()
	{
		$parentCategories = $this->getParentCategories();
		$breadcrumbItems = $this->breadcrumbItems('create', static::TABLE, static::MODEL);

		return view('product::product.create', compact('parentCategories', 'breadcrumbItems'));
	}

	public function store(ProductStoreRequest $request)
	{
		$inputs = $this->getFormInputs($request);

		if ($request->hasFile('image') && $request->file('image')->isValid()) {
			$inputs['image'] = $request->file('image')->store('images/products', 'public');
		}

		$product = Product::query()->create($inputs);
		toastr()->success("محصول جدید با نام {$product->title} با موفقیت ساخته شد.");

		return to_route('admin.products.index');
	}

	public function edit(Product $product)
	{
		$parentCategories = $this->getParentCategories();
		$breadcrumbItems = $this->breadcrumbItems('edit', static::TABLE, static::MODEL);

		return view('product::product.edit', compact('product', 'parentCategories', 'breadcrumbItems'));
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
		if ($product->image) {
			Storage::disk('public')->delete($product->image);
		}
		$product->delete();
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
