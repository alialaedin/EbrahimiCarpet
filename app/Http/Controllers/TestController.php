<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Modules\Purchase\Models\PurchaseItem;

class TestController extends Controller
{
    public function addNewColumn()
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->foreignIdFor(PurchaseItem::class)->nullable()->constrained()->cascadeOnDelete();
        });
        dd('MIGRATION DONE');
    }
}
