<?php

namespace Modules\Purchase\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Modules\Admin\Models\Admin;
use Modules\Core\Exceptions\ModelCannotBeDeletedException;
use Modules\Payment\Models\Payment;
use Modules\Sale\Models\Sale;
use Modules\Store\Models\StoreTransaction;
use Modules\Supplier\Models\Supplier;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Purchase extends Model
{
  use HasFactory, LogsActivity;

  protected $fillable = [
    'supplier_id',
    'purchased_at',
    'discount'
  ];

  public function getActivitylogOptions(): LogOptions
  {
    $admin = auth()->user() ?? Admin::query()->where('mobile', '09368917169')->first();

    return LogOptions::defaults()
      ->logAll()
      ->setDescriptionForEvent(function (string $eventName) use ($admin) {

        $eventDate = verta()->format('Y/m/d');
        $eventTime = verta()->formatTime();
        $messageBase = "ادمین با شناسه {$admin->attributes['id']}, {$admin->attributes['name']}, در تاریخ $eventDate ساعت $eventTime";
        $supplierName = $this->supplier->name;

        switch ($eventName) {
          case 'created':
            $message = "$messageBase یک خرید جدید از $supplierName را ثبت کرد.";
            break;
          case 'updated':
            $message = "$messageBase خرید با شناسه عددی {$this->attributes['id']} را ویرایش کرد.";
            break;
          case 'deleted':
            $message = "$messageBase خرید با شناسه {$this->attributes['id']} که از تامین کننده با نام $supplierName ثبت شده بود را حذف کرد.";
            break;
        }

        return $message;
      });
  }

  public static function booted(): void
  {
    static::deleting(function (Purchase $purchase) {
      $purchase->transactions()->delete();
    });
  }

  // Relations
  public function supplier(): BelongsTo
  {
    return $this->belongsTo(Supplier::class);
  }

  public function items(): HasMany
  {
    return $this->hasMany(PurchaseItem::class, 'purchase_id');
  }

  public function payments(): HasMany
  {
    return $this->hasMany(Payment::class);
  }

  public function transactions(): MorphMany
  {
    return $this->morphMany(StoreTransaction::class, 'transactionable');
  }

  // Functions
  public function getTotalPurchaseAmount(): int
  {
    $totalPrice = 0;
    foreach ($this->items as $item) {
      $totalPrice += (($item->price - $item->discount) * $item->quantity);
    }

    return $totalPrice;
  }

  public function getTotalAmountWithDiscount(): int
  {
    return $this->getTotalPurchaseAmount() - $this->attributes['discount'];
  }

  public function getTotalPaymentAmount(): int
  {
    return $this->payments->where('status', 1)->whereNotNull('payment_date')->sum('amount');
  }
}
