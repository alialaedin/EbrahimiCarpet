<?php

namespace Modules\Sale\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Core\Models\BaseModel;
use Modules\Customer\Models\Customer;
use Spatie\Activitylog\LogOptions;

class SalePayment extends BaseModel
{
  public const TYPE_CASH = 'cash';
  public const TYPE_INSTALLMENT = 'installment';
  public const TYPE_CHEQUE = 'cheque';
  private const MAIN_SELECTED_COLUMNS = ['id', 'customer_id', 'amount', 'type', 'payment_date', 'status', 'created_at'];

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

  public function scopeCheques($query)
  {
    $selectedColumns = self::MAIN_SELECTED_COLUMNS;
    array_push($selectedColumns, 'cheque_holder', 'cheque_serial', 'bank_name', 'pay_to', 'is_mine');
    
    return $query->select($selectedColumns)->where('type', '=', self::TYPE_CHEQUE);
  }

  public function scopeInstallments($query)
  {
    return $query->select(self::MAIN_SELECTED_COLUMNS)->where('type', '=', self::TYPE_INSTALLMENT);
  }

  public function scopeCashes($query)
  {
    return $query->select(self::MAIN_SELECTED_COLUMNS)->where('type', '=', self::TYPE_CASH);
  }

  public function scopeFilters($query)
  {
    return $query
      ->when(request('customer_id'), function(Builder $q) {
        $q->where('customer_id', request('customer_id'));
      })
      ->when(request('cheque_holder'), function(Builder $q) {
        $q->where('cheque_holder', 'LIKE', '%' . request('cheque_holder') . '%');
      })
      ->when(request('type'), function(Builder $q) {
        $q->where('type', request('type'));
      })
      ->when(request('cheque_serial'), function(Builder $q) {
        $q->where('cheque_serial', request('cheque_serial'));
      })
      ->when(request('from_payment_date'), function(Builder $q) {
        $q->whereDate('payment_date', '>=', request('from_payment_date'));
      })
      ->when(request('to_payment_date'), function(Builder $q) {
        $q->whereDate('payment_date', '<=', request('to_payment_date'));
      })
      ->when(request('from_due_date'), function(Builder $q) {
        $q->whereDate('due_date', '>=', request('from_due_date'));
      })
      ->when(request('to_due_date'), function(Builder $q) {
        $q->whereDate('due_date', '<=', request('to_due_date'));
      })
      ->when(!is_null(request('status')), function(Builder $q) {
        $q->where('status', request('status'));
      });
  }

}
