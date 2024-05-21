<?php

namespace Modules\Admin\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Admin\Models\Admin;
use Modules\Permission\Models\Role;

class AdminDatabaseSeeder extends Seeder
{
	public function run(): void
	{
		// Eager load roles once
		$roles = Role::query()->select('id', 'name')->get();

		// Create admins
		$admins = Admin::factory()->count(50)->create();

		// Assign random roles to each admin using batch assignment
		foreach ($admins as $admin) {
			// Find a role that hasn't been assigned yet (assuming roles are unique)
			$roleName = $roles->whereNotIn('name', $admin->roles->pluck('name'))->first()?->name;

			if ($roleName) {
				$admin->assignRole($roleName);
			}
		}
	}
}
