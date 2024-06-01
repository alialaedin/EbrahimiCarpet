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

	/**
	 * Define the model's default state.
	 */
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
			'bank_name' => Faker::word()
		];
	}
}
