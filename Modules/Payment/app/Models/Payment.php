<?php

namespace Modules\Payment\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Admin\Models\Admin;
use Modules\Supplier\Models\Supplier;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Payment extends Model
{
	use HasFactory, LogsActivity;

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
            $message = "$messageBase پرداختی از نوع $payType با مبلغ $amount تومان برای تامین کننده با نام $supplierName ثبت کرد ";
            break;
          case 'updated':
            $message = "$messageBase پرداختی از نوع $payType با مبلغ $amount تومان که متعلق به تامین کننده با نام $supplierName بود را ویرایش کرد.";
            break;
          case 'deleted':
            $message = "$messageBase پرداختی از نوع $payType با مبلغ $amount تومان که متعلق به تامین کننده با نام $supplierName بود را حذف کرد.";
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
		return static::TYPES[$this->attributes['type']];
	}

  public function getPaymentDate()
  {
    $paymentDate =  $this->attributes['payment_date'];
    
    return $paymentDate ? verta($paymentDate)->formatDate() : '-';
  }
}
