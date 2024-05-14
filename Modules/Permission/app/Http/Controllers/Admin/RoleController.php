<?php

namespace Modules\Permission\App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Modules\Permission\App\Http\Requests\RoleStoreRequest;
use Modules\Permission\App\Http\Requests\RoleUpdateRequest;
use Modules\Permission\App\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
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
			->sortable()
			->latest('id')
			->select(['id', 'name', 'label', 'created_at'])
			->paginate();

		return view('permission::admin.role.index', compact('roles'));
	}

	public function create(): Renderable
	{
		$permissions = $this->permissions();

		return view('permission::admin.role.create', compact('permissions'));
	}

	public function store(RoleStoreRequest $request): RedirectResponse
	{
		$role = Role::query()->create([
			'name' => $request->input('name'),
			'label' => $request->input('label'),
			'guard_name' => 'admin'
		]);

		$permissions = $request->input('permissions');
		if ($permissions) {
			foreach ($permissions as $permission) {
				$role->givePermissionTo($permission);
			}
		}

		return redirect()->route('admin.roles.index')
			->with('success', 'نقش با موفقیت ثبت شد.');
	}

	public function edit(Role $role): Renderable
	{
		$permissions = $this->permissions();

		return view('permission::admin.role.edit', compact('permissions', 'role'));
	}

	public function update(RoleUpdateRequest $request, Role $role): RedirectResponse
	{
		$role->update($request->only(['name', 'label']));

		$permissions = $request->input('permissions');
		$role->syncPermissions($permissions);

		return redirect()->route('admin.roles.index')
			->with('success', 'نقش با موفقیت به روزرسانی شد.');
	}

	public function destroy(Role $role): RedirectResponse
	{
		$permissions = $role->permissions;

		if ($role->delete()) {
			foreach ($permissions as $permission) {
				$role->revokePermissionTo($permission);
			}
		}

		return redirect()->route('admin.roles.index')
			->with('success', 'نقش با موفقیت حذف شد.');
	}
}
