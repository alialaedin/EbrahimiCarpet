<?php

namespace Modules\Supplier\Database\Factories;

use Faker\Factory as FactoryFaker;
use Illuminate\Database\Eloquent\Factories\Factory;
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

      return [
        'name' => Faker::fullName(),
        'mobile' => Faker::mobile(),
        'address' => Faker::address(),
        'status' => $faker->boolean,
      ];
    }
}

