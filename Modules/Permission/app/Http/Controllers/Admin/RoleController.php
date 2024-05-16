<?php

namespace Modules\Permission\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Modules\Core\Traits\BreadCrumb;
use Modules\Permission\Http\Requests\RoleStoreRequest;
use Modules\Permission\Http\Requests\RoleUpdateRequest;
use Modules\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller implements HasMiddleware
{
	use BreadCrumb;
	public const MODEL = 'نقش';
	public const TABLE = 'roles';

	public static function middleware()
	{
		return [
			new Middleware('can:view roles', ['index']),
			new Middleware('can:create roles', ['create', 'store']),
			new Middleware('can:edit roles', ['edit', 'update']),
			new Middleware('can:delete roles', ['destroy']),
		];
	}
	private function permissions(): Collection
	{
		return Permission::query()
			->oldest('id')
			->select(['id', 'name', 'label'])
			->get();
	}

	public function index(): Renderable
	{
		$roles = Role::query()
			->latest('id')
			->select(['id', 'name', 'label', 'created_at'])
			->paginate();

		$breadcrumbItems = $this->breadcrumbItems('index', static::TABLE, static::MODEL);
		$rolesCount = $roles->total();

		return view('permission::admin.role.index', compact('roles', 'breadcrumbItems', 'rolesCount'));
	}

	public function create(): Renderable
	{
		$permissions = $this->permissions();
		$breadcrumbItems = $this->breadcrumbItems('create', static::TABLE, static::MODEL);

		return view('permission::admin.role.create', compact('permissions', 'breadcrumbItems'));
	}

	public function store(RoleStoreRequest $request): RedirectResponse
	{
		$role = Role::query()->create([
			'name' => $request->input('name'),
			'label' => $request->input('label'),
			'guard_name' => 'web'
		]);

		$permissions = $request->input('permissions');
		if ($permissions) {
			foreach ($permissions as $permission) {
				$role->givePermissionTo($permission);
			}
		}

		toastr()->success('نقش با موفقیت ثبت شد.');

		return redirect()->route('admin.roles.index');
	}

	public function edit(Role $role): Renderable
	{
		$permissions = $this->permissions();
		$breadcrumbItems = $this->breadcrumbItems('edit', static::TABLE, static::MODEL);

		return view('permission::admin.role.edit', compact('permissions', 'role', 'breadcrumbItems'));
	}

	public function update(RoleUpdateRequest $request, Role $role): RedirectResponse
	{
		$role->update($request->only(['name', 'label']));

		$permissions = $request->input('permissions');
		$role->syncPermissions($permissions);

		toastr()->success('نقش با موفقیت به روزرسانی شد.');

		return redirect()->route('admin.roles.index');
	}

	public function destroy(Role $role): RedirectResponse
	{
		DB::beginTransaction();
		try {
			$role->delete();
			foreach ($role->permissions as $permission) {
				$role->revokePermissionTo($permission);
			}
			DB::commit();
			toastr()->success('نقش با موفقیت حذف شد.');
		} catch (\Exception $e) {
			DB::rollBack();
			toastr()->error($e->getMessage());
		}

		return redirect()->route('admin.roles.index');
	}
}
