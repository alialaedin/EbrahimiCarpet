<?php

namespace Modules\Supplier\Database\Factories;

use Faker\Factory as FactoryFaker;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Supplier\Models\Supplier;
use Ybazli\Faker\Facades\Faker;

class SupplierFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Supplier\Models\Supplier::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
      $faker = FactoryFaker::create();
      $supplierTypes = collect([Supplier::TYPE_LEGAL, Supplier::TYPE_REAL]);

      return [
        'name' => Faker::fullName(),
        'mobile' => Faker::mobile(),
        'telephone' => '017' . $faker->numberBetween(11111111, 99999999),
        'national_code' => Faker::melliCode(),
        'postal_code' => $faker->numberBetween(1111111111, 999999999),
        'description' => Faker::paragraph(),
        'type' => $supplierTypes->random(),
        'address' => Faker::address(),
        'status' => $faker->boolean,
      ];
    }
}

