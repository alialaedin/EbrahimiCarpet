<?php

namespace Modules\Supplier\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Admin\Models\Admin;
use Modules\Payment\Models\Payment;
use Modules\Purchase\Models\Purchase;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Supplier extends Model
{
	use HasFactory, LogsActivity;

	protected $fillable = [
		'name',
		'mobile',
		'address',
		'status'
	];

	public function getActivitylogOptions(): LogOptions
	{
    $admin = auth()->user() ?? Admin::where('mobile', '09368917169')->first();

		return LogOptions::defaults()
			->logAll()
			->setDescriptionForEvent(function (string $eventName) use ($admin) {
				
				$eventDate = verta()->format('Y/m/d');
        $eventTime = verta()->formatTime();
				$messageBase = "ادمین با شناسه {$admin->id}, {$admin->name}, در تاریخ {$eventDate} ساعت {$eventTime}";
				$supplierName = $this->name;

				switch ($eventName) {
          case 'created':
            $message = "{$messageBase} یک تامین کننده جدید با نام {$supplierName} را ثبت کرد.";
            break;
          case 'updated':
            $message = "{$messageBase} تامین کننده با نام {$supplierName} را ویرایش کرد.";
            break;
          case 'deleted':
            $message = "{$messageBase} تامین کننده با نام {$supplierName} را حذف کرد.";
            break;
        }

				return $message;
			});
	}

	// Functions
	public function calcTotalPurchaseAmount() 
	{
		$totalAmount = 0;

		foreach ($this->purchases as $purchase) {
			$totalAmount += $purchase->getTotalAmountWithDiscount();
		}

		return $totalAmount;
	}

	// Functions
	public function calcTotalPaymentAmount() 
	{
		return $this->payments->whereNotNull('payment_date')->sum('amount');
	}

	public function gerRemainingAmount() 
	{
		return $this->calcTotalPurchaseAmount() - $this->calcTotalPaymentAmount();
	}

	// Query Scope
	public static function scopeActive(Builder $query)
	{
	  $query->where('status', 1);
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
}
