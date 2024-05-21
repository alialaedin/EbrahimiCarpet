<?php

namespace Modules\Personnel\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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

	public function getActivitylogOptions(): LogOptions
	{
		$events = [
			'created' => 'ایجاد کرد',
			'updated' => 'ویرایش کرد',
			'deleted' => 'حذف کرد',
		];

		return LogOptions::defaults()
			->logAll()
			->setDescriptionForEvent(function (string $eventName) use ($events) {
				$model = $this;
				$admin = auth()->user();
				$createdDate = verta($model->created_at)->format('Y/m/d');
				$message = "ادمین با شناسه {$admin->id} ({$admin->name}) در تاریخ {$createdDate} ";

				if (array_key_exists($eventName, $events)) {
					$action = $events[$eventName];
					$message .= "کارمند با شناسه {$model->id} ({$model->name}) را {$action}.";
				}

				return $message;
			});
	}
}
