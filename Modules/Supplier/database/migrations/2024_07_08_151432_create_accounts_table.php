<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('accounts', function (Blueprint $table) {
      $table->id();
      $table->foreignIdFor(\Modules\Supplier\Models\Supplier::class)->constrained()->cascadeOnDelete();
      $table->string('bank_name', 50);
      $table->string('account_number', 30);
      $table->string('card_number', 20);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('accounts');
  }
};
