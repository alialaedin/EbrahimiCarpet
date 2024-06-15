<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Employee\Models\Employee;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('salaries', function (Blueprint $table) {
      $table->id();
      $table->foreignIdFor(Employee::class)->constrained()->cascadeOnDelete();
      $table->unsignedBigInteger('amount');
      $table->unsignedInteger('overtime')->nullable();
      $table->timestamp('payment_date');
      $table->string('receipt_image')->nullable();
      $table->text('description')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('salaries');
  }
};
