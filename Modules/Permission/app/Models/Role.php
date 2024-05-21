<?php

namespace Modules\Permission\Models;

use Modules\Core\App\Exceptions\ModelCannotBeDeletedException;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
	use LogsActivity;
	const SUPER_ADMIN = 'super_admin';

	protected $fillable = [
		'name',
		'label',
		'guard_name'
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
					$message .= "نقش با شناسه {$model->id} ({$model->name}) را {$action}.";
				}

				return $message;
			});
	}

	public function isDeletable(): bool
	{
		return $this->attributes['name'] !== static::SUPER_ADMIN;
	}

	// public static function booted(): void
	// {
	// 	static::deleting(function (Role $role) {
	// 		$superAdmin = static::SUPER_ADMIN;
	// 		if ($role->name === $superAdmin) {
	// 			throw new ModelCannotBeDeletedException("نقش {$superAdmin} قابل حذف نمی باشد.");
	// 		}
	// 		if ($role->users()->exists()) {
	// 			throw new ModelCannotBeDeletedException("نقش {$superAdmin} به کاربر یا کاربرانی نسبت داده شده و قابل حذف نمی باشد.");
	// 		}
	// 	});
	// }
}
