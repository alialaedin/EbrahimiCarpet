<?php

namespace Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Core\Models\BaseModel;
use Modules\Core\Traits\HasCache;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Revenue extends BaseModel
{
  use LogsActivity, HasCache;

  protected $fillable = [
    'headline_id',
    'title',
    'amount',
    'payment_date',
    'description'
  ];

  // Cache Names
  protected const CACHE_KEYS = [
    'all_revenues'
  ];

  // Log Activity
  public function getActivitylogOptions(): LogOptions
  {
    return LogOptions::defaults()
      ->logAll()
      ->setDescriptionForEvent(function (string $eventName) {
        return "درامد با شناسه عددی $this->id را" . config('core.events.' . $eventName);
      });
  }

  protected static function booted(): void
  {
    static::saved(fn(Revenue $revenue) => $revenue->forgetAll(static::CACHE_KEYS));

    static::created(fn() => toastr()->success('درامد جدید با موفقیت ثبت شد.'));

    static::updated(fn() => toastr()->success('درامد با موفقیت بروزرسانی شد.'));

    static::deleted(function (Revenue $revenue) {
      $revenue->forgetAll(static::CACHE_KEYS);
      toastr()->success('درامد با موفقیت حذف شد.');
    });
  }

  // Relations
  public function headline(): BelongsTo
  {
    return $this->belongsTo(Headline::class);
  }
}
