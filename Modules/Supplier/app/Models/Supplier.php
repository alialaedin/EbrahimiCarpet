<?php

namespace Modules\Supplier\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
					$message .= "تامین کننده با شناسه {$model->id} ({$model->name}) را {$action}.";
				}

				return $message;
			});
	}
}
