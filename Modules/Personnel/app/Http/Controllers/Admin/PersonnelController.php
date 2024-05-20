<?php

namespace Modules\Personnel\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Modules\Core\Traits\BreadCrumb;
use Modules\Personnel\Http\Requests\Admin\PersonnelStoreRequest;
use Modules\Personnel\Http\Requests\Admin\PersonnelUpdateRequest;
use Modules\Personnel\Models\Personnel;

class PersonnelController extends Controller implements HasMiddleware
{
	use BreadCrumb;
	public const MODEL = 'کارمند';
	public const TABLE = 'personnels';

	public static function middleware()
	{
		return [
			new Middleware('can:view personnels', ['index', 'show']),
			new Middleware('can:create personnels', ['create', 'store']),
			new Middleware('can:edit personnels', ['edit', 'update']),
			new Middleware('can:delete personnels', ['destroy']),
		];
	}

	public function index()
	{
		$fullName = request('full_name');
		$mobile = request('mobile');
		$fromEmploymentAt = request('from_employmented_at');
		$toEmploymentAt = request('to_employmented_at');

		$personnels = Personnel::query()
			->select('id', 'name', 'mobile', 'employmented_at', 'salary', 'national_code')
			->when($fullName, fn (Builder $query) => $query->where('name', 'like', "%{$fullName}%"))
			->when($mobile, fn (Builder $query) => $query->where('mobile', $mobile))
			->when($fromEmploymentAt, fn (Builder $query) => $query->whereDate('employmented_at', '>=', $fromEmploymentAt))
			->when($toEmploymentAt, fn (Builder $query) => $query->whereDate('employmented_at', '<=', $toEmploymentAt))
			->latest('id')
			->paginate(15)
			->withQueryString();

		$totalPersonnels =  $personnels->total();
		$breadcrumbItems = $this->breadcrumbItems('index', static::TABLE, static::MODEL);

		return view('personnel::index', compact('personnels', 'totalPersonnels', 'breadcrumbItems'));
	}

	public function show(Personnel $personnel)
	{
		$breadcrumbItems = $this->breadcrumbItems('show', static::TABLE, static::MODEL);

		return view('personnel::show', compact('personnel', 'breadcrumbItems'));
	}

	public function create()
	{
		$breadcrumbItems = $this->breadcrumbItems('create', static::TABLE, static::MODEL);

		return view('personnel::create', compact('breadcrumbItems'));
	}

	public function store(PersonnelStoreRequest $request)
	{
		$personnel = Personnel::create($request->validated());
		toastr()->success("کارمند جدید به نام {$personnel->name} با موفقیت ساخته شد.");

		return to_route('admin.personnels.index');
	}

	public function edit(Personnel $personnel)
	{
		$breadcrumbItems = $this->breadcrumbItems('edit', static::TABLE, static::MODEL);

		return view('personnel::edit', compact('personnel', 'breadcrumbItems'));
	}

	public function update(PersonnelUpdateRequest $request, Personnel $personnel)
	{
		$personnel->update($request->validated());
		toastr()->success("کارمند با نام {$personnel->name} با موفقیت ویرایش شد.");

		return redirect()->back()->withInput();
	}

	public function destroy(Personnel $personnel)
	{
		$personnel->delete();
		toastr()->success("کارمند با نام {$personnel->name} با موفقیت حذف شد.");

		return redirect()->back();
	}
}
