<?php

namespace Modules\Employee\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Ybazli\Faker\Facades\Faker;
use Faker\Factory as FactoryFaker;

class EmployeeFactory extends Factory
{
  /**
   * The name of the factory's corresponding model.
   */
  protected $model = \Modules\Employee\Models\Employee::class;

  protected const BANK_NAMES = [
    'بانک تجارت',
    'بانک صنعت و معدن',
    'بانک کشاورزی',
    'بانک توسعه صادرات ایران',
    'بانک رفاه کارگران',
    'بانک سپه',
    'بانک صادرات ایران',
    'بانک مرکزی',
    'بانک مسکن ایران',
    'بانک ملت ایران',
    'بانک ملی ایران',
    'پست بانک'
  ];

  public function definition(): array
  {
    $faker = FactoryFaker::create();

    return [
      'name' => Faker::fullName(),
      'mobile' => Faker::mobile(),
      'telephone' => Faker::mobile(),
      'address' => Faker::address(),
      'national_code' => Faker::mellicode(),
      'employmented_at' => $this->faker->date(),
      'salary' => random_int(1000000, 100000000),
      'card_number' => $this->faker->creditCardNumber(),
      'sheba_number' => $faker->bankAccountNumber,
      'bank_name' => collect(static::BANK_NAMES)->random()
    ];
  }
}
