<?php

namespace Modules\Permission\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		// create roles
		$roles = [
			'super_admin' => 'مدیر ارشد'
		];

		foreach ($roles as $name => $label) {
			Role::query()->firstOrCreate(
				['name' => $name],
				['label' => $label, 'guard_name' => 'admin-api']
			);
		}

		//create permissions
		$permissions = [
			'view dashboard stats' => 'مشاهده آمارهای داشبورد',
			//admins
			'view admins' => 'مشاهده ادمین ها',
			'create admins' => 'ایجاد ادمین ها',
			'edit admins' => 'ویرایش ادمین ها',
			'delete admins' => 'حذف ادمین ها',
			//cities
			'view cities' => 'مشاهده شهر ها',
			'create cities' => 'ایجاد شهر ها',
			'edit cities' => 'ویرایش شهر ها',
			'delete cities' => 'حذف شهر ها',
			//provinces
			'view provinces' => 'مشاهده استان ها',
			//roles NEW
			'view roles' => 'مشاهده نقش ها',
			'create roles' => 'ایجاد نقش ها',
			'edit roles' => 'ویرایش نقش ها',
			'delete roles' => 'حذف نقش ها',
			//settings
			'view settings' => 'مشاهده تنظیمات',
			'create settings' => 'ایجاد تنظیمات',
			'edit settings' => 'ویرایش تنظیمات',
		];

		foreach ($permissions as $name => $label) {
			Permission::query()->firstOrCreate(
				['name' => $name],
				['label' => $label, 'guard_name' => 'admin-api']
			);
		}
	}
}
