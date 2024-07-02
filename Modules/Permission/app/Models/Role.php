<?php

namespace Modules\Permission\Models;

use Modules\Admin\Models\Admin;
use Modules\Core\Exceptions\ModelCannotBeDeletedException;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
	use LogsActivity;
	public const SUPER_ADMIN = 'super_admin';

	protected $fillable = [
		'name',
		'label',
		'guard_name'
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
				$roleLabel = $this->label;

				switch ($eventName) {
          case 'created':
            $message = "{$messageBase} یک نقش جدید با عنوان {$roleLabel} را ثبت کرد.";
            break;
          case 'updated':
            $message = "{$messageBase} نقش {$roleLabel} را ویرایش کرد.";
            break;
          case 'deleted':
            $message = "{$messageBase} نقش {$roleLabel} را حذف کرد.";
            break;
        }

				return $message;
			});
	}

	public function isDeletable(): bool
	{
		return !Admin::role($this->attributes['name'])->exists();
	}

	public static function booted(): void
	{
		static::deleting(function (Role $role) {
			$superAdmin = static::SUPER_ADMIN;
			if ($role->name === static::SUPER_ADMIN) {
				throw new ModelCannotBeDeletedException("نقش {$superAdmin} قابل حذف نمی باشد.");
			}
			if ($role->admins()->exists()) {
				throw new ModelCannotBeDeletedException("نقش {$role->label} به کاربر یا کاربرانی نسبت داده شده و قابل حذف نمی باشد.");
			}
		});
	}

	public function admins(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
  {
		return $this->belongsToMany(
      Admin::class,
      'model_has_roles',
      'model_id',
      'role_id',
    );
	}
}
