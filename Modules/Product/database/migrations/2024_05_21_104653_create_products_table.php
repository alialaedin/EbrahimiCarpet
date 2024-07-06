<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Product\Models\Category;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('products', function (Blueprint $table) {
			$table->id();
			$table->foreignIdFor(Category::class)->constrained()->cascadeOnDelete();
			$table->string('title')->unique();
			$table->string('print_title');
			$table->text('description')->nullable();
			$table->unsignedBigInteger('price');
			$table->boolean('status');
			$table->string('image')->nullable();
			$table->unsignedBigInteger('discount')->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('products');
	}
};
