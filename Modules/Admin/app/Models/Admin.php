<?php

namespace Modules\Admin\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
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
        return "ادمین با شناسه عددی $this->name با نام $this->id را " . config('core.events.' . $eventName);
      });
  }

  public static function booted(): void
  {
    static::deleting(function (Admin $admin) {
      if (!$admin->isDeletable()) {
        throw new ModelCannotBeDeletedException('این ادمین قابل حذف نمی باشد!');
      }
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

  public function getStatusBadgeType()
  {
    return $this->attributes['status'] ? 'success' : 'danger';
  }

  public function getStatus()
  {
    return $this->attributes['status'] ? 'فعال' : 'غیر فعال';
  }

}
