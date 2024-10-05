<?php

namespace Modules\Customer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Core\Exceptions\ModelCannotBeDeletedException;
use Modules\Sale\Models\Sale;
use Modules\Sale\Models\SalePayment;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Customer extends Model
{
  use HasFactory, LogsActivity;

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

  public function getTotalSalesAmountAttribute()
  {
    return $this->sales->sum(function ($sale) {  
      return $sale->total_amount;  
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
    return $this->total_sales_amount - $this->total_payments_amount;
  }

  public function getAllPaymentsAmountAttribute()
  {
    $payments = $this->payments()->select(['id', 'type', 'amount']);

    return [
      'cheque' => $payments->where('type', '=', SalePayment::TYPE_CHEQUE)->sum('amount'),
      'cash' => $payments->where('type', '=', SalePayment::TYPE_CASH)->sum('amount'),
      'installment' => $payments->where('type', '=', SalePayment::TYPE_INSTALLMENT)->sum('amount')
    ];
  }

  public function countSales()
  {
    return $this->sales->count();
  }

  public function countPayments()
  {
    return $this->payments->count();
  }

  public function isDeletable(): bool
  {
    return $this->sales->isEmpty() && $this->payments->isEmpty();
  }

  public function getStatusBadgeType(): string
  {
    return $this->attributes['status'] ? 'success' : 'danger';
  }

  public function getStatus(): string
  {
    return $this->attributes['status'] ? 'فعال' : 'غیر فعال';
  }

  public static function getAllCustomers(): \Illuminate\Database\Eloquent\Collection|array
  {
    return Customer::query()->select('id', 'name', 'mobile')->get();
  }

  // Relations
  public function sales(): HasMany
  {
    return $this->hasMany(Sale::class);
  }

  public function payments(): HasMany
  {
    return $this->hasMany(SalePayment::class, 'customer_id');
  }
}
