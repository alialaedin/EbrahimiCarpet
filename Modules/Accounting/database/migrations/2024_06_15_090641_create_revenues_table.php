<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Accounting\Models\Headline;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('revenues', function (Blueprint $table) {
      $table->id();
      $table->foreignIdFor(Headline::class)->constrained()->cascadeOnDelete();
      $table->string('title');
      $table->unsignedBigInteger('amount');
      $table->timestamp('payment_date');
      $table->text('description')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('revenues');
  }
};
