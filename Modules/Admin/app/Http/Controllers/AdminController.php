<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\Foundation\Application as App;
use Illuminate\Http\RedirectResponse;
use Modules\Admin\Http\Requests\AdminStoreRequest;
use Modules\Admin\Http\Requests\AdminUpdateRequest;
use Modules\Admin\Models\Admin;
use Modules\Core\Traits\BreadCrumb;
use Modules\Permission\Models\Role;
use Spatie\Activitylog\Models\Activity;

class AdminController extends Controller implements HasMiddleware
{
	public static function middleware(): array
  {
		return [
			new Middleware('can:view admins', ['index', 'show']),
			new Middleware('can:create admins', ['create', 'store']),
			new Middleware('can:edit admins', ['edit', 'update']),
			new Middleware('can:delete admins', ['destroy']),
		];
	}

	public function index(): View|Application|Factory|App
  {
		$admins = Admin::query()
			->select(['id', 'name', 'mobile', 'status', 'created_at'])
			->latest('id')
			->paginate(15);

		$adminsCount = $admins->total();

		return view('admin::index', compact('admins', 'adminsCount'));
	}

	public function show(Admin $admin): View|Application|Factory|App
  {
		$activities = Activity::query()
			->select('id', 'causer_id', 'description', 'created_at')
			->where('causer_id', $admin->id)
			->latest('id')
			->paginate(15);

		$totalActivity = $activities->total();

		return view('admin::show', compact('admin', 'activities', 'totalActivity'));
	}

	public function create(): View|Application|Factory|App
  {
		$roles = Role::query()->select('id', 'name', 'label')->whereNot('name', 'super_admin')->get();

		return view('admin::create', compact('roles'));
	}

	public function store(AdminStoreRequest $request): RedirectResponse
  {
		$admin = Admin::query()->create($request->validated());
		$admin->assignRole($request->role);
		toastr()->success("ادمین جدید به نام $admin->nam با موفقیت ساخته شد.");

		return to_route('admin.admins.index');
	}

	public function edit(Admin $admin): View|Application|Factory|App
  {
		$roles = Role::query()->select('id', 'name', 'label')->whereNot('name', 'super_admin')->get();

		return view('admin::edit', compact('admin', 'roles'));
	}

	public function update(AdminUpdateRequest $request, Admin $admin): RedirectResponse
  {
		$inputs = [
			'name' => $request->input('name'),
			'mobile' => $request->input('mobile'),
			'status' => $request->filled('status') ? 1 : 0,
		];

		if ($request->filled("password")) {
			$inputs['password'] = Hash::make($request->input('password'));
		}

		$admin->update($inputs);

		if ($admin->getRoleName() !== $request->input('role')) {
			$admin->syncRoles([$request->input('role')]);
		}
		
		toastr()->success("ادمین با نام $admin->name با موفقیت ویرایش شد.");

		return redirect()->back()->withInput();
	}

	public function destroy(Admin $admin): RedirectResponse
  {
		$adminRole = $admin->roles()->first();
		$admin->removeRole($adminRole);
		$admin->delete();

		toastr()->success("ادمین با نام $admin->name با موفقیت پاک شد.");

		return redirect()->back();
	}
}
