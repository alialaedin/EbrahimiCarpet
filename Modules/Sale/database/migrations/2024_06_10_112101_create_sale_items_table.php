<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('sale_items', function (Blueprint $table) {
      $table->id();
      $table->foreignIdFor(\Modules\Sale\Models\Sale::class)->constrained()->cascadeOnDelete();
      $table->foreignIdFor(\Modules\Product\Models\Product::class)->constrained()->cascadeOnDelete();
      $table->unsignedInteger('quantity');
      $table->unsignedBigInteger('price');
      $table->unsignedBigInteger('discount')->nullable();
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('sale_items');
  }
};
