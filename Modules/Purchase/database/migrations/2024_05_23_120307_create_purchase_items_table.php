<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Product\Models\Product;
use Modules\Purchase\Models\Purchase;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('purchase_items', function (Blueprint $table) {
			$table->id();
			$table->foreignIdFor(Purchase::class)->constrained()->cascadeOnDelete();
			$table->foreignIdFor(Product::class)->constrained()->cascadeOnDelete();
			$table->unsignedInteger('quantity');
			$table->unsignedBigInteger('price');
			$table->unsignedBigInteger('discount')->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('purchase_items');
	}
};
