<?php

namespace Modules\Customer\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Core\Exceptions\ModelCannotBeDeletedException;
use Illuminate\Database\Eloquent\Collection;
use Modules\Sale\Models\Sale;
use Modules\Sale\Models\SalePayment;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Customer extends Model
{
  use LogsActivity;

  public const GENDER_MALE = 'male';
  public const GENDER_FEMALE = 'female';

  protected $fillable = [
    'name',
    'mobile',
    'telephone',
    'address',
    'status',
    'birthday',
    'gender',
    'description'
  ];

  public function getActivitylogOptions(): LogOptions
  {
    return LogOptions::defaults()
      ->logAll()
      ->setDescriptionForEvent(function (string $eventName) {
        return "مشتری با شناسه عددی $this->id با نام $this->name را " . config('core.events.' . $eventName);
      });
  }

  protected static function booted(): void
  {
    static::deleting(function (Customer $customer) {
      if ($customer->sales->isNotEmpty()) {
        throw new ModelCannotBeDeletedException('این مشتری دارای فروش است و قابل حذف نمی باشد.');
      } elseif ($customer->payments->isNotEmpty()) {
        throw new ModelCannotBeDeletedException('این مشتری دارای پرداختی است و قابل حذف نمی باشد.');
      }
    });
  }

  public static function getAvailableGenders(): array
  {
    return [
      self::GENDER_MALE, 
      self::GENDER_FEMALE
    ];
  }

  public static function getAllCustomers($columns = ['id', 'name', 'mobile']): Collection|array
  {
    return Customer::query()->select($columns)->get();
  }

  public function totalSalesAmount(): Attribute
  {
    return Attribute::make(
      get: fn () => $this->sales->sum(fn($sale) =>  $sale->total_amount)
    );
  }

  public function totalPaymentsAmount(): Attribute
  {
    return Attribute::make(
      get: fn () => $this->payments->sum('amount')
    );
  }

  public function paidPaymentsAmount(): Attribute
  {
    return Attribute::make(
      get: function () {
        return $this->payments->filter(function ($payment) {
          return $payment->status == 1 && !is_null($payment->payment_date) && $payment->due_date <= $payment->payment_date;
        })->sum('amount');
      }
    );
  }

  public function unpaidPaymentsAmount(): Attribute
  {
    return Attribute::make(
      get: function () {
        return $this->payments->filter(function ($payment) {
          return $payment->status != 1 || (is_null($payment->payment_date));
        })->sum('amount');
      }
    );
  }

  public function remainingAmount(): Attribute
  {
    return Attribute::make(
      get: fn() => $this->total_sales_amount - $this->total_payments_amount
    );
  }

  public function cashPaymentsAmount(): Attribute
  {
    return Attribute::make(
      get: fn() => $this->getPaymentsAmountByType(SalePayment::TYPE_CASH)
    );
  }

  public function chequePaymentsAmount(): Attribute
  {
    return Attribute::make(
      get: fn() => $this->getPaymentsAmountByType(SalePayment::TYPE_CHEQUE)
    );
  }

  public function installmentPaymentsAmount(): Attribute
  {
    return Attribute::make(
      get: fn() => $this->getPaymentsAmountByType(SalePayment::TYPE_INSTALLMENT)
    );
  }

  private function getPaymentsAmountByType($type): mixed
  {
    return $this->payments->where('type', $type)->sum('amount');
  }
  
  public function countSales(): mixed
  {
    return $this->sales->count();
  }

  public function countPayments(): mixed
  {
    return $this->payments->count();
  }

  public function isDeletable(): bool
  {
    return $this->sales->isEmpty() && $this->payments->isEmpty();
  }

  public function sales(): HasMany
  {
    return $this->hasMany(Sale::class);
  }

  public function payments(): HasMany
  {
    return $this->hasMany(SalePayment::class, 'customer_id');
  }
}
