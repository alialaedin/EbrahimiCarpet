<?php

namespace Modules\Accounting\Models;


use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Core\Models\BaseModel;
use Modules\Core\Traits\HasCache;
use Modules\Employee\Models\Employee;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Salary extends BaseModel
{
  use LogsActivity, HasCache;

  protected $fillable = [
    'employee_id',
    'amount',
    'overtime',
    'payment_date',
    'receipt_image',
    'description'
  ];

  // Cache Keys
  protected const CACHE_KEYS = [
    'all_headlines'
  ];

  // Log Activity
  public function getActivitylogOptions(): LogOptions
  {
    return LogOptions::defaults()
      ->logAll()
      ->setDescriptionForEvent(function (string $eventName) {

        $employeeName = $this->employee->name;

        return "حقوق با شناسه عدی $this->id برای کارمند با نام $employeeName را " . config('core.events.' . $eventName);
      });
  }

  protected static function booted(): void
  {
    static::saved(fn(Salary $salary) => $salary->forgetAll(static::CACHE_KEYS));

    static::created(fn() => toastr()->success('حقوق جدید با موفقیت ثبت شد.'));

    static::updated(fn() => toastr()->success('حقوق با موفقیت بروزرسانی شد.'));

    static::deleted(function (Salary $salary) {
      $salary->forgetAll(static::CACHE_KEYS);
      toastr()->success('حقوق با موفقیت حذف شد.');
    });
  }

  // Relations
  public function employee(): BelongsTo
  {
    return $this->belongsTo(Employee::class);
  }
}
