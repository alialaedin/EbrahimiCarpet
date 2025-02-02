<?php

namespace Modules\Sale\Models;

use Hekmatinasser\Verta\Facades\Verta;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Modules\Core\Models\BaseModel;
use Modules\Customer\Models\Customer;
use Modules\Employee\Models\Employee;
use Modules\Store\Models\StoreTransaction;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Sale extends BaseModel
{
  use LogsActivity;

  protected $fillable = [
    'customer_id',
    'sold_at',
    'discount',
    'cost_of_sewing',
    'employee_id',
    'discount_for',
    'total_sell_prices',
    'total_buy_prices'
  ];

  protected $hidden = [
    'total_sell_prices',
    'total_buy_prices'
  ];

  public function getActivitylogOptions(): LogOptions
  {
    return LogOptions::defaults()
      ->logAll()
      ->setDescriptionForEvent(function (string $eventName) {
        return "فروش با کد $this->id برای مشتری با نام {$this->customer->name} را " . config('core.events.' . $eventName);
      });
  }


  public function soldAtMonth(): Attribute
  {
    return Attribute::make(
      get: fn($value) => Verta::instance($value)->month
    );
  }

  public function costOfSewing(): Attribute
  {
    return Attribute::make(
      get: fn($value) => $value ?? 0
    );
  }

  public function getTotalAmount(): int
  {
    $totalPrice = 0;
    $costOfSewing = $this->attributes['cost_of_sewing'] ?? 0;
    foreach ($this->items as $item) {
      $totalPrice += (($item->price - $item->discount) * $item->quantity);
    }
    return $totalPrice + $costOfSewing;
  }

  public function getTotalAmountWithDiscount(): int
  {
    return $this->getTotalAmount() - $this->attributes['discount'];
  }

  public function getTotalDiscountAttribute()
  {
    $itemsDiscount = $this->items->sum(function ($item) {
      return $item->total_discount_amount;
    });

    return (int)$itemsDiscount + (int)$this->attributes['discount'];
  }

  public function getTotalAmountAttribute()
  {
    $totalPrice = 0;
    $costOfSewing = $this->attributes['cost_of_sewing'] ?? 0;
    $discount = $this->attributes['discount'] ?? 0;

    foreach ($this->items as $item) {
      $totalPrice += ($item->price - $item->discount) * $item->quantity;
    }
    return $totalPrice + $costOfSewing - $discount;
  }

  public function getTotalItemsAmountAttribute()
  {
    $totalPrice = 0;
    foreach ($this->items as $item) {
      $totalPrice += (($item->price - $item->discount) * $item->quantity);
    }

    return $totalPrice;
  }

  // Relations
  public function customer(): BelongsTo
  {
    return $this->belongsTo(Customer::class);
  }

  public function employee(): BelongsTo
  {
    return $this->belongsTo(Employee::class);
  }

  public function items(): HasMany
  {
    return $this->hasMany(SaleItem::class);
  }

  public function transactions(): MorphMany
  {
    return $this->morphMany(StoreTransaction::class, 'transactionable');
  }
}
