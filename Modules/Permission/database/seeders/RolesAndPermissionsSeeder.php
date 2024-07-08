<?php

namespace Modules\Permission\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Admin\Models\Admin;
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

    $admin = Admin::query()->firstOrCreate(
      ['mobile' => '09368917169',],
      [
        'name' => 'علی علاالدین',
        'password' => bcrypt(123456),
        'status' => 1
      ]);

    if (!$admin->getRoleName()) {
      $admin->assignRole(\Modules\Permission\Models\Role::SUPER_ADMIN);
    }

    //create permissions
    $permissions = [
      'view dashboard stats' => 'مشاهده آمارهای داشبورد',
      //settings
      'view settings' => 'مشاهده تنظیمات',
      'create settings' => 'ایجاد تنظیمات',
      'edit settings' => 'ویرایش تنظیمات',
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
      //headlines
      'view headlines' => 'مشاهده سرفصل ها',
      'create headlines' => 'ایجاد سرفصل ها',
      'edit headlines' => 'ویرایش سرفصل ها',
      'delete headlines' => 'حذف سرفصل ها',
      //revenues
      'view revenues' => 'مشاهده درامد ها',
      'create revenues' => 'ایجاد درامد ها',
      'edit revenues' => 'ویرایش درامد ها',
      'delete revenues' => 'حذف درامد ها',
      //expenses
      'view expenses ' => 'مشاهده هزینه ها',
      'create expenses ' => 'ایجاد هزینه ها',
      'edit expenses ' => 'ویرایش هزینه ها',
      'delete expenses ' => 'حذف هزینه ها',
      //salaries
      'view salaries' => 'مشاهده حقوق ها',
      'create salaries' => 'ایجاد حقوق ها',
      'edit salaries' => 'ویرایش حقوق ها',
      'delete salaries' => 'حذف حقوق ها',
      //stores
      'view stores' => 'مشاهده انبار',
      'create stores' => 'ایجاد انبار',
      'edit stores' => 'ویرایش انبار',
      'delete stores' => 'حذف انبار',
      //accounts
      'view accounts' => 'مشاهده حساب بانکی ها',
      'create accounts' => 'ایجاد حساب بانکی ها',
      'edit accounts' => 'ویرایش حساب بانکی ها',
      'delete accounts' => 'حذف حساب بانکی ها',
    ];

    foreach ($permissions as $name => $label) {
      Permission::query()->firstOrCreate(
        ['name' => $name],
        ['label' => $label, 'guard_name' => 'web']
      );
    }
  }
}
