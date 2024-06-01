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
    $admin = auth()->user() ?? Admin::where('mobile', '09368917169')->first();

		return LogOptions::defaults()
			->logAll()
			->setDescriptionForEvent(function (string $eventName) use ($admin) {

				$eventDate = verta()->format('Y/m/d');
        $eventTime = verta()->formatTime();
        $messageBase = "ادمین با شناسه {$admin->id}, {$admin->name}, در تاریخ {$eventDate} ساعت {$eventTime}";
				$employeeName = $this->name;

				switch ($eventName) {
          case 'created':
            $message = "{$messageBase} یک کارمند جدید با نام {$employeeName} را ثبت کرد.";
            break;
          case 'updated':
            $message = "{$messageBase} کارمند با نام {$employeeName} را ویرایش کرد.";
            break;
          case 'deleted':
            $message = "{$messageBase} کارمند با نام {$employeeName} را حذف کرد.";
            break;
        }

				return $message;
			});
	}
}
