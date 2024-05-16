<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Hash;
use Modules\Admin\Http\Requests\AdminStoreRequest;
use Modules\Admin\Http\Requests\AdminUpdateRequest;
use Modules\Admin\Models\Admin;
use Modules\Core\Traits\BreadCrumb;
use Modules\Core\Traits\Form;
use Modules\Permission\Models\Role;

class AdminController extends Controller implements HasMiddleware
{
	use BreadCrumb, Form;

	public const MODEL = 'ادمین';
	public const TABLE = 'admins';

	public static function middleware()
	{
		return [
			new Middleware('can:view admins', ['index', 'show']),
			new Middleware('can:create admins', ['create', 'store']),
			new Middleware('can:edit admins', ['edit', 'update']),
			new Middleware('can:delete admins', ['destroy']),
		];
	}

	public function index()
	{
		$admins = Admin::query()
			->select(['id', 'name', 'mobile', 'status', 'created_at'])
			->latest('id')
			->paginate(15);

		$adminsCount = $admins->total();
		$breadcrumbItems = $this->breadcrumbItems('index', static::TABLE, static::MODEL);

		return view('admin::index', compact('admins', 'adminsCount', 'breadcrumbItems'));
	}

	public function show(Admin $admn)
	{
		dd('show');
	}

	public function create()
	{
		$roles = Role::query()->select('id', 'name', 'label')->whereNot('name', 'super_admin')->get();
		$breadcrumbItems = $this->breadcrumbItems('create', static::TABLE, static::MODEL);

		return view('admin::create', compact('roles', 'breadcrumbItems'));
	}

	public function store(AdminStoreRequest $request)
	{
		$admin = Admin::create($request->validated());
		$admin->assignRole($request->role);
		toastr()->success("ادمین جدید به نام {$admin->name} با موفقیت ساخته شد.");

		return to_route('admin.admins.index');
	}

	public function edit(Admin $admin)
	{
		$roles = Role::query()->select('id', 'name', 'label')->whereNot('name', 'super_admin')->get();
		$breadcrumbItems = $this->breadcrumbItems('edit', static::TABLE, static::MODEL);
		$adminRoleName = $admin->getRoleNames()->first();

		return view('admin::edit', compact('admin', 'roles', 'adminRoleName', 'breadcrumbItems'));
	}

	public function update(AdminUpdateRequest $request, Admin $admin)
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
		$admin->syncRoles($request->input('role'));
		toastr()->success("ادمین با نام {$admin->name} با موفقیت ویرایش شد.");

		return redirect()->back()->withInput();
	}

	public function destroy(Admin $admin)
	{
		$adminRole = $admin->roles()->first();
		$admin->removeRole($adminRole);
		$admin->delete();
		
		toastr()->success("ادمین با نام {$admin->name} با موفقیت پاک شد.");

		return redirect()->back();
	}
}
