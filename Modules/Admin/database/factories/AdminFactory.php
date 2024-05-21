<?php

namespace Modules\Admin\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Ybazli\Faker\Facades\Faker;

class AdminFactory extends Factory
{
	/**
	 * The name of the factory's corresponding model.
	 */
	protected $model = \Modules\Admin\Models\Admin::class;

	/**
	 * Define the model's default state.
	 */
	public function definition(): array
	{
		return [
			'name' => Faker::fullName(),
			'mobile' => Faker::mobile(),
			'password' => bcrypt('123456'),
			'status' => $this->faker->boolean() 
		];
	}
}
