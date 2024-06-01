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
    Schema::create('employees', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('mobile', 20)->unique();
      $table->text('address');
      $table->string('national_code', 10)->unique()->nullable();
      $table->timestamp('employmented_at');
      $table->string('card_number');
      $table->string('sheba_number')->nullable();
      $table->string('bank_name');
      $table->unsignedBigInteger('salary');
      $table->string('telephone', 50)->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('employees');
  }
};
