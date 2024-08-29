<?php

namespace Modules\Employee\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Accounting\Models\Salary;
use Modules\Core\Exceptions\ModelCannotBeDeletedException;
use Modules\Core\Models\BaseModel;
use Modules\Core\Traits\HasCache;
use Modules\Employee\Database\Factories\EmployeeFactory;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Employee extends BaseModel
{
	use HasFactory, LogsActivity, HasCache;

	protected $fillable = [
		'name',
		'mobile',
		'address',
		'national_code',
		'employmented_at',
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
		return LogOptions::defaults()
			->logAll()
			->setDescriptionForEvent(function (string $eventName) {
        return "کارمند با شناسه عددی $this->id به نام $this->name را" . config('core.events.' . $eventName);
			});
	}

  protected static function booted(): void
  {
    static::created(fn() => toastr()->success('کارمند جدید با موفقیت ثبت شد.'));

    static::updated(fn() => toastr()->success('کارمند با موفقیت بروزرسانی شد.'));

    static::deleted(fn() => toastr()->success('کارمند با موفقیت حذف شد.'));

    static::deleting(function (Employee $employee) {
      if (!$employee->isDeletable()) {
        throw new ModelCannotBeDeletedException('برای کارمند حقوق ثبت شده و قابل حذف نمی باشد!');
      }
    });
  }

  // Functions
  public function isDeletable()
  {
    return $this->salaries->isEmpty();
  }

  // Relations
  public function salaries(): HasMany
  {
    return $this->hasMany(Salary::class);
  }

}
