<?php

namespace Modules\Sale\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Core\Models\BaseModel;
use Modules\Customer\Models\Customer;
use Spatie\Activitylog\LogOptions;

class SalePayment extends BaseModel
{
  protected $fillable = [
    'customer_id',
    'amount',
    'type',
    'image',
    'payment_date',
    'due_date',
    'description',
    'status'
  ];

  public function getActivitylogOptions(): LogOptions
  {
    return LogOptions::defaults()
      ->logAll()
      ->setDescriptionForEvent(function (string $eventName) {
        return " پرداختی از نوع{$this->getType()} با مبلغ $this->amount تومان از {$this->customer->name} را " . config('core.events.' . $eventName);
      });
  }

  // Relations
  public function customer(): BelongsTo
  {
    return $this->belongsTo(Customer::class);
  }

  // Functions
  public function getType(): string
  {
    return config('core.payment_types.' . $this->attributes['type']);
  }

  public function getPaymentDate()
  {
    $paymentDate = $this->attributes['payment_date'];

    return $paymentDate ? verta($paymentDate)->formatDate() : '-';
  }

}
