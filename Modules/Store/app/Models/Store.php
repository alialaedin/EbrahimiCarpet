<?php

namespace Modules\Store\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Product\Models\Product;

class Store extends Model
{
	protected $fillable = ['product_id', 'balance'];

	// Relations 
	public function product(): BelongsTo
	{
		return $this->belongsTo(Product::class);
	}

	public function transactions(): HasMany
	{
		return $this->hasMany(StoreTransaction::class);
	}
}
