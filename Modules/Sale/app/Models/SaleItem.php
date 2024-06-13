<?php

namespace Modules\Sale\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Core\Exceptions\ModelCannotBeDeletedException;
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
  ];

  public function getActivitylogOptions(): LogOptions
  {
    return LogOptions::defaults()
      ->logAll()
      ->setDescriptionForEvent(function (string $eventName) {
        return "آیتم فروش با کد $this->id برای فروش با کد {$this->sale->id} متعلق به {$this->sale->customer->name} را " . config('core.events.' . $eventName);
      });
  }

  protected static function booted(): void
  {
    static::deleting(function (SaleItem $saleItem) {

      if ($saleItem->sale->payments->isNotEmpty()) {
        throw new ModelCannotBeDeletedException('برای این فروش پرداختی ثبت شده و آیتم های آن قابل حذف نمی باشد.');
      }

      $saleItem->incrementProductStore();
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

  public function getTotalItemPrice()
  {
    return $this->getPriceWithDiscount() * $this->attributes['quantity'];
  }

  private function incrementProductStore(): void
  {
    $this->product->store->increment('balance', $this->quantity);
  }
}
