<?php

namespace Modules\Product\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Modules\Product\Models\Category;
use Modules\Product\Models\Product;

class PricingController extends Controller
{
  public function create()
	{
		$categories = Category::getParentCategories();
		$products = Product::getParentProducts();

		return view('product::product.create', compact(['categories', 'products']));
	}
}
