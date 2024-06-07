<?php

namespace Modules\Admin\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Modules\Admin\Database\Factories\AdminFactory;
use Modules\Core\Exceptions\ModelCannotBeDeletedException;
use Modules\Permission\Models\Role;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
  use HasRoles, LogsActivity;

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

  public function getActivitylogOptions(): LogOptions
  {
    return LogOptions::defaults()
      ->logAll()
      ->setDescriptionForEvent(function (string $eventName) {

        $eventDate = verta()->format('Y/m/d');
        $eventTime = verta()->formatTime();

        $baseMessage = "در تاریخ $eventDate, ساعت $eventTime ادمین با نام {$this->attributes['name']} توسط مدیر سایت ";

        switch ($eventName) {
          case 'created':
            $message = "$baseMessage ساخته شد.";
            break;
          case 'updated':
            $message = "$baseMessage ویرایش شد.";
            break;
          case 'deleted':
            $message = "$baseMessage حذف شد.";
            break;
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

  public function isDeletable(): bool
  {
    return ($this->getRoleName() !== Role::SUPER_ADMIN);
  }

  public static function booted(): void
  {
    static::deleting(function (Admin $admin) {
      if (!$admin->isDeletable()) {
        throw new ModelCannotBeDeletedException('این ادمین قابل حذف نمی باشد!');
      }
    });
  }
}
