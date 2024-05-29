<?php

namespace Modules\Store\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Purchase\Models\Purchase;

class StoreTransaction extends Model
{
	protected $fillable = [
		'store_id',
		'purchase_id',
		'type',
		'quantity',
		'description'
	];

	// Relations 
	public function store(): BelongsTo
	{
		return $this->belongsTo(Store::class);
	}
	public function purchase(): BelongsTo
	{
		return $this->belongsTo(Purchase::class);
	}
}
