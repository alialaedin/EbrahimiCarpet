<?php

namespace Modules\Personnel\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Personnel\Models\Personnel;

class PersonnelDatabaseSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		Personnel::factory()->count(100)->create();
	}
}
