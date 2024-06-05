<?php

namespace Modules\Product\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Modules\Product\Http\Requests\Admin\Category\CategoryStoreRequest;
use Modules\Product\Http\Requests\Admin\Category\CategoryUpdateRequest;
use Modules\Product\Models\Category;

use function Flasher\Toastr\Prime\toastr;

class CategoryController extends Controller implements HasMiddleware
{
	public static function middleware()
	{
		return [
			new Middleware('can:view categories', ['index']),
			new Middleware('can:create categories', ['create', 'store']),
			new Middleware('can:edit categories', ['edit', 'update']),
			new Middleware('can:delete categories', ['destroy']),
		];
	}

	public function index()
	{
		$title = request('title');
		$parentId = request('parent_id');
		$unitType = request('unit_type');
		$status = request('status');

		$categories = Category::query()
			->select('id', 'title', 'parent_id', 'status', 'unit_type', 'created_at')
			->when($title, fn (Builder $query) => $query->where('title', 'like', "%{$title}%"))
			->when($parentId, function (Builder $query) use ($parentId) {
				return $query->where(function ($query) use ($parentId) {
					if ($parentId == 'none') {
						$query->whereNull('parent_id');
					} else {
						$query->where('parent_id', $parentId);
					}
				});
			})
			->when($unitType, fn (Builder $query) => $query->where('unit_type', $unitType))
			->when(isset($status), fn (Builder $query) => $query->where('status', $status))
			->with('parent:id,title')
			->latest('id')
			->paginate(15)
			->withQueryString();

		$parentCategories = Category::query()->select('id', 'title')->whereNull('parent_id')->get();
		$categoriesCount = $categories->total();

		return view('product::category.index', compact('categories', 'categoriesCount', 'parentCategories'));
	}

	public function create()
	{
		$parentCategories = Category::query()
			->select('id', 'title')
			->whereNull('parent_id')
			->get();

		return view('product::category.create', compact('parentCategories'));
	}

	public function store(CategoryStoreRequest $request)
	{
		$category = Category::create($request->validated());
		toastr()->success("دسته بندی جدید به نام {$category->title} با موفقیت ساخته شد.");

		return to_route('admin.categories.index');
	}

	public function edit(Category $category)
	{
		$parentCategories = Category::query()
			->select('id', 'title')
			->whereNull('parent_id')
			->whereNot('id', $category->id)
			->get();

		return view('product::category.edit', compact('category', 'parentCategories'));
	}

	public function update(CategoryUpdateRequest $request, Category $category)
	{
		$category->update($request->validated());
		toastr()->success("دسته بندی با نام {$category->title} با موفقیت بروزرسانی شد.");

		return redirect()->back()->withInput();
	}

	public function destroy(Category $category)
	{
		$category->delete();
		toastr()->success("دسته بندی با نام {$category->title} با موفقیت حذف شد.");

		return redirect()->back();
	}
}
