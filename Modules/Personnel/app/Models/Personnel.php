<?php

namespace Modules\Personnel\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Admin\Models\Admin;
use Modules\Personnel\Database\Factories\PersonnelFactory;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Personnel extends Model
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

	protected static function newFactory(): PersonnelFactory
	{
		return PersonnelFactory::new();
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
				$personnelName = $this->name;

				switch ($eventName) {
          case 'created':
            $message = "{$messageBase} یک کارمند جدید با نام {$personnelName} را ثبت کرد.";
            break;
          case 'updated':
            $message = "{$messageBase} کارمند با نام {$personnelName} را ویرایش کرد.";
            break;
          case 'deleted':
            $message = "{$messageBase} کارمند با نام {$personnelName} را حذف کرد.";
            break;
        }

				return $message;
			});
	}
}
