<?php

namespace Modules\Employee\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Admin\Models\Admin;
use Modules\Employee\Database\Factories\EmployeeFactory;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Employee extends Model
{
	use HasFactory, LogsActivity;

	protected $fillable = [
		'name',
		'mobile',
		'address',
		'national_code',
		'employment_at',
		'card_number',
		'sheba_number',
		'bank_name',
		'salary',
		'telephone'
	];

	protected static function newFactory(): EmployeeFactory
	{
		return EmployeeFactory::new();
	}

	public function getActivitylogOptions(): LogOptions
	{
		return LogOptions::defaults()
			->logAll()
			->setDescriptionForEvent(function (string $eventName) {
        return "کارمند با شناسه عددی $this->id به نام $this->name را" . config('core.events.' . $eventName);
			});
	}
}
