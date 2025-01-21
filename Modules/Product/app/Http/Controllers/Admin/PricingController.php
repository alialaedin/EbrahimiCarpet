<?php

namespace Modules\Product\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Core\Helpers\Helpers;
use Modules\Product\Models\Category;
use Modules\Product\Models\Product;

class PricingController extends Controller
{
  public function create()
	{
		$categories = Category::getParentCategories();
		$products = Product::getParentProducts();

		return view('product::pricing.create', compact(['categories', 'products']));
	}

	public function store(Request $request)
	{
		$request->merge([
			'price' => $request->filled('price') ? Helpers::removeComma($request->price) : null
		]);

		$request->validate([
			'price' => ['required', 'integer', 'min:1000'],
			'category_id' => ['required', 'bail', 'integer', 'exists:categories,id'],
			'product_id' => ['nullable', 'bail', 'integer', 'exists:products,id'],
		]);

		Product::updatePrice($request);
    toastr()->success('قیمت محصولات با موفقیت بروزسانی شد');

		return redirect()->back();
	}
}
