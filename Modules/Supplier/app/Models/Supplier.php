<?php

namespace Modules\Supplier\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Core\Exceptions\ModelCannotBeDeletedException;
use Modules\Core\Models\BaseModel;
use Modules\Payment\Models\Payment;
use Modules\Purchase\Models\Purchase;
use Modules\Supplier\Database\Factories\SupplierFactory;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Supplier extends BaseModel
{
  use HasFactory, LogsActivity;

  public const TYPE_LEGAL = 'legal';
  public const TYPE_REAL = 'real';

  protected $fillable = [
    'name',
    'mobile',
    'address',
    'status',
    'telephone',
    'national_code',
    'postal_code',
    'type',
    'description'
  ];

  protected static function newFactory(): SupplierFactory
  {
    return SupplierFactory::new();
  }

  public function getActivitylogOptions(): LogOptions
  {
    return LogOptions::defaults()
      ->logAll()
      ->setDescriptionForEvent(function (string $eventName) {
        return "تامین کننده با شناسه عددی $this->id با نام $this->name را " . config('core.events.' . $eventName);
      });
  }

  protected static function booted(): void
  {
    static::deleting(function (Supplier $supplier) {
      if ($supplier->purchases->isNotEmpty()) {
        throw new ModelCannotBeDeletedException('از این تامین کننده خریدی ثبت شده و قابل حذف نمی باشد.');
      } elseif ($supplier->payments->isNotEmpty()) {
        throw new ModelCannotBeDeletedException('برای این تامین کننده پرداختی ای ثبت شده و قابل حذف نمی باشد.');
      }
    });
  }

  // Functions
  public function calcTotalPurchaseAmount(): int
  {
    $totalAmount = 0;

    foreach ($this->purchases as $purchase) {
      $totalAmount += $purchase->getTotalAmountWithDiscount();
    }

    return $totalAmount;
  }

  public function calcTotalPaymentAmount(): int|null
  {
    return $this->payments->whereNotNull('payment_date')->sum('amount');
  }

  public function getRemainingAmount(): int
  {
    return $this->calcTotalPurchaseAmount() - $this->calcTotalPaymentAmount();
  }

  public function countPurchases(): int
  {
    return $this->purchases->count();
  }

  public function countPayments(): int
  {
    return $this->payments->count();
  }

  public function isDeletable(): bool
  {
    return $this->payments->isEmpty() && $this->purchases->isEmpty() && $this->accounts->isEmpty();
  }

  public static function getAllSuppliers(): \Illuminate\Database\Eloquent\Collection|array
  {
    return Supplier::query()->select('id', 'name', 'mobile')->latest('id')->get();
  }

  // Relations
  public function purchases(): HasMany
  {
    return $this->hasMany(Purchase::class);
  }

  public function payments(): HasMany
  {
    return $this->hasMany(Payment::class);
  }

  public function accounts(): HasMany
  {
    return $this->hasMany(Account::class);
  }
}
