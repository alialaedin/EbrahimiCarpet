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
				['label' => $label, 'guard_name' => 'web']
			);
		}

		//create permissions
		$permissions = [
			'view dashboard stats' => 'مشاهده آمارهای داشبورد',
			//settings
			'view settings' => 'مشاهده تنظیمات',
			'create settings' => 'ایجاد تنظیمات',
			'edit settings' => 'ویرایش تنظیمات',
			//admins
			// 'view admins' => 'مشاهده ادمین ها',
			// 'create admins' => 'ایجاد ادمین ها',
			// 'edit admins' => 'ویرایش ادمین ها',
			// 'delete admins' => 'حذف ادمین ها',
			//roles NEW
			'view roles' => 'مشاهده نقش ها',
			'create roles' => 'ایجاد نقش ها',
			'edit roles' => 'ویرایش نقش ها',
			'delete roles' => 'حذف نقش ها',
			//customers
			'view customers' => 'مشاهده مشتری ها',
			'create customers' => 'ایجاد مشتری ها',
			'edit customers' => 'ویرایش مشتری ها',
			'delete customers' => 'حذف مشتری ها',
			//personnels
			'view personnels' => 'مشاهده پرسنل',
			'create personnels' => 'ایجاد پرسنل',
			'edit personnels' => 'ویرایش پرسنل',
			'delete personnels' => 'حذف پرسنل',
			//suppliers
			'view suppliers' => 'مشاهده تامین کنندگان',
			'create suppliers' => 'ایجاد تامین کنندگان',
			'edit suppliers' => 'ویرایش تامین کنندگان',
			'delete suppliers' => 'حذف تامین کنندگان',
			//categories
			'view categories' => 'مشاهده دسته بندی ها',
			'create categories' => 'ایجاد دسته بندی ها',
			'edit categories' => 'ویرایش دسته بندی ها',
			'delete categories' => 'حذف دسته بندی ها',
			//products
			'view products' => 'مشاهده محصولات',
			'create products' => 'ایجاد محصولات',
			'edit products' => 'ویرایش محصولات',
			'delete products' => 'حذف محصولات',
		];

		foreach ($permissions as $name => $label) {
			Permission::query()->firstOrCreate(
				['name' => $name],
				['label' => $label, 'guard_name' => 'web']
			);
		}
	}
}
