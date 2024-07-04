<?php

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Modules\Admin\Database\Factories\AdminFactory;
use Modules\Core\Exceptions\ModelCannotBeDeletedException;
use Modules\Permission\Models\Role;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
  use HasRoles, LogsActivity, HasFactory;

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

  protected static function newFactory(): Factory
  {
    return AdminFactory::new();
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
    $role = Role::findByName($this->getRoleName());

    return $role->label;
  }

  public function getRoleName()
  {
    return $this->getRoleNames()->first();
  }

  public function isDeletable(): bool
  {
    return $this->getRoleName() !== Role::SUPER_ADMIN;
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
