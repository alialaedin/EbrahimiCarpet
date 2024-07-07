<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('sale_payments', function (Blueprint $table) {
      $table->id();
      $table->foreignIdFor(\Modules\Customer\Models\Customer::class)->constrained()->cascadeOnDelete();
      $table->unsignedBigInteger('amount');
      $table->enum('type', ['cash', 'cheque', 'installment']);
      $table->string('image')->nullable();
      $table->string('cheque_serial', 20)->nullable();
			$table->string('bank_name')->nullable();
			$table->string('cheque_holder')->nullable();
			$table->string('pay_to')->nullable();
			$table->boolean('is_mine')->nullable();
      $table->timestamp('payment_date')->nullable();
      $table->date('due_date')->nullable();
      $table->text('description')->nullable();
      $table->boolean('status');
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('sale_payments');
  }
};
