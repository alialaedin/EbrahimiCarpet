<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Supplier\Models\Supplier;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('purchases', function (Blueprint $table) {
			$table->id();
			$table->foreignIdFor(Supplier::class)->constrained()->cascadeOnDelete();
			$table->timestamp('purchased_at');
			$table->unsignedBigInteger('discount')->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('purchases');
	}
};
