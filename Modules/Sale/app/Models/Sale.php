<?php

namespace Modules\Sale\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Modules\Core\Exceptions\ModelCannotBeDeletedException;
use Modules\Core\Models\BaseModel;
use Modules\Customer\Models\Customer;
use Modules\Store\Models\StoreTransaction;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Sale extends BaseModel
{
  use LogsActivity;

  protected $fillable = [
    'customer_id',
    'sold_at',
    'discount'
  ];

  public function getActivitylogOptions(): LogOptions
  {
    return LogOptions::defaults()
      ->logAll()
      ->setDescriptionForEvent(function (string $eventName) {
        return "فروش با کد $this->id برای مشتری با نام {$this->customer->name} را " . config('core.events.' . $eventName);
      });
  }

  // Functions
  public function getTotalAmount(): int
  {
    $totalPrice = 0;
    foreach ($this->items as $item) {
      $totalPrice += (($item->price - $item->discount) * $item->quantity);
    }
    return $totalPrice;
  }

  public function getTotalAmountWithDiscount(): int
  {
    return $this->getTotalAmount() - $this->attributes['discount'];
  }

  public function getTotalPaymentAmount(): int
  {
    return $this->payments->sum('amount');
  }

  public function getTotalPaidPaymentAmount(): int
  {
    return $this->payments->where('status', 1)->whereNotNull('payment_date')->sum('amount');
  }

  // Relations
  public function customer(): BelongsTo
  {
    return $this->belongsTo(Customer::class);
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
