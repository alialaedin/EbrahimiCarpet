<?php

namespace Modules\Payment\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Admin\Models\Admin;
use Modules\Core\Models\BaseModel;
use Modules\Supplier\Models\Supplier;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Payment extends BaseModel
{
	use HasFactory, LogsActivity;

  public const TYPE_CASH = 'cash';
  public const TYPE_INSTALLMENT = 'installment';
  public const TYPE_CHEQUE = 'cheque';

  private const MAIN_SELECTED_COLUMNS = ['id', 'supplier_id', 'amount', 'type', 'payment_date', 'status', 'created_at'];

	private const TYPES = [
		'cash' => 'نقد',
		'installment' => 'قسط',
		'cheque' => 'چک',
	];

	protected $fillable = [
		'supplier_id',
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
    $admin = auth()->user() ?? Admin::query()->where('mobile', '09368917169')->first();

    return LogOptions::defaults()
      ->logAll()
      ->setDescriptionForEvent(function (string $eventName) use ($admin) {

        $eventDate = verta()->format('Y/m/d');
        $eventTime = verta()->formatTime();
        $messageBase = "ادمین با شناسه {$admin->attributes['id']}, {$admin->attributes['name']}, در تاریخ $eventDate ساعت $eventTime";
				$payType = $this->getType();
        $supplierName = $this->supplier->name;
        $amount = number_format($this->attributes['amount']);

        switch ($eventName) {
          case 'created':
            $message = "$messageBase پرداختی از نوع $payType با مبلغ $amount ریال برای تامین کننده با نام $supplierName ثبت کرد ";
            break;
          case 'updated':
            $message = "$messageBase پرداختی از نوع $payType با مبلغ $amount ریال که متعلق به تامین کننده با نام $supplierName بود را ویرایش کرد.";
            break;
          case 'deleted':
            $message = "$messageBase پرداختی از نوع $payType با مبلغ $amount ریال که متعلق به تامین کننده با نام $supplierName بود را حذف کرد.";
            break;
        }

        return $message;
      });
  }

	// Relations
	public function supplier(): BelongsTo
  {
    return $this->belongsTo(Supplier::class);
  }

	// Functions
	public function getType(): string
  {
		return self::TYPES[$this->attributes['type']];
	}

  public function getPaymentDate(): string
  {
    $paymentDate = $this->attributes['payment_date'];

    return $paymentDate ? verta($paymentDate)->format('Y/m/d') : '-';
  }

  public function getDueDate(): string
  {
    return $this->type === self::TYPE_CASH ? '-' : verta($this->attributes['due_date'])->format('Y/m/d');
  }

  public static function getAllTypes(): array
  {
    return [
      self::TYPE_CASH,
      self::TYPE_CHEQUE,
      self::TYPE_INSTALLMENT
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
      ->when(request('supplier_id'), function(Builder $q) {
        $q->where('supplier_id', request('supplier_id'));
      })
      ->when(request('type'), function(Builder $q) {
        $q->where('type', request('type'));
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
