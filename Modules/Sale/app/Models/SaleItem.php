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

  public function scopeFilters($query)
  {
    return $query 
      ->when(request('from_date'), function ($itemQuery) {
        $itemQuery->whereHas('sale', function ($saleQuery) {
          $saleQuery->whereBetween('sold_at', [request('from_date'), request('to_date')]);
        });
      })
      ->when(request('product_id'), function ($itemQuery) {
        $itemQuery->where('product_id', request('product_id'));
      })
      ->when(request('category_id'), function ($itemQuery) {
        $itemQuery->whereHas('product', function ($productQuery) {
          $productQuery->where('category_id', request('category_id'));
        });
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
