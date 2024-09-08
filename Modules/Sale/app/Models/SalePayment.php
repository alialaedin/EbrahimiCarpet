<?php

namespace Modules\Sale\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Core\Models\BaseModel;
use Modules\Customer\Models\Customer;
use Spatie\Activitylog\LogOptions;

class SalePayment extends BaseModel
{
  public const TYPE_CASH = 'cash';
  public const TYPE_INSTALLMENT = 'installment';
  public const TYPE_CHEQUE = 'cheque';

  protected $fillable = [
    'customer_id',
    'amount',
    'type',
    'image',
    'cheque_serial',
    'bank_name',
    'cheque_holder',
    'pay_to',
    'is_mine',
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
        return " پرداختی از نوع{$this->getType()} با مبلغ $this->amount ریال از {$this->customer->name} را " . config('core.events.' . $eventName);
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

  public function getPaymentDate(): string
  {
    $paymentDate = $this->attributes['payment_date'];

    return $paymentDate ? verta($paymentDate)->format('Y/m/d') : '-';
  }

  public function getDueDate(): string
  {
    return $this->type === static::TYPE_CASH ? '-' : verta($this->attributes['due_date'])->format('Y/m/d');
  }

  public static function getAllTypes(): array
  {
    return [
      self::TYPE_CASH,
      self::TYPE_CHEQUE,
      self::TYPE_INSTALLMENT,
    ];
  }

  public function getPaymentDateAttribute()
  {
    return $this->attributes['payment_date'] ? verta($this->attributes['payment_date'])->format('Y/m/d') : '-';
  }

  public function getDueDateAttribute()
  {
    return $this->type === self::TYPE_CASH ? '-' : verta($this->attributes['due_date'])->format('Y/m/d');
  }

}
