<?php

namespace Modules\Supplier\Models;

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

  public function getTotalPurchasesAmountAttribute()
  {
    return $this->purchases->sum(function ($purchase) {  
      return $purchase->total_amount;  
    }); 
  }

  public function getTotalPaymentsAmountAttribute()
  {
    return $this->payments->sum('amount');
  }

  public function getPaidPaymentsAmountAttribute()
  {
    return $this->payments->filter(function ($payment) {
      return $payment->status == 1 && !is_null($payment->payment_date) && $payment->due_date <= $payment->payment_date;
    })->sum('amount');
  }

  public function getUnpaidPaymentsAmountAttribute()
  {
    return $this->payments->filter(function ($payment) {
      return $payment->status != 1 || (is_null($payment->payment_date));
    })->sum('amount');
  }

  public function getRemainingAmountAttribute()
  {
    return $this->total_purchases_amount - $this->total_payments_amount;
  }

  public function getAllPaymentsAmountAttribute()
  {
    $payments = $this->payments()->select(['id', 'type', 'amount']);

    return [
      'cheque' => (clone $payments)->where('type', '=', Payment::TYPE_CHEQUE)->sum('amount'),
      'cash' => (clone $payments)->where('type', '=', Payment::TYPE_CASH)->sum('amount'),
      'installment' => (clone $payments)->where('type', '=', Payment::TYPE_INSTALLMENT)->sum('amount')
    ];
  }

  public function getChequePaymentsAmountAttribute()
  {
    return $this->payments->where('type', '=', Payment::TYPE_CHEQUE)->sum('amount');
  }
  public function getCashPaymentsAmountAttribute()
  {
    return $this->payments->where('type', '=', Payment::TYPE_CASH)->sum('amount');
  }
  public function getInstallmentPaymentsAmountAttribute()
  {
    return $this->payments->where('type', '=', Payment::TYPE_INSTALLMENT)->sum('amount');
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
