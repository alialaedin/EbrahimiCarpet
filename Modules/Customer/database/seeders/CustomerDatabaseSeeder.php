<?php

namespace Modules\Customer\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Customer\Models\Customer;

class CustomerDatabaseSeeder extends Seeder
{
    public function run(): void
    {
      $customers = [
        ['name' => 'علی', 'mobile' => '09123456789', 'telephone' => '02112345678', 'address' => 'تهران، خیابان ولیعصر', 'status' => true],
        ['name' => 'محمد', 'mobile' => '09345678901', 'telephone' => '0222334455', 'address' => 'مشهد، بلوار سجاد', 'status' => false],
        ['name' => 'زهرا', 'mobile' => '09999999999', 'telephone' => '0333444555', 'address' => 'اصفهان، خیابان نصرت', 'status' => true],
        ['name' => 'حسین', 'mobile' => '09444444444', 'telephone' => '0444555666', 'address' => 'شیراز، خیابان مطهری', 'status' => false],
        ['name' => 'فاطمه', 'mobile' => '09555555555', 'telephone' => '0555666777', 'address' => 'کرج، خیابان فاطمی', 'status' => true],
        ['name' => 'ابراهیم', 'mobile' => '09666666666', 'telephone' => '0667777888', 'address' => 'تبریز، خیابان طالقانی', 'status' => false],
        ['name' => 'سارا', 'mobile' => '09777777777', 'telephone' => '0778888999', 'address' => 'بندرعباس، خیابان مفتاح', 'status' => true],
        ['name' => 'محمدرضا', 'mobile' => '09888888888', 'telephone' => '0889999911', 'address' => 'یزد، خیابان رضوانی', 'status' => false],
        ['name' => 'پارسیا', 'mobile' => '09999999999', 'telephone' => '0991111222', 'address' => 'قزوین، خیابان قائم مقام', 'status' => true],
        ['name' => 'رضا', 'mobile' => '09000000000', 'telephone' => '0000112233', 'address' => 'رشت، خیابان دریایی', 'status' => false]
      ];

      foreach ($customers as $customer) {
        Customer::query()->firstOrCreate(
          ['mobile' => $customer['mobile']],
          [
            'name' => $customer['name'],
            'telephone' => $customer['telephone'],
            'address' => $customer['address'],
            'status' => $customer['status'],
          ]
        );
      }
    }
}
