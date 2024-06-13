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
			//roles
//			'view roles' => 'مشاهده نقش ها',
//			'create roles' => 'ایجاد نقش ها',
//			'edit roles' => 'ویرایش نقش ها',
//			'delete roles' => 'حذف نقش ها',
			//customers
			'view customers' => 'مشاهده مشتری ها',
			'create customers' => 'ایجاد مشتری ها',
			'edit customers' => 'ویرایش مشتری ها',
			'delete customers' => 'حذف مشتری ها',
			//employees
			'view employees' => 'مشاهده پرسنل',
			'create employees' => 'ایجاد پرسنل',
			'edit employees' => 'ویرایش پرسنل',
			'delete employees' => 'حذف پرسنل',
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
			//purchases
			'view purchases' => 'مشاهده خرید ها',
			'create purchases' => 'ایجاد خرید ها',
			'edit purchases' => 'ویرایش خرید ها',
			'delete purchases' => 'حذف خرید ها',
			//purchase_items
			'view purchase_items' => 'مشاهده اقلام خرید',
			'create purchase_items' => 'ایجاد اقلام خرید',
			'edit purchase_items' => 'ویرایش اقلام خرید',
			'delete purchase_items' => 'حذف اقلام خرید',
			//payments
			'view payments' => 'مشاهده پرداختی ها',
			'create payments' => 'ایجاد پرداختی ها',
			'edit payments' => 'ویرایش پرداختی ها',
			'delete payments' => 'حذف پرداختی ها',
      //sales
			'view sales' => 'مشاهده فروش ها',
			'create sales' => 'ایجاد فروش ها',
			'edit sales' => 'ویرایش فروش ها',
			'delete sales' => 'حذف فروش ها',
			//sale_items
			'view sale_items' => 'مشاهده اقلام فروش',
			'create sale_items' => 'ایجاد اقلام فروش',
			'edit sale_items' => 'ویرایش اقلام فروش',
			'delete sale_items' => 'حذف اقلام فروش',
			//sale_payments
			'view sale_payments' => 'مشاهده پرداختی های مشتری',
			'create sale_payments' => 'ایجاد پرداختی های مشتری',
			'edit sale_payments' => 'ویرایش پرداختی های مشتری',
			'delete sale_payments' => 'حذف پرداختی های مشتری',
			//stores
			'view stores' => 'مشاهده انبار',
			'create stores' => 'ایجاد انبار',
			'edit stores' => 'ویرایش انبار',
			'delete stores' => 'حذف انبار',
		];

		foreach ($permissions as $name => $label) {
			Permission::query()->firstOrCreate(
				['name' => $name],
				['label' => $label, 'guard_name' => 'web']
			);
		}
	}
}
