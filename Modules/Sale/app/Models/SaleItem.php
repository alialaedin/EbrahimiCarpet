<?php

namespace Modules\Sale\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Core\Models\BaseModel;
use Modules\Product\Models\Product;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class SaleItem extends BaseModel
{
  use LogsActivity;

  protected $fillable = [
    'sale_id',
    'product_id',
    'quantity',
    'price',
    'discount',
    'archived_price'
  ];

  protected $casts = [
    'archived_price' => 'array',
  ];

  protected $hidden = [
    'archived_price'
  ];

  public function getActivitylogOptions(): LogOptions
  {
    return LogOptions::defaults()
      ->logAll()
      ->setDescriptionForEvent(function (string $eventName) {
        return "آیتم فروش با کد $this->id برای فروش با کد {$this->sale->id} متعلق به {$this->sale->customer->name} را " . config('core.events.' . $eventName);
      });
  }

  // Relations
  public function product(): BelongsTo
  {
    return $this->belongsTo(Product::class);
  }
  public function sale(): BelongsTo
  {
    return $this->belongsTo(Sale::class);
  }

  // Functions
  public function getPriceWithDiscount(): int
  {
    $price = $this->attributes['price'];
    $discount = $this->attributes['discount'];

    return is_null($discount) ? $price : $price - $discount;
  }

  public function getTotalItemPrice(): float|int
  {
    return $this->getPriceWithDiscount() * $this->attributes['quantity'];
  }

  public function getTotalDiscountAmountAttribute()
  {
    return $this->attributes['quantity'] * $this->attributes['discount'];
  }
}
