<?php

namespace Modules\Product\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Core\Models\BaseModel;
use Modules\Store\Models\Store;

class Price extends BaseModel
{
  protected $fillable = [
    'product_id',
    'buy_price',
    'sell_price'
  ];

  public function product(): BelongsTo
  {
    return $this->belongsTo(Product::class);
  }

  public function store(): HasOne
  {
    return $this->hasOne(Store::class);
  }

}
