<?php

namespace Modules\Admin\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Admin\Database\Factories\AdminFactory;
use Modules\Permission\Models\Role;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
	use HasFactory, HasRoles, LogsActivity;

	protected $fillable = [
		'name',
		'mobile',
		'status',
		'password',
	];

	protected $hidden = [
		'password',
		'remember_token',
	];

	protected function casts(): array
	{
		return [
			'password' => 'hashed',
		];
	}

	protected static function newFactory(): AdminFactory
	{
		return AdminFactory::new();
	}

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
				$admin = auth()->user() ?? Admin::where('mobile', '09368917169')->first();
				$createdDate = verta($model->created_at)->format('Y/m/d');
				$message = "ادمین با شناسه {$admin->id} ({$admin->name}) در تاریخ {$createdDate} ";

				if (array_key_exists($eventName, $events)) {
					$action = $events[$eventName];
					$message .= "ادمین با شناسه {$model->id} ({$model->name}) را {$action}.";
				}

				return $message;
			});
	}

	// Functions
	public function getRoleLabel()
	{
		$thisRoleName = $this->getRoleNames()->first();
		$role = Role::findByName($thisRoleName);

		return $role->label;
	}

	public function getRoleName()
	{
		return $this->getRoleNames()->first();
	}
}
