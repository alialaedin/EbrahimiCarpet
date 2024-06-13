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
        return "تامین کننده با شناسه عددی $this->id با نام $this->name را " . config('core.events.' . $eventName);
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

	public function getRemainingAmount()
	{
		return $this->calcTotalPurchaseAmount() - $this->calcTotalPaymentAmount();
	}

  public function countPurchases()
  {
    return $this->purchases->count();
  }

  public function countPayments()
  {
    return $this->payments->count();
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
