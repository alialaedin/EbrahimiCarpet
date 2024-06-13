<?php

namespace Modules\Store\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Modules\Purchase\Models\Purchase;
use Modules\Sale\Models\Sale;

class StoreTransaction extends Model
{
	protected $fillable = [
		'store_id',
		'type',
		'quantity',
		'description'
	];

  // Functions
  public function getTransactionableType()
  {
    return match ($this->attributes['transactionable_type']) {
      Purchase::class => 'خرید',
      Sale::class => 'فروش',
    };
  }

  public function getRoute()
  {
    return match ($this->attributes['transactionable_type']) {
      Purchase::class => 'admin.purchases.show',
      Sale::class => 'admin.sales.show',
    };
  }

	// Relations
	public function store(): BelongsTo
	{
		return $this->belongsTo(Store::class);
	}

  public function transactionable(): MorphTo
  {
    return $this->morphTo();
  }
}
