<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('suppliers', function (Blueprint $table) {
			$table->id();
			$table->string('name', 191);
			$table->string('mobile', 20)->unique();
			$table->string('telephone', 20)->unique()->nullable();
			$table->text('address');
			$table->string('national_code', 20);
			$table->string('postal_code', 20);
      $table->enum('type', ['legal', 'real']);
      $table->text('description');
			$table->boolean('status');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('suppliers');
	}
};
