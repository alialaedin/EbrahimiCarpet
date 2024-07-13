<?php

namespace Modules\Store\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Core\Models\BaseModel;
use Modules\Product\Models\Price;
use Modules\Product\Models\Product;

class Store extends Model
{
	protected $fillable = ['product_id', 'balance', 'price_id', 'priority'];

	// Relations
  public function product(): BelongsTo
	{
		return $this->belongsTo(Product::class);
	}

  public function price(): BelongsTo
  {
    return $this->belongsTo(Price::class);
  }

	public function transactions(): HasMany
	{
		return $this->hasMany(StoreTransaction::class);
	}

  public static function scopeFindByPriceId(Builder $query, int $priceId): Builder|null|Store
  {
    return $query->where('price_id', $priceId)->first();
  }
}
