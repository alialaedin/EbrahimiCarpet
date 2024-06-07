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
		Schema::create('payments', function (Blueprint $table) {
			$table->id();
			$table->foreignIdFor(Supplier::class)->constrained()->cascadeOnDelete();
			$table->unsignedBigInteger('amount');
			$table->enum('type', ['cash', 'cheque', 'installment']);
			$table->string('image')->nullable();
			$table->timestamp('payment_date')->nullable();
			$table->date('due_date')->nullable();
			$table->text('description')->nullable();
			$table->boolean('status');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('payments');
	}
};
