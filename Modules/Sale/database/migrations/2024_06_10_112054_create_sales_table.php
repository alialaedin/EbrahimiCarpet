<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

  public function up(): void
  {
    Schema::create('sales', function (Blueprint $table) {
      $table->id();
      $table->foreignIdFor(\Modules\Customer\Models\Customer::class)->constrained()->cascadeOnDelete();
      $table->timestamp('sold_at');
      $table->unsignedBigInteger('discount')->nullable();
      $table->timestamps();
    });
  }

  public function down(): void
  {
      Schema::dropIfExists('sales');
  }
};
