<?php

namespace Modules\Accounting\Database\Factories;

use Faker\Factory as FactoryFaker;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Employee\Models\Employee;
use Ybazli\Faker\Facades\Faker;

class SalaryFactory extends Factory
{
  /**
   * The name of the factory's corresponding model.
   */
  protected $model = \Modules\Accounting\Models\Salary::class;

  /**
   * Define the model's default state.
   */

  public function definition(): array
  {
    $faker = FactoryFaker::create();

    return [
      'employee_id' => Employee::all()->random()->id,
      'amount' => $faker->numberBetween(10000000, 1000000000),
      'overtime' => $faker->numberBetween(0, 150),
      'payment_date' => $faker->dateTime(),
    ];
  }

}

