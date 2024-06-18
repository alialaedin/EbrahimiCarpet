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

  protected $fillable = [
    'name',
    'mobile',
    'telephone',
    'address',
    'status'
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
      }elseif ($customer->payments->isNotEmpty()) {
        throw new ModelCannotBeDeletedException('این مشتری دارای پرداختی است و قابل حذف نمی باشد.');
      }
    });
  }

  // Functions
  public function calcTotalSalesAmount()
  {
    $totalAmount = 0;

    foreach ($this->sales as $sale) {
      $totalAmount += $sale->getTotalAmountWithDiscount();
    }

    return $totalAmount;
  }

  public function calcTotalSalePaymentsAmount()
  {
    return $this->payments->whereNotNull('payment_date')->sum('amount');
  }

  public function getRemainingAmount()
  {
    return $this->calcTotalSalesAmount() - $this->calcTotalSalePaymentsAmount();
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

  public function getStatusBadgeType()
  {
    return $this->attributes['status'] ? 'success' : 'danger';
  }

  public function getStatus()
  {
    return $this->attributes['status'] ? 'فعال' : 'غیر فعال';
  }

  // Relations
  public function sales(): HasMany
  {
    return $this->hasMany(Sale::class);
  }

  public function payments(): HasMany
  {
    return $this->hasMany(SalePayment::class);
  }
}
