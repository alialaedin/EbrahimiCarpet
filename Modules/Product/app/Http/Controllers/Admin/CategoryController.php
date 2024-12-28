<?php

namespace Modules\Product\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Modules\Product\Http\Requests\Admin\Category\CategoryStoreRequest;
use Modules\Product\Http\Requests\Admin\Category\CategoryUpdateRequest;
use Modules\Product\Models\Category;

use function Flasher\Toastr\Prime\toastr;

class CategoryController extends Controller implements HasMiddleware
{
	public static function middleware(): array
  {
		return [
			new Middleware('can:view categories', ['index']),
			new Middleware('can:create categories', ['store']),
			new Middleware('can:edit categories', ['update']),
			new Middleware('can:delete categories', ['destroy']),
		];
	}

	public function index(): View
  {
		$categories = Category::query()
			->select(['id', 'title', 'parent_id', 'status', 'unit_type', 'created_at', 'updated_at'])
			->filters()
			->withParents()
			->withCountProducts()
			->latest('id')
			->get();

		$parentCategories = Category::getParentCategories();
		$categoriesCount = $categories->count();

		return view('product::category.index', compact('categories', 'categoriesCount', 'parentCategories'));
	}

	public function store(CategoryStoreRequest $request): RedirectResponse
  {
		$category = Category::query()->create($request->validated());
		toastr()->success("دسته بندی جدید به نام {$category->title} با موفقیت ساخته شد.");

		return to_route('admin.categories.index');
	}

	public function update(CategoryUpdateRequest $request, Category $category): RedirectResponse
  {
		$category->update($request->validated());
		toastr()->success("دسته بندی با نام {$category->title} با موفقیت بروزرسانی شد.");

    return to_route('admin.categories.index');
	}

	public function destroy(Category $category): RedirectResponse
  {
		$category->delete();
		toastr()->success("دسته بندی با نام {$category->title} با موفقیت حذف شد.");

		return redirect()->back();
	}
}
