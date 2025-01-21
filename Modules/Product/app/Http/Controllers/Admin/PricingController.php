<?php

namespace Modules\Product\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
		$request->validate([
			'products' => ['required', 'array'],
			'products.*.id' => ['required', 'integer'],
			'products.*.price' => ['required', 'integer']
		]);

		Product::updatePrice($request);
    toastr()->success('قیمت محصولات با موفقیت بروزسانی شد');

		return redirect()->back();
	}
}
